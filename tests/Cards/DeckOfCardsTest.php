<?php

namespace App\Cards;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for DeckOfCard class.
 */
class DeckOfCardsTest extends TestCase
{
    /**
     * Constructs a DeckOfCards object and verify the values are null
     */
    public function testCreateDeckOfCardObject()
    {
        $deck = new DeckOfCards();
        $this->assertInstanceOf("App\Cards\DeckOfCards", $deck);
        //CHeck if there's 52 cards in the deck.
        
        // $this->assertEquals($deck->getDeck(), null);
        // assertObjectHasProperty()
        $this->assertContainsOnlyObject($deck->getDeck());
    }
}