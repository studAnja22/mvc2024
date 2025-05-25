<?php

namespace App\Cards;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for Card class
 */
class CardsTest extends TestCase
{
    /**
     * Constructs a card object and verify:
     * That we have property 'color', 'suit', 'name' and 'cardValue' and
     * that these properties have the value null.
     */
    public function testCreateCardObject()
    {
        $card = new Cards();
        $this->assertInstanceOf("App\Cards\Cards", $card);

        $color = $card->getColor();
        $name = $card->getName();
        $value = $card->getCardValue();
        $stringValue = $card->getAsString();

        $this->assertObjectHasProperty('color', $card);
        $this->assertObjectHasProperty('suit', $card);
        $this->assertObjectHasProperty('name', $card);
        $this->assertObjectHasProperty('cardValue', $card);
        $this->assertEquals($color, null);
        $this->assertEquals($name, null);
        $this->assertEquals($value, null);
        $this->assertEquals($stringValue, "[]");
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
        $stringValue = $card->getAsString();

        $this->assertEquals($color, "card black");
        $this->assertEquals($name, "7 of Spades");
        $this->assertEquals($value, "7");
        $this->assertEquals($stringValue, "[7]");
    }
}