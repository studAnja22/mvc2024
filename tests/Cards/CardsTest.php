<?php

namespace App\Cards;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Cards
 */
class CardsTest extends TestCase
{
    /**
     * Constructs a card object and verify the values are null
     */
    public function testCreateCardObject()
    {
        $card = new Cards();
        $this->assertInstanceOf("App\Cards\Cards", $card);

        $color = $card->getColor();
        $name = $card->getName();
        $value = $card->getCardValue();

        $this->assertEquals($color, null);
        $this->assertEquals($name, null);
        $this->assertEquals($value, null);
    }
    /**
     * Constructs a card object,
     * set card to 7 of spades,
     * verify the values
     */
    public function testCreateCardObject7OfSpades()
    {
        $card = new Cards();
        $this->assertInstanceOf("App\Cards\Cards", $card);

        $card->setCard('7', 'Spades');

        $color = $card->getColor();
        $name = $card->getName();
        $value = $card->getCardValue();

        $this->assertEquals($color, "card black");
        $this->assertEquals($name, "7 of Spades");
        $this->assertEquals($value, "7");
    }
}