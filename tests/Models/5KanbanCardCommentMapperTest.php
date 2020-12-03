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
use Modules\Kanban\Models\KanbanCardComment;
use Modules\Kanban\Models\KanbanCardCommentMapper;
use phpOMS\Utils\RnG\Text;

/**
 * @internal
 */
class KanbanCardCommentMapperTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Modules\Kanban\Models\KanbanCardCommentMapper
     * @group module
     */
    public function testCRUD() : void
    {
        $comment = new KanbanCardComment();

        $comment->description = 'This is some card description';
        $comment->setCard(1);
        $comment->createdBy = new NullAccount(1);

        $id = KanbanCardCommentMapper::create($comment);
        self::assertGreaterThan(0, $comment->getId());
        self::assertEquals($id, $comment->getId());

        $commentR = KanbanCardCommentMapper::get($comment->getId());
        self::assertEquals($comment->description, $commentR->description);
        self::assertEquals($comment->getCard(), $commentR->getCard());
        self::assertEquals($comment->createdBy->getId(), $commentR->createdBy->getId());
        self::assertEquals($comment->createdAt->format('Y-m-d'), $commentR->createdAt->format('Y-m-d'));
    }

    /**
     * @group volume
     * @group module
     * @coversNothing
     */
    public function testVolume() : void
    {
        for ($i = 1; $i < 10; ++$i) {
            $text    = new Text();
            $comment = new KanbanCardComment();

            $comment->description = $text->generateText(\mt_rand(20, 100));
            $comment->setCard(1);
            $comment->createdBy = new NullAccount(1);

            $id = KanbanCardCommentMapper::create($comment);
        }
    }
}
