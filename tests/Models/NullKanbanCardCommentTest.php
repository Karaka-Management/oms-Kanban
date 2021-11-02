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

use Modules\Kanban\Models\NullKanbanCardComment;

/**
 * @internal
 */
final class NullKanbanCardCommentTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Modules\Kanban\Models\NullKanbanCardComment
     * @group framework
     */
    public function testNull() : void
    {
        self::assertInstanceOf('\Modules\Kanban\Models\KanbanCardComment', new NullKanbanCardComment());
    }

    /**
     * @covers Modules\Kanban\Models\NullKanbanCardComment
     * @group framework
     */
    public function testId() : void
    {
        $null = new NullKanbanCardComment(2);
        self::assertEquals(2, $null->getId());
    }
}
