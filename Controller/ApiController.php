<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   Modules\Kanban
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
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
use Modules\Kanban\Models\KanbanCardComment;
use Modules\Kanban\Models\KanbanCardCommentMapper;
use Modules\Kanban\Models\KanbanCardMapper;
use Modules\Kanban\Models\KanbanColumn;
use Modules\Kanban\Models\KanbanColumnMapper;
use Modules\Media\Models\NullMedia;
use Modules\Tag\Models\NullTag;
use phpOMS\Message\Http\HttpResponse;
use phpOMS\Message\Http\RequestStatusCode;
use phpOMS\Message\NotificationLevel;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
use phpOMS\Model\Message\FormValidation;
use phpOMS\Utils\Parser\Markdown\Markdown;

/**
 * Kanban controller class.
 *
 * @package Modules\Kanban
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
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
        $card                 = new KanbanCard();
        $card->name           = (string) ($request->getData('title'));
        $card->descriptionRaw = (string) ($request->getData('plain') ?? '');
        $card->description    = Markdown::parse((string) ($request->getData('plain') ?? ''));
        $card->style          = (string) ($request->getData('style') ?? '');
        $card->column         = (int) $request->getData('column');
        $card->order          = (int) ($request->getData('order') ?? 1);
        $card->ref            = (int) ($request->getData('ref') ?? 0);
        $card->setStatus((int) ($request->getData('status') ?? CardStatus::ACTIVE));
        $card->setType((int) ($request->getData('type') ?? CardType::TEXT));
        $card->createdBy = new NullAccount($request->header->account);

        if (!empty($tags = $request->getDataJson('tags'))) {
            foreach ($tags as $tag) {
                if (!isset($tag['id'])) {
                    $request->setData('title', $tag['title'], true);
                    $request->setData('color', $tag['color'], true);
                    $request->setData('icon', $tag['icon'] ?? null, true);
                    $request->setData('language', $tag['language'], true);

                    $internalResponse = new HttpResponse();
                    $this->app->moduleManager->get('Tag')->apiTagCreate($request, $internalResponse, null);
                    $card->addTag($internalResponse->get($request->uri->__toString())['response']);
                } else {
                    $card->addTag(new NullTag((int) $tag['id']));
                }
            }
        }

        if (!empty($uploadedFiles = $request->getFiles() ?? [])) {
            $uploaded = $this->app->moduleManager->get('Media')->uploadFiles(
                [],
                [],
                $uploadedFiles,
                $request->header->account,
                __DIR__ . '/../../../Modules/Media/Files/Modules/Kanban',
                '/Modules/Kanban',
            );

            foreach ($uploaded as $media) {
                $card->addMedia($media);
            }
        }

        if (!empty($mediaFiles = $request->getDataJson('media') ?? [])) {
            foreach ($mediaFiles as $media) {
                $card->addMedia(new NullMedia($media));
            }
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
        $comment                 = new KanbanCardComment();
        $comment->description    = Markdown::parse((string) ($request->getData('plain') ?? ''));
        $comment->descriptionRaw = (string) ($request->getData('plain') ?? '');
        $comment->card           = (int) $request->getData('card');
        $comment->createdBy      = new NullAccount($request->header->account);

        if (!empty($uploadedFiles = $request->getFiles() ?? [])) {
            $uploaded = $this->app->moduleManager->get('Media')->uploadFiles(
                [],
                [],
                $uploadedFiles,
                $request->header->account,
                __DIR__ . '/../../../Modules/Media/Files/Modules/Kanban',
                '/Modules/Kanban',
            );

            foreach ($uploaded as $media) {
                $comment->addMedia($media);
            }
        }

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
        $board                 = new KanbanBoard();
        $board->name           = (string) $request->getData('title');
        $board->description    = Markdown::parse((string) ($request->getData('plain') ?? ''));
        $board->descriptionRaw = (string) ($request->getData('plain') ?? '');
        $board->order          = (int) ($request->getData('order') ?? 1);
        $board->setStatus((int) ($request->getData('status') ?? BoardStatus::ACTIVE));
        $board->createdBy = new NullAccount($request->header->account);

        if (!empty($tags = $request->getDataJson('tags'))) {
            foreach ($tags as $tag) {
                if (!isset($tag['id'])) {
                    $request->setData('title', $tag['title'], true);
                    $request->setData('color', $tag['color'], true);
                    $request->setData('icon', $tag['icon'] ?? null, true);
                    $request->setData('language', $tag['language'], true);

                    $internalResponse = new HttpResponse();
                    $this->app->moduleManager->get('Tag')->apiTagCreate($request, $internalResponse, null);
                    $board->addTag($internalResponse->get($request->uri->__toString())['response']);
                } else {
                    $board->addTag(new NullTag((int) $tag['id']));
                }
            }
        }

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
    public function apiKanbanBoardUpdate(RequestAbstract $request, ResponseAbstract $response, $data = null) : void
    {
        $old = clone KanbanBoardMapper::get()->where('id', (int) $request->getData('id'))->execute();
        $new = $this->updateBoardFromRequest($request);
        $this->updateModel($request->header->account, $old, $new, KanbanBoardMapper::class, 'board', $request->getOrigin());
        $this->fillJsonResponse($request, $response, NotificationLevel::OK, 'Board', 'Board successfully updated', $new);
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
    private function updateBoardFromRequest(RequestAbstract $request) : KanbanBoard
    {
        /** @var KanbanBoard $board */
        $board                 = KanbanBoardMapper::get()->where('id', (int) $request->getData('id'))->execute();
        $board->name           = $request->getData('title') ?? $board->name;
        $board->description    = Markdown::parse((string) ($request->getData('plain') ?? $board->descriptionRaw));
        $board->descriptionRaw = (string) ($request->getData('plain') ?? $board->descriptionRaw);
        $board->order          = (int) ($request->getData('order') ?? $board->order);
        $board->setStatus((int) ($request->getData('status') ?? $board->getStatus()));
        $board->style = (string) ($request->getData('style') ?? $board->style);

        return $board;
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
        $column        = new KanbanColumn();
        $column->name  = (string) $request->getData('title');
        $column->board = (int) $request->getData('board');
        $column->order = (int) ($request->getData('order') ?? 1);

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
