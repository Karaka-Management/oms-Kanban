<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   Modules\Kanban
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\Kanban\Controller;

use Modules\Kanban\Models\KanbanBoardMapper;
use Modules\Kanban\Models\KanbanCardMapper;
use Modules\Kanban\Models\PermissionCategory;
use phpOMS\Account\PermissionType;
use phpOMS\Asset\AssetType;
use phpOMS\Contract\RenderableInterface;
use phpOMS\DataStorage\Database\Query\OrderType;
use phpOMS\Message\Http\RequestStatusCode;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
use phpOMS\Views\View;

/**
 * Kanban backend controller class.
 *
 * @package Modules\Kanban
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 * @codeCoverageIgnore
 */
final class BackendController extends Controller
{
    /**
     * Routing end-point for application behavior.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function setupStyles(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        /** @var \phpOMS\Model\Html\Head $head */
        $head = $response->data['Content']->head;
        $head->addAsset(AssetType::CSS, '/Modules/Kanban/Theme/Backend/css/styles.css?v=1.0.0');
    }

    /**
     * Routing end-point for application behavior.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return RenderableInterface
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewKanbanDashboard(RequestAbstract $request, ResponseAbstract $response, array $data = []) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);

        $view->setTemplate('/Modules/Kanban/Theme/Backend/kanban-dashboard');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1005801001, $request, $response);

        $list = KanbanBoardMapper::getAll()
            ->with('tags')
            ->with('tags/title')
            ->where('tags/title/language', $request->header->l11n->language)
            ->sort('createdAt', OrderType::DESC)
            ->limit(20)
            ->execute();

        $view->data['boards'] = $list;

        return $view;
    }

    /**
     * Routing end-point for application behavior.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return RenderableInterface
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewKanbanBoard(RequestAbstract $request, ResponseAbstract $response, array $data = []) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);

        /** @var \Modules\Kanban\Models\KanbanBoard $board */
        $board = KanbanBoardMapper::get()
            ->with('columns')
            ->with('columns/cards')
            ->with('columns/cards/comments')
            ->with('columns/cards/tags')
            ->with('columns/cards/tags/title')
            ->where('id', (int) $request->getData('id'))
            ->where('columns/cards/tags/title/language', $request->header->l11n->language)
            ->execute();

        $accountId = $request->header->account;

        if ($board->createdBy->id !== $accountId
            && !$this->app->accountManager->get($accountId)->hasPermission(
                PermissionType::READ, $this->app->unitId, $this->app->appId, self::NAME, PermissionCategory::BOARD, $board->id)
        ) {
            $view->setTemplate('/Web/Backend/Error/403_inline');
            $response->header->status = RequestStatusCode::R_403;
            return $view;
        }

        $view->setTemplate('/Modules/Kanban/Theme/Backend/kanban-board');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1005801001, $request, $response);

        $view->data['board'] = $board;

        return $view;
    }

    /**
     * Routing end-point for application behavior.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return RenderableInterface
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewKanbanArchive(RequestAbstract $request, ResponseAbstract $response, array $data = []) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/Kanban/Theme/Backend/kanban-archive');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1005801001, $request, $response);

        $list = KanbanBoardMapper::getAll()
            ->with('tags')
            ->with('tags/title')
            ->where('tags/title/language', $request->header->l11n->language)
            ->sort('createdAt', OrderType::DESC)
            ->limit(25)
            ->execute();

        $view->data['boards'] = $list;

        return $view;
    }

    /**
     * Routing end-point for application behavior.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return RenderableInterface
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewKanbanBoardCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);

        $accountId = $request->header->account;

        if (!$this->app->accountManager->get($accountId)->hasPermission(
                PermissionType::CREATE, $this->app->unitId, $this->app->appId, self::NAME, PermissionCategory::BOARD)
        ) {
            $view->setTemplate('/Web/Backend/Error/403_inline');
            $response->header->status = RequestStatusCode::R_403;
            return $view;
        }

        $view->setTemplate('/Modules/Kanban/Theme/Backend/kanban-board-create');
        $view->data['nav'] = $this->app->moduleManager->get('Navigation')->createNavigationMid(1005801001, $request, $response);

        $permissionView               = new \Modules\Admin\Theme\Backend\Components\AccountPermissionSelector\BaseView($this->app->l11nManager, $request, $response);
        $view->data['permissionView'] = $permissionView;

        return $view;
    }

    /**
     * Routing end-point for application behavior.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return RenderableInterface
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewKanbanCard(RequestAbstract $request, ResponseAbstract $response, array $data = []) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);

        /** @var \Modules\Kanban\Models\KanbanCard $card */
        $card = KanbanCardMapper::get()
            ->with('tags')
            ->with('tags/title')
            ->with('files')
            ->with('createdBy')
            ->with('comments')
            ->with('comments/media')
            ->with('comments/createdBy')
            ->where('id', (int) $request->getData('id'))
            ->where('tags/title/language', $response->header->l11n->language)
            ->execute();

        $accountId = $request->header->account;

        if ($card->createdBy->id !== $accountId
            && !$this->app->accountManager->get($accountId)->hasPermission(
                PermissionType::READ, $this->app->unitId, $this->app->appId, self::NAME, PermissionCategory::CARD, $card->id)
        ) {
            $view->setTemplate('/Web/Backend/Error/403_inline');
            $response->header->status = RequestStatusCode::R_403;
            return $view;
        }

        $view->setTemplate('/Modules/Kanban/Theme/Backend/kanban-card');
        $view->data['nav']  = $this->app->moduleManager->get('Navigation')->createNavigationMid(1005801001, $request, $response);
        $view->data['card'] = $card;

        return $view;
    }
}
