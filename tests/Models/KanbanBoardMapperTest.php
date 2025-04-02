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
use Modules\Kanban\Models\KanbanBoard;
use Modules\Kanban\Models\KanbanBoardMapper;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\Modules\Kanban\Models\KanbanBoardMapper::class)]
final class KanbanBoardMapperTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('module')]
    public function testCRUD() : void
    {
        $board = new KanbanBoard();

        $board->name        = 'Test Board 0';
        $board->description = 'This is some description';
        $board->createdBy   = new NullAccount(1);

        $id = KanbanBoardMapper::create()->execute($board);
        self::assertGreaterThan(0, $board->id);
        self::assertEquals($id, $board->id);

        $boardR = KanbanBoardMapper::get()->where('id', $board->id)->execute();
        self::assertEquals($board->name, $boardR->name);
        self::assertEquals($board->status, $boardR->status);
        self::assertEquals($board->description, $boardR->description);
        self::assertEquals($board->createdBy->id, $boardR->createdBy->id);
        self::assertEquals($board->createdAt->format('Y-m-d'), $boardR->createdAt->format('Y-m-d'));
        self::assertEquals($board->getColumns(), $boardR->getColumns());
    }
}
