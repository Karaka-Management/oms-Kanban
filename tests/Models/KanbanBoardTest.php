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

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\Modules\Kanban\Models\KanbanBoard::class)]
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

    #[\PHPUnit\Framework\Attributes\Group('module')]
    public function testDefault() : void
    {
        self::assertEquals(0, $this->board->id);
        self::assertEquals(BoardStatus::ACTIVE, $this->board->status);
        self::assertEquals('', $this->board->name);
        self::assertEquals('', $this->board->description);
        self::assertEquals(0, $this->board->createdBy->id);
        self::assertInstanceOf('\DateTimeImmutable', $this->board->createdAt);
        self::assertEquals([], $this->board->getColumns());
    }

    #[\PHPUnit\Framework\Attributes\Group('module')]
    public function testColumnsInputOutput() : void
    {
        $this->board->addColumn($column = new NullKanbanColumn(2));
        self::assertEquals([$column], $this->board->getColumns());
    }

    #[\PHPUnit\Framework\Attributes\Group('module')]
    public function testColumnRemove() : void
    {
        $this->board->addColumn(new NullKanbanColumn(2));
        self::assertTrue($this->board->removeColumn(0));
        self::assertCount(0, $this->board->getColumns());
        self::assertFalse($this->board->removeColumn(0));
    }

    #[\PHPUnit\Framework\Attributes\Group('module')]
    public function testSerialize() : void
    {
        $this->board->status = BoardStatus::ARCHIVED;

        $serialized = $this->board->jsonSerialize();

        self::assertEquals(
            [
                'id'      => 0,
                'status'  => BoardStatus::ARCHIVED,
                'columns' => [],
                'tags'    => [],
            ],
            $serialized
        );
    }
}
