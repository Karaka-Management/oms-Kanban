<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
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
     * @covers \Modules\Kanban\Models\KanbanColumnMapper
     * @group module
     */
    public function testCRUD() : void
    {
        $column = new KanbanColumn();

        $column->name  = 'Some Column';
        $column->board = 1;
        $column->order = 1;

        $id = KanbanColumnMapper::create()->execute($column);
        self::assertGreaterThan(0, $column->id);
        self::assertEquals($id, $column->id);

        $columnR = KanbanColumnMapper::get()->where('id', $column->id)->execute();
        self::assertEquals($column->name, $columnR->name);
        self::assertEquals($column->board, $columnR->board);
        self::assertEquals($column->order, $columnR->order);
    }
}
