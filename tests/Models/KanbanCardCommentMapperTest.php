<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\Kanban\tests\Models;

use Modules\Admin\Models\NullAccount;
use Modules\Kanban\Models\KanbanCardComment;
use Modules\Kanban\Models\KanbanCardCommentMapper;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\Modules\Kanban\Models\KanbanCardCommentMapper::class)]
final class KanbanCardCommentMapperTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\DependsExternal('\Modules\Kanban\tests\Models\KanbanCardMapperTest', 'testCRUD')]
    #[\PHPUnit\Framework\Attributes\Group('module')]
    public function testCRUD() : void
    {
        $comment = new KanbanCardComment();

        $comment->description = 'This is some card description';
        $comment->card        = 1;
        $comment->createdBy   = new NullAccount(1);

        $id = KanbanCardCommentMapper::create()->execute($comment);
        self::assertGreaterThan(0, $comment->id);
        self::assertEquals($id, $comment->id);

        $commentR = KanbanCardCommentMapper::get()->where('id', $comment->id)->execute();
        self::assertEquals($comment->description, $commentR->description);
        self::assertEquals($comment->card, $commentR->card);
        self::assertEquals($comment->createdBy->id, $commentR->createdBy->id);
        self::assertEquals($comment->createdAt->format('Y-m-d'), $commentR->createdAt->format('Y-m-d'));
    }
}
