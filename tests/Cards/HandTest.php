<?php

namespace App\Cards;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for Hand class
 * 
 * The Hand holds a deck of card, which contains card objects.
 * Hand can shuffle the deck, draw the top card, shuffle the deck, 
 * draw cards by index and look at all the cards in the deck and draw pile.
 */
class HandTest extends TestCase
{
    /**
     * Tests if we can create a hand object with properties 'drawn' and 'deck'
     */
    public function testCreateHandObject()
    {
        $hand = new Hand();
        $this->assertInstanceOf("App\Cards\Hand", $hand);

        $this->assertObjectHasProperty('drawn', $hand);
        $this->assertObjectHasProperty('deck', $hand);
    }
    /**
     * Tests if we have a deck of 52 cards and our drawn pile is 0.
     * 
     * We check that we have 52 cards and that we get 52 cards when we shuffle the deck.
     * We also check if it only contains objects (with a method that will be deprecated with phpunit 13).
     * 
     * Methods checked:
     * getDeck(), shuffle(), checkDrawn()
     */
    public function testDeckHas52CardsDrawnHas0cards()
    {
        $hand = new Hand();
        //52 cards in the deck, only objects.
        $this->assertCount(52, $hand->getDeck());
        //assertContainsOnly() will be deprecated and method removed in PHPUnit 13.
        $this->assertContainsOnlyObject($hand->getDeck());

        //No drawn cards yet.
        $this->assertEquals(0, $hand->checkDrawn());

        //Shuffle deck returns 52 cards
        $this->assertCount(52, $hand->shuffle());
        //assertContainsOnly() will be deprecated and method removed in PHPUnit 13.
        $this->assertContainsOnlyObject($hand->shuffle());
    }
    /**
     * We draw cards from the deck and checks the properties drawn and deck.
     * 
     * Testing methods:
     * howManyLeft(), checkDrawn(),
     * drawTopCard(), removeTopCard(), drawAndDiscard(), getAllDrawn(),
     * drawIndex(), getDrawnByIndex()
     */
    public function testDrawingCards()
    {
        $hand = new Hand();
        /**
         * No cards drawn yet. 
         * Deck should have 52 cards and drawn pile 0.
         */
        $this->assertEquals(52, $hand->howManyLeft());
        $this->assertEquals(0, $hand->checkDrawn());

        /**
         * We draw the top card and place it in drawn pile (property 'drawn' +1),
         * then we remove it from the deck (property 'deck' -1)
         * drawAndDiscard() combines drawTopCard and removeTopCard (property 'drawn' +1, property 'deck' -1)
         */
        $hand->drawTopCard();
        $this->assertEquals(1, $hand->checkDrawn());

        $hand->removeTopCard();
        $this->assertEquals(51, $hand->howManyLeft());

        $hand->drawAndDiscard();
        $this->assertEquals(2, $hand->checkDrawn());
        $this->assertEquals(50, $hand->howManyLeft());

        /**
         * We look at the drawn cards and expect to see
         * Ace Of Hearts and 2 of Hearts in there.
         * We check for Ace of hearts.
         */
        $drawnCards = $hand->getAllDrawn();
        $this->assertCount(2, $drawnCards);
        $firstCard = $hand->getDrawnByIndex(0);

        $color = $firstCard[0]->getColor();
        $this->assertObjectHasProperty('color', $firstCard[0]);
        $this->assertEquals($color, "card red");

        $name = $firstCard[0]->getName();
        $this->assertEquals($name, "Ace of Hearts");

        $value = $firstCard[0]->getCardValue();
        $this->assertEquals($value, "1");

        $stringValue = $firstCard[0]->getAsString();
        $this->assertEquals($stringValue, "[1]");

        /**
         * We now have 50 cards in our deck.
         * We draw the card with index 17, which should be 7 of Diamonds, lets check.
         */
        $indexDraw = $hand->drawIndex(17);
        $this->assertInstanceOf("App\Cards\Cards", $indexDraw);
        $indexName = $indexDraw->getName();
        $this->assertEquals($indexName, "7 of Diamonds");
    }
}