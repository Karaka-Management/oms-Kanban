<?php
/**
 * Karaka
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

use Modules\Kanban\Models\KanbanCardComment;
use Modules\Media\Models\NullMedia;

/**
 * @internal
 */
final class KanbanCardCommentTest extends \PHPUnit\Framework\TestCase
{
    private KanbanCardComment $comment;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->comment = new KanbanCardComment();
    }

    /**
     * @covers Modules\Kanban\Models\KanbanCardComment
     * @group module
     */
    public function testDefault() : void
    {
        self::assertEquals(0, $this->comment->id);
        self::assertEquals(0, $this->comment->card);
        self::assertEquals('', $this->comment->description);
        self::assertEquals(0, $this->comment->createdBy->id);
        self::assertInstanceOf('\DateTimeImmutable', $this->comment->createdAt);
        self::assertEquals([], $this->comment->getMedia());
    }

    /**
     * @covers Modules\Kanban\Models\KanbanCardComment
     * @group module
     */
    public function testMediaInputOutput() : void
    {
        $this->comment->addMedia($m = new NullMedia(7));
        self::assertCount(1, $this->comment->getMedia());
    }

    /**
     * @covers Modules\Kanban\Models\KanbanCardComment
     * @group module
     */
    public function testSerialize() : void
    {
        $this->comment->description    = 'Description';
        $this->comment->descriptionRaw = 'DescriptionRaw';
        $this->comment->card           = 2;

        $serialized = $this->comment->jsonSerialize();
        unset($serialized['createdBy']);
        unset($serialized['createdAt']);

        self::assertEquals(
            [
                'id'             => 0,
                'description'    => 'Description',
                'descriptionRaw' => 'DescriptionRaw',
                'card'           => 2,
                'media'          => [],
            ],
            $serialized
        );
    }
}
