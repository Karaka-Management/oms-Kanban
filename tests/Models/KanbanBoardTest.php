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

use Modules\Kanban\Models\BoardStatus;
use Modules\Kanban\Models\KanbanBoard;
use Modules\Kanban\Models\NullKanbanColumn;
use Modules\Tag\Models\Tag;

/**
 * @internal
 */
final class KanbanBoardTest extends \PHPUnit\Framework\TestCase
{
    private KanbanBoard $board;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->board = new KanbanBoard();
    }

    /**
     * @covers Modules\Kanban\Models\KanbanBoard
     * @group module
     */
    public function testDefault() : void
    {
        self::assertEquals(0, $this->board->id);
        self::assertEquals(BoardStatus::ACTIVE, $this->board->getStatus());
        self::assertEquals('', $this->board->name);
        self::assertEquals('', $this->board->description);
        self::assertEquals(0, $this->board->createdBy->id);
        self::assertInstanceOf('\DateTimeImmutable', $this->board->createdAt);
        self::assertEquals([], $this->board->getColumns());
    }

    /**
     * @covers Modules\Kanban\Models\KanbanBoard
     * @group module
     */
    public function testStatusInputOutput() : void
    {
        $this->board->setStatus(BoardStatus::ARCHIVED);
        self::assertEquals(BoardStatus::ARCHIVED, $this->board->getStatus());
    }

    /**
     * @covers Modules\Kanban\Models\KanbanBoard
     * @group module
     */
    public function testColumnsInputOutput() : void
    {
        $this->board->addColumn($column = new NullKanbanColumn(2));
        self::assertEquals([$column], $this->board->getColumns());
    }

    /**
     * @covers Modules\Kanban\Models\KanbanBoard
     * @group module
     */
    public function testColumnRemove() : void
    {
        $this->board->addColumn(new NullKanbanColumn(2));
        self::assertTrue($this->board->removeColumn(0));
        self::assertCount(0, $this->board->getColumns());
        self::assertFalse($this->board->removeColumn(0));
    }

    /**
     * @covers Modules\Kanban\Models\KanbanBoard
     * @group module
     */
    public function testTagInputOutput() : void
    {
        $tag = new Tag();
        $tag->setL11n('Tag');

        $this->board->addTag($tag);
        self::assertEquals($tag, $this->board->getTag(0));
        self::assertCount(1, $this->board->getTags());
    }

    /**
     * @covers Modules\Kanban\Models\KanbanBoard
     * @group module
     */
    public function testSerialize() : void
    {
        $this->board->setStatus(BoardStatus::ARCHIVED);

        $serialized = $this->board->jsonSerialize();

        self::assertEquals(
            [
                'id'             => 0,
                'status'         => BoardStatus::ARCHIVED,
                'columns'        => [],
                'tags'           => [],
            ],
            $serialized
        );
    }
}
