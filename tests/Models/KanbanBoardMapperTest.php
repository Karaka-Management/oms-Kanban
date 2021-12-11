<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace Modules\Kanban\tests\Models;

use Modules\Admin\Models\NullAccount;
use Modules\Kanban\Models\KanbanBoard;
use Modules\Kanban\Models\KanbanBoardMapper;

/**
 * @internal
 */
final class KanbanBoardMapperTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Modules\Kanban\Models\KanbanBoardMapper
     * @group module
     */
    public function testCRUD() : void
    {
        $board = new KanbanBoard();

        $board->name        = 'Test Board 0';
        $board->description = 'This is some description';
        $board->createdBy   = new NullAccount(1);

        $id = KanbanBoardMapper::create()->execute($board);
        self::assertGreaterThan(0, $board->getId());
        self::assertEquals($id, $board->getId());

        $boardR = KanbanBoardMapper::get()->where('id', $board->getId())->execute();
        self::assertEquals($board->name, $boardR->name);
        self::assertEquals($board->getStatus(), $boardR->getStatus());
        self::assertEquals($board->description, $boardR->description);
        self::assertEquals($board->createdBy->getId(), $boardR->createdBy->getId());
        self::assertEquals($board->createdAt->format('Y-m-d'), $boardR->createdAt->format('Y-m-d'));
        self::assertEquals($board->getColumns(), $boardR->getColumns());
    }
}
