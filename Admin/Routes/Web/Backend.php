<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

use Modules\Kanban\Controller\BackendController;
use Modules\Kanban\Models\PermissionCategory;
use phpOMS\Account\PermissionType;
use phpOMS\Router\RouteVerb;

return [
    '^/kanban(\?.*$|$)' => [
        [
            'dest'       => '\Modules\Kanban\Controller\BackendController:setupStyles',
            'verb'       => RouteVerb::GET,
            'active' => true,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::KANBAN,
            ],
        ],
    ],
    '^/kanban/dashboard(\?.*$|$)' => [
        [
            'dest'       => '\Modules\Kanban\Controller\BackendController:viewKanbanDashboard',
            'verb'       => RouteVerb::GET,
            'active' => true,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::KANBAN,
            ],
        ],
    ],
    '^/kanban/archive(\?.*$|$)' => [
        [
            'dest'       => '\Modules\Kanban\Controller\BackendController:viewKanbanArchive',
            'verb'       => RouteVerb::GET,
            'active' => true,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::KANBAN,
            ],
        ],
    ],
    '^/kanban/board(\?.*$|$)' => [
        [
            'dest'       => '\Modules\Kanban\Controller\BackendController:viewKanbanBoard',
            'verb'       => RouteVerb::GET,
            'active' => true,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::BOARD,
            ],
        ],
    ],
    '^/kanban/card/view(\?.*$|$)' => [
        [
            'dest'       => '\Modules\Kanban\Controller\BackendController:viewKanbanCard',
            'verb'       => RouteVerb::GET,
            'active' => true,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::CARD,
            ],
        ],
    ],
    '^/kanban/card/create(\?.*$|$)' => [
        [
            'dest'       => '\Modules\Kanban\Controller\BackendController:viewKanbanCard',
            'verb'       => RouteVerb::GET,
            'active' => true,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionCategory::CARD,
            ],
        ],
    ],
    '^/kanban/create(\?.*$|$)' => [
        [
            'dest'       => '\Modules\Kanban\Controller\BackendController:viewKanbanBoardCreate',
            'verb'       => RouteVerb::GET,
            'active' => true,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionCategory::KANBAN,
            ],
        ],
    ],
    '^/kanban/edit(\?.*$|$)' => [
        [
            'dest'       => '\Modules\Kanban\Controller\BackendController:viewKanbanBoardEdit',
            'verb'       => RouteVerb::GET,
            'active' => true,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::MODIFY,
                'state'  => PermissionCategory::BOARD,
            ],
        ],
    ],
];
