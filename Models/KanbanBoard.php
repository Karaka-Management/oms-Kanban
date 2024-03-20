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
use Modules\Tag\Models\Tag;

/**
 * Kanban board class.
 *
 * @package Modules\Kanban\Models
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class KanbanBoard implements \JsonSerializable
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
     * Color.
     *
     * @var string
     * @since 1.0.0
     */
    public string $color = '';

    /**
     * Board status.
     *
     * @var int
     * @since 1.0.0
     */
    public int $status = BoardStatus::ACTIVE;

    /**
     * Order.
     *
     * @var int
     * @since 1.0.0
     */
    public int $order = 0;

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
     * Board style.
     *
     * @var string
     * @since 1.0.0
     */
    public string $style = '';

    /**
     * Tags.
     *
     * @var Tag[]
     * @since 1.0.0
     */
    public array $tags = [];

    /**
     * Creator.
     *
     * @var Account
     * @since 1.0.0
     */
    public Account $createdBy;

    /**
     * Created.
     *
     * @var \DateTimeImmutable
     * @since 1.0.0
     */
    public \DateTimeImmutable $createdAt;

    /**
     * Board columns.
     *
     * @var \Modules\Kanban\Models\KanbanColumn[]
     * @since 1.0.0
     */
    public array $columns = [];

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
     * Get the columns
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getColumns() : array
    {
        return $this->columns;
    }

    /**
     * Add a column
     *
     * @param KanbanColumn $column Column
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function addColumn(KanbanColumn $column) : void
    {
        $this->columns[] = $column;
    }

    /**
     * Remove a column
     *
     * @param int $id Id to remove
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function removeColumn(int $id) : bool
    {
        if (isset($this->columns[$id])) {
            unset($this->columns[$id]);

            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray() : array
    {
        return [
            'id'      => $this->id,
            'status'  => $this->status,
            'columns' => $this->columns,
            'tags'    => $this->tags,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize() : mixed
    {
        return $this->toArray();
    }
}
