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
use Modules\Kanban\Models\CardStatus;
use Modules\Kanban\Models\CardType;
use Modules\Kanban\Models\KanbanCard;
use Modules\Media\Models\NullMedia;
use Modules\Tasks\Models\Task;
use Modules\Tag\Models\Tag;

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
     * @covers Modules\Kanban\Models\KanbanCard
     * @group module
     */
    public function testDefault() : void
    {
        self::assertEquals(0, $this->card->getId());
        self::assertEquals(CardStatus::ACTIVE, $this->card->getStatus());
        self::assertEquals(CardType::TEXT, $this->card->getType());
        self::assertEquals('', $this->card->name);
        self::assertEquals('', $this->card->description);
        self::assertEquals(0, $this->card->column);
        self::assertEquals(0, $this->card->order);
        self::assertEquals(0, $this->card->createdBy->getId());
        self::assertInstanceOf('\DateTimeImmutable', $this->card->createdAt);
        self::assertEquals([], $this->card->getComments());
        self::assertEquals([], $this->card->getTags());
        self::assertEquals([], $this->card->getMedia());
    }

    /**
     * @covers Modules\Kanban\Models\KanbanCard
     * @group module
     */
    public function testStatusInputOutput() : void
    {
        $this->card->setStatus(CardStatus::ARCHIVED);
        self::assertEquals(CardStatus::ARCHIVED, $this->card->getStatus());
    }

    /**
     * @covers Modules\Kanban\Models\KanbanCard
     * @group module
     */
    public function testTypeInputOutput() : void
    {
        $this->card->setType(CardType::TASK);
        self::assertEquals(CardType::TASK, $this->card->getType());
    }

    /**
     * @covers Modules\Kanban\Models\KanbanCard
     * @group module
     */
    public function testColumnInputOutput() : void
    {
        $this->card->column = 1;
        self::assertEquals(1, $this->card->column);
    }

    /**
     * @covers Modules\Kanban\Models\KanbanCard
     * @group module
     */
    public function testMediaInputOutput() : void
    {
        $this->card->addMedia($m = new NullMedia(7));
        self::assertCount(1, $this->card->getMedia());
    }

    /**
     * @covers Modules\Kanban\Models\KanbanCard
     * @group module
     */
    public function testCommentInputOutput() : void
    {
        $this->card->addComment(5);
        self::assertEquals([5], $this->card->getComments());
        self::assertEquals(1, $this->card->getCommentCount());
    }

    /**
     * @covers Modules\Kanban\Models\KanbanCard
     * @group module
     */
    public function testTagInputOutput() : void
    {
        $tag = new Tag();
        $tag->setL11n('Tag');

        $this->card->addTag($tag);
        self::assertEquals($tag, $this->card->getTag(0));
        self::assertCount(1, $this->card->getTags());
    }

    /**
     * @covers Modules\Kanban\Models\KanbanCard
     * @group module
     */
    public function testCommentRemove() : void
    {
        $this->card->addComment(5);
        self::assertCount(1, $this->card->getComments());
        self::assertTrue($this->card->removeComment(0));
        self::assertCount(0, $this->card->getComments());
        self::assertFalse($this->card->removeComment(0));
    }

    /**
     * @covers Modules\Kanban\Models\KanbanCard
     * @group module
     */
    public function testCreateFromTask() : void
    {
        self::assertInstanceOf('\Modules\Kanban\Models\KanbanCard', $this->card->createFromTask(new Task()));
    }

    /**
     * @covers Modules\Kanban\Models\KanbanCard
     * @group module
     */
    public function testSerialize() : void
    {
        $this->card->name = 'Title';
        $this->card->description = 'Description';
        $this->card->descriptionRaw = 'DescriptionRaw';
        $this->card->order = 3;
        $this->card->column = 2;
        $this->card->setStatus(CardStatus::ARCHIVED);
        $this->card->setType(CardType::TASK);

        $serialized = $this->card->jsonSerialize();
        unset($serialized['createdBy']);
        unset($serialized['createdAt']);

        self::assertEquals(
            [
                'id'       => 0,
                'title'       => 'Title',
                'description' => 'Description',
                'descriptionRaw' => 'DescriptionRaw',
                'status'      => CardStatus::ARCHIVED,
                'type'        => CardType::TASK,
                'column'      => 2,
                'order'       => 3,
                'ref'         => 0,
                'comments'    => [],
                'media'       => [],
            ],
            $serialized
        );
    }
}
