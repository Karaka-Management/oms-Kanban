<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules\Kanban
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\Kanban\Controller;

use Modules\Admin\Models\AccountMapper;
use Modules\Admin\Models\NullAccount;
use Modules\Comments\Models\Comment;
use Modules\Kanban\Models\BoardStatus;
use Modules\Kanban\Models\CardStatus;
use Modules\Kanban\Models\CardType;
use Modules\Kanban\Models\KanbanBoard;
use Modules\Kanban\Models\KanbanBoardMapper;
use Modules\Kanban\Models\KanbanCard;
use Modules\Kanban\Models\KanbanCardMapper;
use Modules\Kanban\Models\KanbanColumn;
use Modules\Kanban\Models\KanbanColumnMapper;
use Modules\Kanban\Models\PermissionCategory;
use Modules\Media\Models\NullMedia;
use Modules\Notification\Models\Notification;
use Modules\Notification\Models\NotificationMapper;
use Modules\Notification\Models\NotificationType;
use phpOMS\Message\Http\RequestStatusCode;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
use phpOMS\Utils\Parser\Markdown\Markdown;

/**
 * Kanban controller class.
 *
 * @package Modules\Kanban
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class ApiController extends Controller
{
    /**
     * Create a notification for a card
     *
     * @param KanbanCard      $card    Card to create notification for
     * @param RequestAbstract $request Request
     *
     * @return void
     *
     * @performance This should happen in the cli if possible?
     *
     * @since 1.0.0
     */
    private function createCardNotifications(KanbanCard $card, RequestAbstract $request) : void
    {
        $accounts = AccountMapper::findReadPermission(
            $this->app->unitId,
            self::NAME,
            PermissionCategory::CARD,
            $card->id
        );

        foreach ($accounts as $account) {
            $notification             = new Notification();
            $notification->module     = self::NAME;
            $notification->title      = $card->name;
            $notification->createdBy  = $card->createdBy;
            $notification->createdFor = new NullAccount($account);
            $notification->type       = NotificationType::CREATE;
            $notification->category   = PermissionCategory::CARD;
            $notification->element    = $card->id;
            $notification->redirect   = '{/base}/kanban/card?{?}&id=' . $card->id;

            $this->createModel($request->header->account, $notification, NotificationMapper::class, 'notification', $request->getOrigin());
        }
    }

    /**
     * Create a notification for a card
     *
     * @param Comment         $comment Comment to create notification for
     * @param RequestAbstract $request Request
     *
     * @return void
     *
     * @performance This should happen in the cli if possible?
     *
     * @since 1.0.0
     */
    private function createCommentNotifications(Comment $comment, RequestAbstract $request) : void
    {
        $card = KanbanCardMapper::get()
            ->with('commentList')
            ->with('commentList/comments')
            ->where('commentList', $comment->list)
            ->execute();

        $accounts = [];
        if ($card->createdBy->id !== $comment->createdBy->id) {
            $accounts[] = $card->createdBy->id;
        }

        if ($card->commentList !== null) {
            foreach ($card->commentList->comments as $element) {
                if ($element->createdBy->id !== $comment->createdBy->id) {
                    $accounts[] = $element->createdBy->id;
                }
            }
        }

        foreach ($accounts as $account) {
            $notification             = new Notification();
            $notification->module     = self::NAME;
            $notification->title      = $card->name;
            $notification->createdBy  = $card->createdBy;
            $notification->createdFor = new NullAccount($account);
            $notification->type       = NotificationType::CREATE;
            $notification->category   = PermissionCategory::CARD;
            $notification->element    = $card->id;
            $notification->redirect   = '{/base}/kanban/card?{?}&id=' . $card->id;

            $this->createModel($request->header->account, $notification, NotificationMapper::class, 'notification', $request->getOrigin());
        }
    }

    /**
     * Routing end-point for application behavior.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiKanbanCardCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateKanbanCardCreate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidCreateResponse($request, $response, $val);

            return;
        }

        $card = $this->createKanbanCardFromRequest($request);
        $this->createModel($request->header->account, $card, KanbanCardMapper::class, 'card', $request->getOrigin());

        $this->createCardNotifications($card, $request);

        $this->createStandardCreateResponse($request, $response, $card);
    }

    /**
     * Method to create card from request.
     *
     * @param RequestAbstract $request Request
     *
     * @return KanbanCard
     *
     * @since 1.0.0
     */
    public function createKanbanCardFromRequest(RequestAbstract $request) : KanbanCard
    {
        $card                 = new KanbanCard();
        $card->name           = $request->getDataString('title') ?? '';
        $card->descriptionRaw = $request->getDataString('plain') ?? '';
        $card->description    = Markdown::parse($request->getDataString('plain') ?? '');
        $card->style          = $request->getDataString('style') ?? '';
        $card->column         = (int) $request->getData('column');
        $card->order          = $request->getDataInt('order') ?? 1;
        $card->ref            = $request->getDataInt('ref') ?? 0;
        $card->status         = CardStatus::tryFromValue($request->getDataInt('status')) ?? CardStatus::ACTIVE;
        $card->type           = CardType::tryFromValue($request->getDataInt('type')) ?? CardType::TEXT;
        $card->createdBy      = new NullAccount($request->header->account);

        // allow comments
        if ($request->hasData('allow_comments')
            && ($commentApi = $this->app->moduleManager->get('Comments', 'Api'))::ID > 0
        ) {
            /** @var \Modules\Comments\Controller\ApiController $commentApi */
            $commnetList       = $commentApi->createCommentList();
            $card->commentList = $commnetList;
        }

        /*
        if ($request->hasData('tags')) {
            $card->tags = $this->app->moduleManager->get('Tag', 'Api')->createTagsFromRequest($request);
        }
        */

        // @todo Implement correct path (based on board id)
        if (!empty($request->files)) {
            $uploaded = $this->app->moduleManager->get('Media', 'Api')->uploadFiles(
                names: [],
                fileNames: [],
                files: $request->files,
                account: $request->header->account,
                basePath: __DIR__ . '/../../../Modules/Media/Files/Modules/Kanban',
                virtualPath: '/Modules/Kanban',
            );

            foreach ($uploaded as $media) {
                $card->files[] = $media;
            }
        }

        $mediaFiles = $request->getDataJson('media');
        foreach ($mediaFiles as $media) {
            $card->files[] = new NullMedia($media);
        }

        return $card;
    }

    /**
     * Validate card create request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool>
     *
     * @since 1.0.0
     */
    private function validateKanbanCardCreate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['title'] = !$request->hasData('title'))
            || ($val['column'] = !$request->hasData('column'))
            || ($val['status'] = (
                $request->hasData('status')
                && !CardStatus::isValidValue((int) $request->getData('status'))
            ))
            || ($val['type'] = (
                $request->hasData('type')
                && !CardType::isValidValue((int) $request->getData('type'))
            ))
        ) {
            return $val;
        }

        return [];
    }

    /**
     * Routing end-point for application behavior.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiKanbanBoardCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateKanbanBoardCreate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidCreateResponse($request, $response, $val);

            return;
        }

        $board = $this->createKanbanBoardFromRequest($request);
        $this->createModel($request->header->account, $board, KanbanBoardMapper::class, 'board',$request->getOrigin());
        $this->createStandardCreateResponse($request, $response, $board);
    }

    /**
     * Method to create board from request.
     *
     * @param RequestAbstract $request Request
     *
     * @return KanbanBoard
     *
     * @since 1.0.0
     */
    public function createKanbanBoardFromRequest(RequestAbstract $request) : KanbanBoard
    {
        $board                 = new KanbanBoard();
        $board->name           = (string) $request->getData('title');
        $board->color          = $request->getDataString('color') ?? '';
        $board->description    = Markdown::parse($request->getDataString('plain') ?? '');
        $board->descriptionRaw = $request->getDataString('plain') ?? '';
        $board->order          = $request->getDataInt('order') ?? 1;
        $board->status         = BoardStatus::tryFromValue($request->getDataInt('status')) ?? BoardStatus::ACTIVE;
        $board->createdBy      = new NullAccount($request->header->account);

        /*
        if ($request->hasData('tags')) {
            $board->tags = $this->app->moduleManager->get('Tag', 'Api')->createTagsFromRequest($request);
        }
        */

        return $board;
    }

    /**
     * Validate board create request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool>
     *
     * @since 1.0.0
     */
    private function validateKanbanBoardCreate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['title'] = !$request->hasData('title'))
            || ($val['status'] = (
                $request->hasData('status')
                && !CardStatus::isValidValue((int) $request->getData('status'))
            ))
        ) {
            return $val;
        }

        return [];
    }

    /**
     * Routing end-point for application behavior.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiKanbanBoardUpdate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        /** @var \Modules\Kanban\Models\KanbanBoard $old */
        $old = KanbanBoardMapper::get()->where('id', (int) $request->getData('id'))->execute();
        $new = $this->updateBoardFromRequest($request, clone $old);

        $this->updateModel($request->header->account, $old, $new, KanbanBoardMapper::class, 'board', $request->getOrigin());
        $this->createStandardUpdateResponse($request, $response, $new);
    }

    /**
     * Method to update board from request.
     *
     * @param RequestAbstract $request Request
     *
     * @return KanbanBoard
     *
     * @since 1.0.0
     */
    private function updateBoardFromRequest(RequestAbstract $request, KanbanBoard $new) : KanbanBoard
    {
        $new->name           = $request->getDataString('title') ?? $new->name;
        $new->description    = Markdown::parse($request->getDataString('plain') ?? $new->descriptionRaw);
        $new->descriptionRaw = $request->getDataString('plain') ?? $new->descriptionRaw;
        $new->order          = $request->getDataInt('order') ?? $new->order;
        $new->status         = BoardStatus::tryFromValue($request->getDataInt('status')) ?? $new->status;
        $new->style          = $request->getDataString('style') ?? $new->style;

        return $new;
    }

    /**
     * Routing end-point for application behavior.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiKanbanColumnCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateKanbanColumnCreate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidCreateResponse($request, $response, $val);

            return;
        }

        $column = $this->createKanbanColumnFromRequest($request);
        $this->createModel($request->header->account, $column, KanbanColumnMapper::class, 'column', $request->getOrigin());
        $this->createStandardCreateResponse($request, $response, $column);
    }

    /**
     * Method to create column from request.
     *
     * @param RequestAbstract $request Request
     *
     * @return KanbanColumn
     *
     * @since 1.0.0
     */
    public function createKanbanColumnFromRequest(RequestAbstract $request) : KanbanColumn
    {
        $column        = new KanbanColumn();
        $column->name  = (string) $request->getData('title');
        $column->board = (int) $request->getData('board');
        $column->order = $request->getDataInt('order') ?? 1;

        return $column;
    }

    /**
     * Validate column create request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool>
     *
     * @since 1.0.0
     */
    private function validateKanbanColumnCreate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['title'] = !$request->hasData('title')
            || ($val['board'] = !$request->hasData('board')))
        ) {
            return $val;
        }

        return [];
    }

    /**
     * Api method to create comment
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiCommentCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        $this->app->moduleManager->get('Comment', 'Api')->apiCommentCreate($request, $response, $data);
        $comment = $response->getDataArray($request->uri->__toString())['response'] ?? null;

        if ($comment === null) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidCreateResponse($request, $response, $comment);

            return;
        }

        $this->createCommentNotifications($comment, $request);
    }
}
