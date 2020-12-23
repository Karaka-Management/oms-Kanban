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
use Modules\Kanban\Models\CardStatus;
use Modules\Kanban\Models\CardType;
use Modules\Kanban\Models\KanbanCard;
use Modules\Media\Models\NullMedia;

/**
 * @internal
 */
class KanbanCardTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Modules\Kanban\Models\KanbanCard
     * @group module
     */
    public function testDefault() : void
    {
        $card = new KanbanCard();

        self::assertEquals(0, $card->getId());
        self::assertEquals(CardStatus::ACTIVE, $card->getStatus());
        self::assertEquals(CardType::TEXT, $card->getType());
        self::assertEquals('', $card->name);
        self::assertEquals('', $card->description);
        self::assertEquals(0, $card->getColumn());
        self::assertEquals(0, $card->getOrder());
        self::assertEquals(0, $card->createdBy->getId());
        self::assertInstanceOf('\DateTimeImmutable', $card->createdAt);
        self::assertEquals([], $card->getComments());
        self::assertEquals([], $card->getTags());
        self::assertEquals([], $card->getMedia());
    }

    /**
     * @covers Modules\Kanban\Models\KanbanCard
     * @group module
     */
    public function testSetGet() : void
    {
        $card = new KanbanCard();
        $card->setStatus(CardStatus::ARCHIVED);
        $card->setType(CardType::TASK);
        $card->name        = 'Name';
        $card->description = 'Description';
        $card->setColumn(1);
        $card->setOrder(2);
        $card->createdBy = new NullAccount(1);
        $card->addComment(5);
        $card->addMedia($m = new NullMedia(7));

        self::assertEquals(CardStatus::ARCHIVED, $card->getStatus());
        self::assertEquals(CardType::TASK, $card->getType());
        self::assertEquals('Name', $card->name);
        self::assertEquals('Description', $card->description);
        self::assertEquals(1, $card->getColumn());
        self::assertEquals(2, $card->getOrder());
        self::assertEquals(1, $card->createdBy->getId());
        self::assertEquals([5], $card->getComments());
        self::assertEquals([$m], $card->getMedia());
    }
}
