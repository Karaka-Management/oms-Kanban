<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   Modules\Kanban\Models
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\Kanban\Models;

use Modules\Admin\Models\AccountMapper;
use Modules\Media\Models\MediaMapper;
use phpOMS\DataStorage\Database\Mapper\DataMapperFactory;

/**
 * Mapper class.
 *
 * @package Modules\Kanban\Models
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class KanbanCardCommentMapper extends DataMapperFactory
{
    /**
     * Columns.
     *
     * @var array<string, array{name:string, type:string, internal:string, autocomplete?:bool, readonly?:bool, writeonly?:bool, annotations?:array}>
     * @since 1.0.0
     */
    public const COLUMNS = [
        'kanban_card_comment_id'             => ['name' => 'kanban_card_comment_id',             'type' => 'int',               'internal' => 'id'],
        'kanban_card_comment_description'    => ['name' => 'kanban_card_comment_description',    'type' => 'string',            'internal' => 'description'],
        'kanban_card_comment_descriptionraw' => ['name' => 'kanban_card_comment_descriptionraw', 'type' => 'string',            'internal' => 'descriptionRaw'],
        'kanban_card_comment_card'           => ['name' => 'kanban_card_comment_card',           'type' => 'int',               'internal' => 'card'],
        'kanban_card_comment_created_at'     => ['name' => 'kanban_card_comment_created_at',     'type' => 'DateTimeImmutable', 'internal' => 'createdAt', 'readonly' => true],
        'kanban_card_comment_created_by'     => ['name' => 'kanban_card_comment_created_by',     'type' => 'int',               'internal' => 'createdBy', 'readonly' => true],
    ];

    /**
     * Has many relation.
     *
     * @var array<string, array{mapper:class-string, table:string, self?:?string, external?:?string, column?:string}>
     * @since 1.0.0
     */
    public const HAS_MANY = [
        'media' => [
            'mapper'   => MediaMapper::class,
            'table'    => 'kanban_card_comment_media',
            'external' => 'kanban_card_comment_media_dst',
            'self'     => 'kanban_card_comment_media_src',
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
            'external' => 'kanban_card_comment_created_by',
        ],
    ];

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    public const TABLE = 'kanban_card_comment';

    /**
     * Created at.
     *
     * @var string
     * @since 1.0.0
     */
    public const CREATED_AT = 'kanban_card_comment_created_at';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    public const PRIMARYFIELD = 'kanban_card_comment_id';
}
