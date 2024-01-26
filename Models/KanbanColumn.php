<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   Modules\Kanban\Models
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\Kanban\Models;

/**
 * Kanban column class.
 *
 * @package Modules\Kanban\Models
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class KanbanColumn implements \JsonSerializable
{
    /**
     * ID.
     *
     * @var int
     * @since 1.0.0
     */
    public int $id = 0;

    /**
     * Name.
     *
     * @var string
     * @since 1.0.0
     */
    public string $name = '';

    /**
     * Column order.
     *
     * @var int
     * @since 1.0.0
     */
    public int $order = 0;

    /**
     * Board.
     *
     * @var int
     * @since 1.0.0
     */
    public int $board = 0;

    /**
     * Cards.
     *
     * @var array
     * @since 1.0.0
     */
    public array $cards = [];

    /**
     * Get the cards
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getCards() : array
    {
        return $this->cards;
    }

    /**
     * Add a card
     *
     * @param KanbanCard $card Card
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function addCard(KanbanCard $card) : void
    {
        $this->cards[] = $card;
    }

    /**
     * Remove a card
     *
     * @param int $id Card to remove
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function removeCard(int $id) : bool
    {
        if (isset($this->cards[$id])) {
            unset($this->cards[$id]);

            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray() : array
    {
        return [
            'id'    => $this->id,
            'name'  => $this->name,
            'order' => $this->order,
            'board' => $this->board,
            'cards' => $this->cards,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize() : mixed
    {
        return $this->toArray();
    }
}
