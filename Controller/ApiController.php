<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   Modules\Kanban
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace Modules\Kanban\Controller;

use Modules\Admin\Models\NullAccount;
use Modules\Kanban\Models\BoardStatus;
use Modules\Kanban\Models\CardStatus;
use Modules\Kanban\Models\CardType;
use Modules\Kanban\Models\KanbanBoard;
use Modules\Kanban\Models\KanbanBoardMapper;
use Modules\Kanban\Models\KanbanCard;
use Modules\Kanban\Models\KanbanCardMapper;
use Modules\Kanban\Models\KanbanColumn;
use Modules\Kanban\Models\KanbanColumnMapper;
use phpOMS\Message\Http\RequestStatusCode;
use phpOMS\Message\NotificationLevel;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
use phpOMS\Model\Message\FormValidation;
use Modules\Kanban\Models\KanbanCardComment;
use Modules\Kanban\Models\KanbanCardCommentMapper;

/**
 * Kanban controller class.
 *
 * @package Modules\Kanban
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 *
 * @todo Orange-Management/Modules#77
 *  Edit functionality
 *  Currently nothing can be edited (change)
 */
final class ApiController extends Controller
{
    /**
     * Routing end-point for application behaviour.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiKanbanCardCreate(RequestAbstract $request, ResponseAbstract $response, $data = null) : void
    {
        if (!empty($val = $this->validateKanbanCardCreate($request))) {
            $response->set('kanban_card_create', new FormValidation($val));
            $response->header->status = RequestStatusCode::R_400;

            return;
        }

        $card = $this->createKanbanCardFromRequest($request);
        $this->createModel($request->header->account, $card, KanbanCardMapper::class, 'card', $request->getOrigin());
        $this->fillJsonResponse($request, $response, NotificationLevel::OK, 'Card', 'Card successfully created.', $card);
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
        $card = new KanbanCard();
        $card->name = (string) ($request->getData('title'));
        $card->description = (string) ($request->getData('plain') ?? '');
        $card->setColumn((int) $request->getData('column'));
        $card->setOrder((int) ($request->getData('order') ?? 1));
        $card->setRef((int) ($request->getData('ref') ?? 0));
        $card->setStatus((int) ($request->getData('status') ?? CardStatus::ACTIVE));
        $card->setType((int) ($request->getData('type') ?? CardType::TEXT));
        $card->createdBy = new NullAccount($request->header->account);

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
        if (($val['title'] = empty($request->getData('title')))
            || ($val['column'] = empty($request->getData('column')))
            || ($val['status'] = (
                $request->getData('status') !== null
                && !CardStatus::isValidValue((int) $request->getData('status'))
            ))
            || ($val['type'] = (
                $request->getData('type') !== null
                && !CardType::isValidValue((int) $request->getData('type'))
            ))
        ) {
            return $val;
        }

        return [];
    }

    /**
     * Routing end-point for application behaviour.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiKanbanCardCommentCreate(RequestAbstract $request, ResponseAbstract $response, $data = null) : void
    {
        if (!empty($val = $this->validateKanbanCardCommentCreate($request))) {
            $response->set('kanban_comment_create', new FormValidation($val));
            $response->header->status = RequestStatusCode::R_400;

            return;
        }

        $comment = $this->createKanbanCardCommentFromRequest($request);
        $this->createModel($request->header->account, $comment, KanbanCardCommentMapper::class, 'comment', $request->getOrigin());
        $this->fillJsonResponse($request, $response, NotificationLevel::OK, 'Card', 'Card successfully created.', $comment);
    }

    /**
     * Method to create comment from request.
     *
     * @param RequestAbstract $request Request
     *
     * @return KanbanCardComment
     *
     * @since 1.0.0
     */
    public function createKanbanCardCommentFromRequest(RequestAbstract $request) : KanbanCardComment
    {
        $comment = new KanbanCardComment();
        $comment->description = (string) ($request->getData('plain') ?? '');
        $comment->setCard((int) $request->getData('card'));
        $comment->createdBy = new NullAccount($request->header->account);

        return $comment;
    }

    /**
     * Validate comment create request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool>
     *
     * @since 1.0.0
     */
    private function validateKanbanCardCommentCreate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['plain'] = empty($request->getData('plain')))
            || ($val['card'] = empty($request->getData('card')))
        ) {
            return $val;
        }

        return [];
    }

    /**
     * Routing end-point for application behaviour.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiKanbanBoardCreate(RequestAbstract $request, ResponseAbstract $response, $data = null) : void
    {
        if (!empty($val = $this->validateKanbanBoardCreate($request))) {
            $response->set('kanban_board_create', new FormValidation($val));
            $response->header->status = RequestStatusCode::R_400;

            return;
        }

        $board = $this->createKanbanBoardFromRequest($request);
        $this->createModel($request->header->account, $board, KanbanBoardMapper::class, 'board',$request->getOrigin());
        $this->fillJsonResponse($request, $response, NotificationLevel::OK, 'Board', 'Board successfully created.', $board);
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
        $board = new KanbanBoard();
        $board->name = (string) $request->getData('title');
        $board->description = (string) ($request->getData('plain') ?? '');
        $board->setOrder((int) ($request->getData('order') ?? 1));
        $board->setStatus((int) ($request->getData('status') ?? BoardStatus::ACTIVE));
        $board->createdBy = new NullAccount($request->header->account);

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
        if (($val['title'] = empty($request->getData('title')))
            || ($val['status'] = (
                $request->getData('status') !== null
                && !CardStatus::isValidValue((int) $request->getData('status'))
            ))
        ) {
            return $val;
        }

        return [];
    }

    /**
     * Routing end-point for application behaviour.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiKanbanColumnCreate(RequestAbstract $request, ResponseAbstract $response, $data = null) : void
    {
        if (!empty($val = $this->validateKanbanColumnCreate($request))) {
            $response->set('kanban_column_create', new FormValidation($val));
            $response->header->status = RequestStatusCode::R_400;

            return;
        }

        $column = $this->createKanbanColumnFromRequest($request);
        $this->createModel($request->header->account, $column, KanbanColumnMapper::class, 'column', $request->getOrigin());
        $this->fillJsonResponse($request, $response, NotificationLevel::OK, 'Column', 'Column successfully created.', $column);
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
        $column = new KanbanColumn();
        $column->name = (string) $request->getData('title');
        $column->setBoard((int) $request->getData('board'));
        $column->setOrder((int) ($request->getData('order') ?? 1));

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
        if (($val['title'] = empty($request->getData('title'))
            || ($val['board'] = empty($request->getData('board'))))
        ) {
            return $val;
        }

        return [];
    }
}
