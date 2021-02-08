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
use Modules\Kanban\Models\KanbanCardMapper;
use Modules\Tag\Models\NullTag;
use Modules\Tag\Models\Tag;
use phpOMS\Utils\RnG\Text;

/**
 * @internal
 */
class KanbanCardMapperTest extends \PHPUnit\Framework\TestCase
{
    /**
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
        $card->setOrder(1);
        $card->setColumn(1);
        $card->createdBy = new NullAccount(1);
        $card->addTag(new Tag());
        $card->addTag(new Tag());

        $id = KanbanCardMapper::create($card);
        self::assertGreaterThan(0, $card->getId());
        self::assertEquals($id, $card->getId());

        $cardR = KanbanCardMapper::get($card->getId());
        self::assertEquals($card->name, $cardR->name);
        self::assertEquals($card->description, $cardR->description);
        self::assertEquals($card->getColumn(), $cardR->getColumn());
        self::assertEquals($card->getOrder(), $cardR->getOrder());
        self::assertEquals($card->getStatus(), $cardR->getStatus());
        self::assertEquals($card->getType(), $cardR->getType());
        self::assertEquals($card->createdBy->getId(), $cardR->createdBy->getId());
        self::assertEquals($card->createdAt->format('Y-m-d'), $cardR->createdAt->format('Y-m-d'));
        self::assertEquals($card->getRef(), $cardR->getRef());
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
        $card->setRef(1);
        $card->setOrder(1);
        $card->setColumn(1);
        $card->createdBy = new NullAccount(1);
        $card->addTag(new Tag());
        $card->addTag(new Tag());

        $id = KanbanCardMapper::create($card);
        self::assertGreaterThan(0, $card->getId());
        self::assertEquals($id, $card->getId());
    }

    /**
     * @group volume
     * @group module
     * @coversNothing
     */
    public function testVolume() : void
    {
        for ($i = 1; $i < 10; ++$i) {
            $text = new Text();
            $card = new KanbanCard();

            $card->name        = $text->generateText(\mt_rand(3, 7));
            $card->description = $text->generateText(\mt_rand(20, 100));
            $card->setStatus(CardStatus::ACTIVE);
            $card->setType(CardType::TEXT);
            $card->setOrder(\mt_rand(1, 10));
            $card->setColumn(\mt_rand(1, 4));
            $card->createdBy = new NullAccount(1);
            $card->addTag(new NullTag(1));
            $card->addTag(new NullTag(2));

            $id = KanbanCardMapper::create($card);
        }
    }
}
