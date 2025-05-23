<?php

namespace App\Cards;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for CardGraphic class which extends Cards class.
 */
class CardGraphicTest extends TestCase
{
    /**
     * Constructs a cardGraphic object and verify the values are null
     */
    public function testCreateCardGraphicObject()
    {
        $card = new CardGraphic();
        $this->assertInstanceOf("App\Cards\Cards", $card);

        $color = $card->getColor();
        $name = $card->getName();
        $value = $card->getCardValue();

        $this->assertEquals($color, null);
        $this->assertEquals($name, null);
        $this->assertEquals($value, null);
    }
    /**
     * Constructs a cardGraphic object,
     * set card to 7 of spades,
     * verify the values
     */
    public function testCreateCardGraphicObject7OfSpades()
    {
        $card = new CardGraphic();
        $this->assertInstanceOf("App\Cards\Cards", $card);

        $card->setCard('7', 'Spades');

        $color = $card->getColor();
        $name = $card->getName();
        $value = $card->getCardValue();
        $unicode = $card->getUnicode();

        $this->assertEquals($color, "card black");
        $this->assertEquals($name, "7 of Spades");
        $this->assertEquals($value, "7");
        $this->assertEquals($unicode, "127143");
    }
}