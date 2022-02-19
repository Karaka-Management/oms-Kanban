<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace Modules\Kanban\tests\Models;

use Modules\Admin\Models\NullAccount;
use Modules\Kanban\Models\CardStatus;
use Modules\Kanban\Models\CardType;
use Modules\Kanban\Models\KanbanCard;
use Modules\Kanban\Models\KanbanCardMapper;
use Modules\Tag\Models\Tag;

/**
 * @internal
 */
final class KanbanCardMapperTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @depends Modules\Kanban\tests\Models\KanbanCardMapperTest::testCRUD
     * @covers Modules\Kanban\Models\KanbanCardMapper
     * @group module
     */
    public function testCRUD() : void
    {
        $card = new KanbanCard();

        $card->name        = 'Some Card name';
        $card->description = 'This is some card description';
        $card->setStatus(CardStatus::ACTIVE);
        $card->setType(CardType::TEXT);
        $card->order     = 1;
        $card->column    = 1;
        $card->createdBy = new NullAccount(1);
        $card->addTag(new Tag());
        $card->addTag(new Tag());

        $id = KanbanCardMapper::create()->execute($card);
        self::assertGreaterThan(0, $card->getId());
        self::assertEquals($id, $card->getId());

        $cardR = KanbanCardMapper::get()->where('id', $card->getId())->execute();
        self::assertEquals($card->name, $cardR->name);
        self::assertEquals($card->description, $cardR->description);
        self::assertEquals($card->column, $cardR->column);
        self::assertEquals($card->order, $cardR->order);
        self::assertEquals($card->getStatus(), $cardR->getStatus());
        self::assertEquals($card->getType(), $cardR->getType());
        self::assertEquals($card->createdBy->getId(), $cardR->createdBy->getId());
        self::assertEquals($card->createdAt->format('Y-m-d'), $cardR->createdAt->format('Y-m-d'));
        self::assertEquals($card->ref, $cardR->ref);
    }

    /**
     * @covers Modules\Kanban\Models\KanbanCardMapper
     * @group module
     */
    public function testTaskCard() : void
    {
        $card = new KanbanCard();

        $card->setStatus(CardStatus::ACTIVE);
        $card->setType(CardType::TASK);
        $card->ref       = 1;
        $card->order     = 1;
        $card->column    = 1;
        $card->createdBy = new NullAccount(1);
        $card->addTag(new Tag());
        $card->addTag(new Tag());

        $id = KanbanCardMapper::create()->execute($card);
        self::assertGreaterThan(0, $card->getId());
        self::assertEquals($id, $card->getId());
    }
}
