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

use Modules\Kanban\Models\NullKanbanCard;

/**
 * @internal
 */
final class NullKanbanCardTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Modules\Kanban\Models\NullKanbanCard
     * @group framework
     */
    public function testNull() : void
    {
        self::assertInstanceOf('\Modules\Kanban\Models\KanbanCard', new NullKanbanCard());
    }

    /**
     * @covers Modules\Kanban\Models\NullKanbanCard
     * @group framework
     */
    public function testId() : void
    {
        $null = new NullKanbanCard(2);
        self::assertEquals(2, $null->id);
    }
}
