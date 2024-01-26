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

use Modules\Kanban\Models\KanbanCard;
use Modules\Kanban\Models\KanbanColumn;

/**
 * @internal
 */
final class KanbanColumnTest extends \PHPUnit\Framework\TestCase
{
    private KanbanColumn $column;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->column = new KanbanColumn();
    }

    /**
     * @covers Modules\Kanban\Models\KanbanColumn
     * @group module
     */
    public function testDefault() : void
    {
        self::assertEquals(0, $this->column->id);
        self::assertEquals('', $this->column->name);
        self::assertEquals(0, $this->column->order);
        self::assertEquals(0, $this->column->board);
        self::assertEquals([], $this->column->getCards());
    }

    /**
     * @covers Modules\Kanban\Models\KanbanColumn
     * @group module
     */
    public function testCardInputOutput() : void
    {
        $this->column->addCard(new KanbanCard());
        self::assertCount(1, $this->column->getCards());
    }

    /**
     * @covers Modules\Kanban\Models\KanbanColumn
     * @group module
     */
    public function testCardRemove() : void
    {
        $this->column->addCard(new KanbanCard());
        self::assertCount(1, $this->column->getCards());
        self::assertTrue($this->column->removeCard(0));
        self::assertCount(0, $this->column->getCards());
        self::assertFalse($this->column->removeCard(0));
    }

    /**
     * @covers Modules\Kanban\Models\KanbanColumn
     * @group module
     */
    public function testSerialize() : void
    {
        $this->column->name  = 'Name';
        $this->column->board = 2;
        $this->column->order = 3;

        $serialized = $this->column->jsonSerialize();
        unset($serialized['createdBy']);
        unset($serialized['createdAt']);

        self::assertEquals(
            [
                'id'    => 0,
                'name'  => 'Name',
                'order' => 3,
                'board' => 2,
                'cards' => [],
            ],
            $serialized
        );
    }
}
