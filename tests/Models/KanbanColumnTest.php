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

use Modules\Kanban\Models\KanbanCard;
use Modules\Kanban\Models\KanbanColumn;

/**
 * @internal
 */
class KanbanColumnTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Modules\Kanban\Models\KanbanColumn
     * @group module
     */
    public function testDefault() : void
    {
        $column = new KanbanColumn();

        self::assertEquals(0, $column->getId());
        self::assertEquals('', $column->name);
        self::assertEquals(0, $column->getOrder());
        self::assertEquals(0, $column->getBoard());
        self::assertEquals([], $column->getCards());
    }

    /**
     * @covers Modules\Kanban\Models\KanbanColumn
     * @group module
     */
    public function testSetGet() : void
    {
        $column = new KanbanColumn();

        $column->name = 'Name';
        $column->order = 2;
        $column->setBoard(3);
        $column->addCard(new KanbanCard());

        self::assertEquals('Name', $column->name);
        self::assertEquals(2, $column->getOrder());
        self::assertEquals(3, $column->getBoard());
    }
}
