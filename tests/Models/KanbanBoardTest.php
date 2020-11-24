<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
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
use Modules\Kanban\Models\BoardStatus;
use Modules\Kanban\Models\KanbanBoard;

/**
 * @internal
 */
class KanbanBoardTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Modules\Kanban\Models\KanbanBoard
     * @group module
     */
    public function testDefault() : void
    {
        $board = new KanbanBoard();

        self::assertEquals(0, $board->getId());
        self::assertEquals(BoardStatus::ACTIVE, $board->getStatus());
        self::assertEquals('', $board->name);
        self::assertEquals('', $board->description);
        self::assertEquals(0, $board->createdBy->getId());
        self::assertInstanceOf('\DateTimeImmutable', $board->createdAt);
        self::assertEquals([], $board->getColumns());
    }

    /**
     * @covers Modules\Kanban\Models\KanbanBoard
     * @group module
     */
    public function testSetGet() : void
    {
        $board = new KanbanBoard();

        $board->name        = 'Name';
        $board->description = 'Description';
        $board->setStatus(BoardStatus::ARCHIVED);
        $board->createdBy = new NullAccount(1);
        $board->addColumn(2);

        self::assertEquals(BoardStatus::ARCHIVED, $board->getStatus());
        self::assertEquals('Name', $board->name);
        self::assertEquals('Description', $board->description);
        self::assertEquals(1, $board->createdBy->getId());
        self::assertEquals([2], $board->getColumns());
    }
}
