<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

use Modules\Kanban\Controller\ApiController;
use Modules\Kanban\Models\PermissionCategory;
use phpOMS\Account\PermissionType;
use phpOMS\Router\RouteVerb;

return [
    '^.*/kanban(\?.*|$)' => [
        [
            'dest'       => '\Modules\Kanban\Controller\ApiController:apiKanbanBoardCreate',
            'verb'       => RouteVerb::PUT,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionCategory::BOARD,
            ],
        ],
        [
            'dest'       => '\Modules\Kanban\Controller\ApiController:apiKanbanBoardUpdate',
            'verb'       => RouteVerb::SET,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::MODIFY,
                'state'  => PermissionCategory::BOARD,
            ],
        ],
        [
            'dest'       => '\Modules\Kanban\Controller\ApiController:apiKanbanBoardDelete',
            'verb'       => RouteVerb::DELETE,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::DELETE,
                'state'  => PermissionCategory::BOARD,
            ],
        ],
    ],
    '^.*/kanban/column(\?.*|$)' => [
        [
            'dest'       => '\Modules\Kanban\Controller\ApiController:apiKanbanColumnCreate',
            'verb'       => RouteVerb::PUT,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionCategory::BOARD,
            ],
        ],
        [
            'dest'       => '\Modules\Kanban\Controller\ApiController:apiKanbanColumnUpdate',
            'verb'       => RouteVerb::SET,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::MODIFY,
                'state'  => PermissionCategory::BOARD,
            ],
        ],
        [
            'dest'       => '\Modules\Kanban\Controller\ApiController:apiKanbanColumnDelete',
            'verb'       => RouteVerb::DELETE,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::DELETE,
                'state'  => PermissionCategory::BOARD,
            ],
        ],
    ],
    '^.*/kanban/card(\?.*|$)' => [
        [
            'dest'       => '\Modules\Kanban\Controller\ApiController:apiKanbanCardCreate',
            'verb'       => RouteVerb::PUT,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionCategory::BOARD,
            ],
        ],
        [
            'dest'       => '\Modules\Kanban\Controller\ApiController:apiKanbanCardUpdate',
            'verb'       => RouteVerb::SET,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::MODIFY,
                'state'  => PermissionCategory::BOARD,
            ],
        ],
        [
            'dest'       => '\Modules\Kanban\Controller\ApiController:apiKanbanCardDelete',
            'verb'       => RouteVerb::DELETE,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::DELETE,
                'state'  => PermissionCategory::BOARD,
            ],
        ],
    ],
    '^.*/kanban/comment(\?.*|$)' => [
        [
            'dest'       => '\Modules\Kanban\Controller\ApiController:apiKanbanCommentCreate',
            'verb'       => RouteVerb::PUT,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::CREATE,
                'state'  => PermissionCategory::BOARD,
            ],
        ],
        [
            'dest'       => '\Modules\Kanban\Controller\ApiController:apiKanbanCommentUpdate',
            'verb'       => RouteVerb::SET,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::MODIFY,
                'state'  => PermissionCategory::BOARD,
            ],
        ],
        [
            'dest'       => '\Modules\Kanban\Controller\ApiController:apiKanbanCommentDelete',
            'verb'       => RouteVerb::DELETE,
            'csrf'       => true,
            'active'     => true,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::DELETE,
                'state'  => PermissionCategory::BOARD,
            ],
        ],
    ]
];
