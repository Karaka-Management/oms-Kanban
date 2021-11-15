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

use Modules\Kanban\Models\KanbanColumn;
use Modules\Kanban\Models\KanbanColumnMapper;

/**
 * @internal
 */
final class KanbanColumnMapperTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @depends Modules\Kanban\tests\Models\KanbanBoardMapperTest::testCRUD
     * @covers Modules\Kanban\Models\KanbanColumnMapper
     * @group module
     */
    public function testCRUD() : void
    {
        $column = new KanbanColumn();

        $column->name  = 'Some Column';
        $column->board = 1;
        $column->order = 1;

        $id = KanbanColumnMapper::create($column);
        self::assertGreaterThan(0, $column->getId());
        self::assertEquals($id, $column->getId());

        $columnR = KanbanColumnMapper::get($column->getId());
        self::assertEquals($column->name, $columnR->name);
        self::assertEquals($column->board, $columnR->board);
        self::assertEquals($column->order, $columnR->order);
    }
}