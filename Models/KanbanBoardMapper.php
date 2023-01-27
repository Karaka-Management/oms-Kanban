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

use Modules\Admin\Models\AccountMapper;
use Modules\Tag\Models\TagMapper;
use phpOMS\DataStorage\Database\Mapper\DataMapperFactory;

/**
 * Mapper class.
 *
 * @package Modules\Kanban\Models
 * @license OMS License 1.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class KanbanBoardMapper extends DataMapperFactory
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    public const COLUMNS = [
        'kanban_board_id'         => ['name' => 'kanban_board_id',         'type' => 'int',               'internal' => 'id'],
        'kanban_board_name'       => ['name' => 'kanban_board_name',       'type' => 'string',            'internal' => 'name'],
        'kanban_board_color'       => ['name' => 'kanban_board_color',       'type' => 'string',            'internal' => 'color'],
        'kanban_board_desc'       => ['name' => 'kanban_board_desc',       'type' => 'string',            'internal' => 'description'],
        'kanban_board_descraw'    => ['name' => 'kanban_board_descraw',    'type' => 'string',            'internal' => 'descriptionRaw'],
        'kanban_board_status'     => ['name' => 'kanban_board_status',     'type' => 'int',               'internal' => 'status'],
        'kanban_board_order'      => ['name' => 'kanban_board_order',      'type' => 'int',               'internal' => 'order'],
        'kanban_board_style'      => ['name' => 'kanban_board_style',      'type' => 'string',            'internal' => 'style'],
        'kanban_board_created_by' => ['name' => 'kanban_board_created_by', 'type' => 'int',               'internal' => 'createdBy', 'readonly' => true],
        'kanban_board_created_at' => ['name' => 'kanban_board_created_at', 'type' => 'DateTimeImmutable', 'internal' => 'createdAt', 'readonly' => true],
    ];

    /**
     * Has many relation.
     *
     * @var array<string, array{mapper:class-string, table:string, self?:?string, external?:?string, column?:string}>
     * @since 1.0.0
     */
    public const HAS_MANY = [
        'columns' => [
            'mapper'   => KanbanColumnMapper::class,
            'table'    => 'kanban_column',
            'self'     => 'kanban_column_board',
            'external' => null,
        ],
        'tags' => [
            'mapper'   => TagMapper::class,
            'table'    => 'kanban_board_tag',
            'self'     => 'kanban_board_tag_dst',
            'external' => 'kanban_board_tag_src',
        ],
    ];

    /**
     * Belongs to.
     *
     * @var array<string, array{mapper:class-string, external:string, column?:string, by?:string}>
     * @since 1.0.0
     */
    public const BELONGS_TO = [
        'createdBy' => [
            'mapper'   => AccountMapper::class,
            'external' => 'kanban_board_created_by',
        ],
    ];

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    public const TABLE = 'kanban_board';

    /**
     * Created at.
     *
     * @var string
     * @since 1.0.0
     */
    public const CREATED_AT = 'kanban_board_created_at';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    public const PRIMARYFIELD ='kanban_board_id';
}
