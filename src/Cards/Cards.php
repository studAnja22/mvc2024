<?php

namespace App\Cards;

class Cards
{
    protected $cardValue;
    protected $suit;
    protected $color;
    protected $name;

    public function __construct()
    {
        $this->cardValue = null;
        $this->suit = null;
        $this->color = null;
        $this->name = null;
    }

    public function setCard($cardValue, $suit)
    {
        $this->cardValue = $cardValue;
        $this->suit = $suit;
        $this->setColor();
        $this->setName();
    }

    public function setColor()
    {
        if ($this->suit == 'Hearts' or $this->suit == 'Diamonds') {
            $this->color = "card red";
        } else {
            $this->color = "card black";
        };
    }

    public function setName()
    {
        if ($this->cardValue == 1 or $this->cardValue > 10) {
            if ($this->cardValue == 1) {
                $this->name = "Ace of " . $this->suit;
            } elseif ($this->cardValue == 11) {
                $this->name = "Jack of " . $this->suit;
            } elseif ($this->cardValue == 12) {
                $this->name = "Queen of " . $this->suit;
            } elseif ($this->cardValue == 13) {
                $this->name = "King of " . $this->suit;
            }
        } else {
            $this->name = $this->cardValue . " of " . $this->suit;
        }
    }

    public function getColor()
    {
        return $this->color;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getCardValue(): int
    {
        return $this->cardValue;
    }

    public function getAsString(): string
    {
        return "[{$this->cardValue}]";
    }
}
