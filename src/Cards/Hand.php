<?php

namespace App\Cards;

use App\Cards\CardGraphic;
use App\Cards\DeckOfCards;

class Hand
{
    /** @var array<int, CardGraphic> */
    private array $drawn;

    /** @var array<int, CardGraphic> */
    public array $deck;

    public function __construct()
    {
        $deck = new DeckOfCards();
        $this->deck = $deck->getDeck() ?? [];
        $this->drawn = [];
    }

    /**
     * Shuffles the deck of cards.
     *
     * @return array<int, CardGraphic>
     */
    public function shuffle()
    {
        shuffle($this->deck);
        return $this->deck;
    }

    /**
     * Draws a card by index.
     *
     * @param int $index
     * @return CardGraphic
     */
    public function drawIndex($index)
    {
        return $this->deck[$index];
    }

    /**
     * Draws the top card and discards it.
     *
     * @return CardGraphic
     */
    public function drawAndDiscard(): CardGraphic|false
    {
        array_push($this->drawn, current($this->deck));
        array_shift($this->deck);
        return end($this->drawn);
    }
    /**
     * Draws the top card without discarding it.
     *
     * @return CardGraphic
     */
    public function drawTopCard(): CardGraphic|false
    {
        array_push($this->drawn, current($this->deck));
        return end($this->drawn);
    }

    /**
     * Discards the top card.
     *
     * @return void
     */
    public function removeTopCard(): void
    {
        array_shift($this->deck);
    }

    /**
     * Returns the number of drawn cards.
     *
     * @return int
     */
    public function checkDrawn(): int
    {
        return count($this->drawn);
    }

    /**
     * Returns the last drawn cards by index.
     *
     * @param int $index
     * @return array<int, CardGraphic>
     */
    public function getDrawnByIndex(int $index): array
    {
        $lastCards = array_slice($this->drawn, -$index);
        return $lastCards;
    }

    /**
     * Returns all drawn cards.
     *
     * @return array<int, CardGraphic>
     */
    public function getAllDrawn(): array
    {
        return $this->drawn;
    }

    /**
     * Returns the number of cards left in the deck.
     *
     * @return int
     */
    public function howManyLeft(): int
    {
        return count($this->deck);
    }

    /**
     * Returns the current deck.
     *
     * @return array<int, CardGraphic>
     */
    public function getDeck(): array
    {
        return $this->deck;
    }
}
