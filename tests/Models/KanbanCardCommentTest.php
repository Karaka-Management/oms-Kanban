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
use Modules\Kanban\Models\KanbanCardComment;

/**
 * @internal
 */
class KanbanCardCommentTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Modules\Kanban\Models\KanbanCardComment
     * @group module
     */
    public function testDefault() : void
    {
        $comment = new KanbanCardComment();

        self::assertEquals(0, $comment->getId());
        self::assertEquals(0, $comment->getCard());
        self::assertEquals('', $comment->description);
        self::assertEquals(0, $comment->createdBy->getId());
        self::assertInstanceOf('\DateTimeImmutable', $comment->createdAt);
        self::assertEquals([], $comment->getMedia());
    }

    /**
     * @covers Modules\Kanban\Models\KanbanCardComment
     * @group module
     */
    public function testSetGet() : void
    {
        $comment = new KanbanCardComment();

        $comment->setCard(2);
        $comment->description = 'Description';
        $comment->createdBy   = new NullAccount(1);
        $comment->addMedia(3);

        self::assertEquals(2, $comment->getCard());
        self::assertEquals('Description', $comment->description);
        self::assertEquals(1, $comment->createdBy->getId());
        self::assertEquals([3], $comment->getMedia());
    }
}
