<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   Modules\Kanban\Models
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace Modules\Kanban\Models;

use Modules\Admin\Models\Account;
use Modules\Admin\Models\NullAccount;
use Modules\Media\Models\Media;
use Modules\Tag\Models\Tag;
use Modules\Tag\Models\NullTag;
use Modules\Tasks\Models\Task;

/**
 * Kanban card class.
 *
 * @package Modules\Kanban\Models
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class KanbanCard implements \JsonSerializable
{
    /**
     * ID.
     *
     * @var int
     * @since 1.0.0
     */
    protected int $id = 0;

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
    private int $status = CardStatus::ACTIVE;

    /**
     * Card type.
     *
     * @var int
     * @since 1.0.0
     */
    private int $type = CardType::TEXT;

    /**
     * Color schme.
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
    private array $tags = [];

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
     * Comments.
     *
     * @var \Modules\Kanban\Models\KanbanCardComment[]
     * @since 1.0.0
     */
    private array $comments = [];

    /**
     * Media
     *
     * @var Media[]
     * @since 1.0.0
     */
    private array $media = [];

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
     * Get id.
     *
     * @return int Model id
     *
     * @since 1.0.0
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * Get the status
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getStatus() : int
    {
        return $this->status;
    }

    /**
     * Set the status
     *
     * @param int $status Status
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setStatus(int $status) : void
    {
        $this->status = $status;
    }

    /**
     * Get the card type
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getType() : int
    {
        return $this->type;
    }

    /**
     * Set the card type
     *
     * @param int $type Card type
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setType(int $type) : void
    {
        $this->type = $type;
    }

    /**
     * Count the amount of comments in a card
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getCommentCount() : int
    {
        return \count($this->comments);
    }

    /**
     * Get the comments
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getComments() : array
    {
        return $this->comments;
    }

    /**
     * Add a comment
     *
     * @param mixed $comment Comment
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function addComment($comment) : void
    {
        $this->comments[] = $comment;
    }

    /**
     * Remove a comment
     *
     * @param int $id Comment id
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function removeComment(int $id) : bool
    {
        if (isset($this->comments[$id])) {
            unset($this->comments[$id]);

            return true;
        }

        return false;
    }

    /**
     * Get the media files
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getMedia() : array
    {
        return $this->media;
    }

    /**
     * Add a media file
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function addMedia(Media $media) : void
    {
        $this->media[] = $media;
    }

    /**
     * Get tags
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getTags() : array
    {
        return $this->tags;
    }

    /**
     * Get task elements.
     *
     * @param int $id Element id
     *
     * @return Tag
     *
     * @since 1.0.0
     */
    public function getTag(int $id) : Tag
    {
        return $this->tags[$id] ?? new NullTag();
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
            'id'       => $this->id,
            'title'       => $this->name,
            'description' => $this->description,
            'descriptionRaw' => $this->descriptionRaw,
            'status'      => $this->status,
            'type'        => $this->type,
            'column'      => $this->column,
            'order'       => $this->order,
            'ref'         => $this->ref,
            'createdBy'   => $this->createdBy,
            'createdAt'   => $this->createdAt,
            'comments'    => $this->comments,
            'media'       => $this->media,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
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
        $card->ref = $task->getId();

        return $card;
    }
}
