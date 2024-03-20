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

use Modules\Kanban\Models\CardStatus;
use Modules\Kanban\Models\CardType;
use Modules\Kanban\Models\KanbanCard;
use Modules\Kanban\Models\NullKanbanCardComment;
use Modules\Tasks\Models\Task;

/**
 * @internal
 */
final class KanbanCardTest extends \PHPUnit\Framework\TestCase
{
    private KanbanCard $card;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->card = new KanbanCard();
    }

    /**
     * @covers \Modules\Kanban\Models\KanbanCard
     * @group module
     */
    public function testDefault() : void
    {
        self::assertEquals(0, $this->card->id);
        self::assertEquals(CardStatus::ACTIVE, $this->card->status);
        self::assertEquals(CardType::TEXT, $this->card->type);
        self::assertEquals('', $this->card->name);
        self::assertEquals('', $this->card->description);
        self::assertEquals(0, $this->card->column);
        self::assertEquals(0, $this->card->order);
        self::assertEquals(0, $this->card->createdBy->id);
        self::assertInstanceOf('\DateTimeImmutable', $this->card->createdAt);
        self::assertEquals([], $this->card->tags);
        self::assertEquals([], $this->card->files);
    }

    /**
     * @covers \Modules\Kanban\Models\KanbanCard
     * @group module
     */
    public function testColumnInputOutput() : void
    {
        $this->card->column = 1;
        self::assertEquals(1, $this->card->column);
    }

    /**
     * @covers \Modules\Kanban\Models\KanbanCard
     * @group module
     */
    public function testCreateFromTask() : void
    {
        self::assertInstanceOf('\Modules\Kanban\Models\KanbanCard', $this->card->createFromTask(new Task()));
    }

    /**
     * @covers \Modules\Kanban\Models\KanbanCard
     * @group module
     */
    public function testSerialize() : void
    {
        $this->card->name           = 'Title';
        $this->card->description    = 'Description';
        $this->card->descriptionRaw = 'DescriptionRaw';
        $this->card->order          = 3;
        $this->card->column         = 2;
        $this->card->status         = CardStatus::ARCHIVED;
        $this->card->type           = CardType::TASK;

        $serialized = $this->card->jsonSerialize();
        unset($serialized['createdBy']);
        unset($serialized['createdAt']);

        self::assertEquals(
            [
                'id'             => 0,
                'title'          => 'Title',
                'description'    => 'Description',
                'descriptionRaw' => 'DescriptionRaw',
                'status'         => CardStatus::ARCHIVED,
                'type'           => CardType::TASK,
                'column'         => 2,
                'order'          => 3,
                'ref'            => 0,
                'comments'       => null,
                'media'          => [],
            ],
            $serialized
        );
    }
}
