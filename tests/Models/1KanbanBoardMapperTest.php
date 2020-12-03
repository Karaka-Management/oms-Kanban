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
use Modules\Kanban\Models\KanbanBoard;
use Modules\Kanban\Models\KanbanBoardMapper;
use phpOMS\Utils\RnG\Text;

/**
 * @internal
 */
class KanbanBoardMapperTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Modules\Kanban\Models\KanbanBoardMapper
     * @group module
     */
    public function testCRUD() : void
    {
        $board = new KanbanBoard();

        $board->name        = 'Test Board 0';
        $board->description = 'This is some description';
        $board->createdBy   = new NullAccount(1);

        $id = KanbanBoardMapper::create($board);
        self::assertGreaterThan(0, $board->getId());
        self::assertEquals($id, $board->getId());

        $boardR = KanbanBoardMapper::get($board->getId());
        self::assertEquals($board->name, $boardR->name);
        self::assertEquals($board->getStatus(), $boardR->getStatus());
        self::assertEquals($board->description, $boardR->description);
        self::assertEquals($board->createdBy->getId(), $boardR->createdBy->getId());
        self::assertEquals($board->createdAt->format('Y-m-d'), $boardR->createdAt->format('Y-m-d'));
        self::assertEquals($board->getColumns(), $boardR->getColumns());
    }

    /**
     * @group volume
     * @group module
     * @coversNothing
     */
    public function testVolume() : void
    {
        for ($i = 1; $i < 30; ++$i) {
            $text  = new Text();
            $board = new KanbanBoard();

            $board->name        = $text->generateText(\mt_rand(3, 7));
            $board->description = $text->generateText(\mt_rand(20, 70));
            $board->createdBy   = new NullAccount(1);

            $id = KanbanBoardMapper::create($board);
        }
    }
}
