<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   Modules\Kanban\Models
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\Kanban\Models;

use phpOMS\DataStorage\Database\Mapper\DataMapperFactory;

/**
 * Mapper class.
 *
 * @package Modules\Kanban\Models
 * @license OMS License 1.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class KanbanColumnMapper extends DataMapperFactory
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    public const COLUMNS = [
        'kanban_column_id'    => ['name' => 'kanban_column_id',    'type' => 'int',    'internal' => 'id'],
        'kanban_column_name'  => ['name' => 'kanban_column_name',  'type' => 'string', 'internal' => 'name'],
        'kanban_column_order' => ['name' => 'kanban_column_order', 'type' => 'int',    'internal' => 'order'],
        'kanban_column_board' => ['name' => 'kanban_column_board', 'type' => 'int',    'internal' => 'board'],
    ];

    /**
     * Has many relation.
     *
     * @var array<string, array{mapper:class-string, table:string, self?:?string, external?:?string, column?:string}>
     * @since 1.0.0
     */
    public const HAS_MANY = [
        'cards' => [
            'mapper'   => KanbanCardMapper::class,
            'table'    => 'kanban_card',
            'self'     => 'kanban_card_column',
            'external' => null,
        ],
    ];

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    public const TABLE = 'kanban_column';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    public const PRIMARYFIELD = 'kanban_column_id';
}
