<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules\Kanban\Models
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\Kanban\Models;

use Modules\Admin\Models\Account;
use Modules\Admin\Models\NullAccount;
use Modules\Comments\Models\CommentList;
use Modules\Tag\Models\Tag;
use Modules\Tasks\Models\Task;

/**
 * Kanban card class.
 *
 * @package Modules\Kanban\Models
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 *
 * @todo Implement unread cards/comments notification/highlight
 *      See tasks for inspiration. However, here we also need to highlight the entire board for unread content
 *      https://github.com/Karaka-Management/oms-Kanban/issues/5
 */
class KanbanCard implements \JsonSerializable
{
    /**
     * ID.
     *
     * @var int
     * @since 1.0.0
     */
    public int $id = 0;

    /**
     * Name.
     *
     * @var string
     * @since 1.0.0
     */
    public string $name = '';

    /**
     * Card status.
     *
     * @var int
     * @since 1.0.0
     */
    public int $status = CardStatus::ACTIVE;

    /**
     * Card type.
     *
     * @var int
     * @since 1.0.0
     */
    public int $type = CardType::TEXT;

    /**
     * Color scheme.
     *
     * @var string
     * @since 1.0.0
     */
    public string $color = '';

    /**
     * Card style.
     *
     * @var string
     * @since 1.0.0
     */
    public string $style = '';

    /**
     * Description.
     *
     * @var string
     * @since 1.0.0
     */
    public string $description = '';

    /**
     * Description.
     *
     * @var string
     * @since 1.0.0
     */
    public string $descriptionRaw = '';

    /**
     * Tags.
     *
     * @var Tag[]
     * @since 1.0.0
     */
    public array $tags = [];

    /**
     * Column this card belongs to.
     *
     * @var int
     * @since 1.0.0
     */
    public int $column = 0;

    /**
     * Card order/position.
     *
     * @var int
     * @since 1.0.0
     */
    public int $order = 0;

    /**
     * Reference of this card.
     *
     * The reference is based on the card type and can be a task, calendar, ...
     *
     * @var int
     * @since 1.0.0
     */
    public int $ref = 0;

    /**
     * Created by.
     *
     * @var Account
     * @since 1.0.0
     */
    public Account $createdBy;

    /**
     * Created at.
     *
     * @var \DateTimeImmutable
     * @since 1.0.0
     */
    public \DateTimeImmutable $createdAt;

    /**
     * Comments
     *
     * @var null|CommentList
     * @since 1.0.0
     */
    public ?CommentList $commentList = null;

    /**
     * Constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable('now');
        $this->createdBy = new NullAccount();
    }

    /**
     * Add tag
     *
     * @param Tag $tag Tag
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function addTag(Tag $tag) : void
    {
        $this->tags[] = $tag;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray() : array
    {
        return [
            'id'             => $this->id,
            'title'          => $this->name,
            'description'    => $this->description,
            'descriptionRaw' => $this->descriptionRaw,
            'status'         => $this->status,
            'type'           => $this->type,
            'column'         => $this->column,
            'order'          => $this->order,
            'ref'            => $this->ref,
            'createdBy'      => $this->createdBy,
            'createdAt'      => $this->createdAt,
            'comments'       => $this->commentList,
            'media'          => $this->files,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize() : mixed
    {
        return $this->toArray();
    }

    /**
     * Create a task from a card
     *
     * @param Task $task Task to create the card from
     *
     * @return KanbanCard
     *
     * @since 1.0.0
     */
    public static function createFromTask(Task $task) : self
    {
        $card      = new self();
        $card->ref = $task->id;

        return $card;
    }

    use \Modules\Media\Models\MediaListTrait;
}
