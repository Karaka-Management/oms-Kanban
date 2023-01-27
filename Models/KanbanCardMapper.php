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
use Modules\Media\Models\MediaMapper;
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
final class KanbanCardMapper extends DataMapperFactory
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    public const COLUMNS = [
        'kanban_card_id'             => ['name' => 'kanban_card_id',             'type' => 'int',               'internal' => 'id'],
        'kanban_card_name'           => ['name' => 'kanban_card_name',           'type' => 'string',            'internal' => 'name'],
        'kanban_card_description'    => ['name' => 'kanban_card_description',    'type' => 'string',            'internal' => 'description'],
        'kanban_card_descriptionraw' => ['name' => 'kanban_card_descriptionraw', 'type' => 'string',            'internal' => 'descriptionRaw'],
        'kanban_card_style'          => ['name' => 'kanban_card_style',          'type' => 'string',            'internal' => 'style'],
        'kanban_card_type'           => ['name' => 'kanban_card_type',           'type' => 'int',               'internal' => 'type'],
        'kanban_card_status'         => ['name' => 'kanban_card_status',         'type' => 'int',               'internal' => 'status'],
        'kanban_card_order'          => ['name' => 'kanban_card_order',          'type' => 'int',               'internal' => 'order'],
        'kanban_card_color'          => ['name' => 'kanban_card_color',          'type' => 'string',            'internal' => 'color'],
        'kanban_card_ref'            => ['name' => 'kanban_card_ref',            'type' => 'int',               'internal' => 'ref'],
        'kanban_card_column'         => ['name' => 'kanban_card_column',         'type' => 'int',               'internal' => 'column'],
        'kanban_card_created_at'     => ['name' => 'kanban_card_created_at',     'type' => 'DateTimeImmutable', 'internal' => 'createdAt', 'readonly' => true],
        'kanban_card_created_by'     => ['name' => 'kanban_card_created_by',     'type' => 'int',               'internal' => 'createdBy', 'readonly' => true],
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
            'external' => 'kanban_card_created_by',
        ],
    ];

    /**
     * Has many relation.
     *
     * @var array<string, array{mapper:class-string, table:string, self?:?string, external?:?string, column?:string}>
     * @since 1.0.0
     */
    public const HAS_MANY = [
        'media'    => [
            'mapper'   => MediaMapper::class,
            'table'    => 'kanban_card_media',
            'external' => 'kanban_card_media_dst',
            'self'     => 'kanban_card_media_src',
        ],
        'comments' => [
            'mapper'   => KanbanCardCommentMapper::class,
            'table'    => 'kanban_card_comment',
            'self'     => 'kanban_card_comment_card',
            'external' => null,
        ],
        'tags' => [
            'mapper'   => TagMapper::class,
            'table'    => 'kanban_card_tag',
            'self'     => 'kanban_card_tag_dst',
            'external' => 'kanban_card_tag_src',
        ],
    ];

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    public const TABLE = 'kanban_card';

    /**
     * Created at.
     *
     * @var string
     * @since 1.0.0
     */
    public const CREATED_AT = 'kanban_card_created_at';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    public const PRIMARYFIELD ='kanban_card_id';
}
