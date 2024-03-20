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

use phpOMS\Stdlib\Base\Enum;

/**
 * Permission category enum.
 *
 * @package Modules\Kanban\Models
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class PermissionCategory extends Enum
{
    public const BOARD = 1;

    public const CARD = 2;

    public const COMMENT = 3;

    public const KANBAN = 4;

    public const LIST = 5;

    public const MODERATION = 4;

    public const D_CREATE = [
        self::BOARD => [
            'DEFAULT' => ['RUDP'], // limited by own permissions
            'MAX'     => ['RUDP'], // limited by own permissions
        ],

        self::CARD => [
            'DEFAULT' => ['RU'], // limited by own permissions
            'MAX'     => ['RUD'], // limited by own permissions
        ],
    ];
}
