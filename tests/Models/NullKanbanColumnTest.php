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

use Modules\Kanban\Models\NullKanbanColumn;

/**
 * @internal
 */
final class NullKanbanColumnTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Modules\Kanban\Models\NullKanbanColumn
     * @group framework
     */
    public function testNull() : void
    {
        self::assertInstanceOf('\Modules\Kanban\Models\KanbanColumn', new NullKanbanColumn());
    }

    /**
     * @covers Modules\Kanban\Models\NullKanbanColumn
     * @group framework
     */
    public function testId() : void
    {
        $null = new NullKanbanColumn(2);
        self::assertEquals(2, $null->getId());
    }
}