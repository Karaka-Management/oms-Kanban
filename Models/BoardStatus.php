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
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace Modules\Kanban\Models;

use phpOMS\Stdlib\Base\Enum;

/**
 * Board status enum.
 *
 * @package Modules\Kanban\Models
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
abstract class BoardStatus extends Enum
{
    public const ACTIVE = 1;

    public const INACTIVE = 2;

    public const ARCHIVED = 3;
}
