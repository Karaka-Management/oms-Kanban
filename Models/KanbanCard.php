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

    private int $status = CardStatus::ACTIVE;

    private int $type = CardType::TEXT;

    public string $color = '';

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

    private int $column = 0;

    private int $order = 0;

    private int $ref = 0;

    public Account $createdBy;

    public \DateTimeImmutable $createdAt;

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
     * Get the order
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getOrder() : int
    {
        return $this->order;
    }

    /**
     * Set the order
     *
     * @param int $order Order
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setOrder(int $order) : void
    {
        $this->order = $order;
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
     * Set the column
     *
     * @param int $id Id
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setColumn(int $id) : void
    {
        $this->column = $id;
    }

    /**
     * Get the column
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getColumn() : int
    {
        return $this->column;
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
     * Get the reference if the card references another object (e.g. task, calendar etc.)
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getRef() : int
    {
        return $this->ref;
    }

    /**
     * Set the reference
     *
     * @param int $ref Reference
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setRef(int $ref) : void
    {
        $this->ref = $ref;
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
    public function jsonSerialize() : array
    {
        return [
            'title'       => $this->name,
            'description' => $this->description,
            'status'      => $this->status,
            'type'        => $this->type,
            'column'      => $this->name,
            'order'       => $this->name,
            'ref'         => $this->name,
            'createdBy'   => $this->name,
            'createdAt'   => $this->name,
            'comments'    => $this->name,
            'media'       => $this->name,
        ];
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
        $card = new self();
        $card->setRef($task->getId());

        return $card;
    }
}
