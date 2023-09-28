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
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
use phpOMS\Utils\Parser\Markdown\Markdown;

/**
 * Kanban controller class.
 *
 * @package Modules\Kanban
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class ApiController extends Controller
{
    /**
     * Routing end-point for application behaviour.
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
        $card->setStatus($request->getDataInt('status') ?? CardStatus::ACTIVE);
        $card->setType($request->getDataInt('type') ?? CardType::TEXT);
        $card->createdBy = new NullAccount($request->header->account);

        if (!empty($tags = $request->getDataJson('tags'))) {
            foreach ($tags as $tag) {
                if (!isset($tag['id'])) {
                    $request->setData('title', $tag['title'], true);
                    $request->setData('color', $tag['color'], true);
                    $request->setData('icon', $tag['icon'] ?? null, true);
                    $request->setData('language', $tag['language'], true);

                    $internalResponse = new HttpResponse();
                    $this->app->moduleManager->get('Tag')->apiTagCreate($request, $internalResponse);

                    if (!\is_array($data = $internalResponse->get($request->uri->__toString()))) {
                        continue;
                    }

                    $card->addTag($data['response']);
                } else {
                    $card->addTag(new NullTag((int) $tag['id']));
                }
            }
        }

        if (!empty($uploadedFiles = $request->files)) {
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

        if (!empty($mediaFiles = $request->getDataJson('media'))) {
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
     * Routing end-point for application behaviour.
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
    public function apiKanbanCardCommentCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateKanbanCardCommentCreate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidCreateResponse($request, $response, $val);

            return;
        }

        $comment = $this->createKanbanCardCommentFromRequest($request);
        $this->createModel($request->header->account, $comment, KanbanCardCommentMapper::class, 'comment', $request->getOrigin());
        $this->createStandardCreateResponse($request, $response, $comment);
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
        $comment->description    = Markdown::parse($request->getDataString('plain') ?? '');
        $comment->descriptionRaw = $request->getDataString('plain') ?? '';
        $comment->card           = (int) $request->getData('card');
        $comment->createdBy      = new NullAccount($request->header->account);

        if (!empty($uploadedFiles = $request->files)) {
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
        if (($val['plain'] = !$request->hasData('plain'))
            || ($val['card'] = !$request->hasData('card'))
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
        $board->setStatus($request->getDataInt('status') ?? BoardStatus::ACTIVE);
        $board->createdBy = new NullAccount($request->header->account);

        if (!empty($tags = $request->getDataJson('tags'))) {
            foreach ($tags as $tag) {
                if (!isset($tag['id'])) {
                    $request->setData('title', $tag['title'], true);
                    $request->setData('color', $tag['color'], true);
                    $request->setData('icon', $tag['icon'] ?? null, true);
                    $request->setData('language', $tag['language'], true);

                    $internalResponse = new HttpResponse();
                    $this->app->moduleManager->get('Tag')->apiTagCreate($request, $internalResponse);

                    if (!\is_array($data = $internalResponse->get($request->uri->__toString()))) {
                        continue;
                    }

                    $board->addTag($data['response']);
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
     * Routing end-point for application behaviour.
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
        $new->setStatus($request->getDataInt('status') ?? $new->getStatus());
        $new->style = $request->getDataString('style') ?? $new->style;

        return $new;
    }

    /**
     * Routing end-point for application behaviour.
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
}
