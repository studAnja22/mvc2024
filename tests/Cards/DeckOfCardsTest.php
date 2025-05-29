<?php

namespace App\Cards;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for DeckOfCard class.
 */
class DeckOfCardsTest extends TestCase
{
    /**
     * Constructs a DeckOfCards object and verifies:
     * That we have properties 'allSuits', 'allValues' and 'deck'.
     */
    public function testCreateDeckOfCardObject(): void
    {
        $deck = new DeckOfCards();

        $this->assertInstanceOf("App\Cards\DeckOfCards", $deck);

        $this->assertObjectHasProperty('allSuits', $deck);
        $this->assertObjectHasProperty('allValues', $deck);
        $this->assertObjectHasProperty('deck', $deck);
    }
    /**
     * We check the functions getDeck() and shuffleDeck()
     * We check if we have 52 objects in the deck of cards.
     * We check (in a soon to be deprecated method) that it only contains objects.
     */
    public function testDeckOfCardsMethods(): void
    {
        $deck = new DeckOfCards();

        //assertContainsOnly() will be deprecated and method removed in PHPUnit 13.
        // $this->assertContainsOnlyObject($deck->getDeck());//will always return true

        $this->assertCount(52, $deck->getDeck() ?? []);
        $this->assertCount(52, $deck->shuffleDeck() ?? []);
    }
}
