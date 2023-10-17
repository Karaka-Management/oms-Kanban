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

use Modules\Kanban\Models\NullKanbanCardComment;

/**
 * @internal
 */
final class NullKanbanCardCommentTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Modules\Kanban\Models\NullKanbanCardComment
     * @group module
     */
    public function testNull() : void
    {
        self::assertInstanceOf('\Modules\Kanban\Models\KanbanCardComment', new NullKanbanCardComment());
    }

    /**
     * @covers Modules\Kanban\Models\NullKanbanCardComment
     * @group module
     */
    public function testId() : void
    {
        $null = new NullKanbanCardComment(2);
        self::assertEquals(2, $null->id);
    }

    /**
     * @covers Modules\Kanban\Models\NullKanbanCardComment
     * @group module
     */
    public function testJsonSerialize() : void
    {
        $null = new NullKanbanCardComment(2);
        self::assertEquals(['id' => 2], $null->jsonSerialize());
    }
}
