<?php

namespace App\Cards;

class Cards
{
    protected ?string $cardValue;
    protected ?string $suit;
    protected ?string $color;
    protected ?string $name;

    public function __construct()
    {
        $this->cardValue = null;
        $this->suit = null;
        $this->color = null;
        $this->name = null;
    }

    public function setCard(string $value, string $suit): void
    {
        $this->cardValue = $value;
        $this->suit = $suit;
        $this->setColor();
        $this->setName();
    }

    public function setColor(): void
    {
        switch ($this->suit) {
            case 'Hearts':
            case 'Diamonds':
                $this->color = "card red";
                break;
            case 'Clubs':
            case 'Spades':
                $this->color = "card black";
                break;
        }
    }

    public function setName(): void
    {
        switch ($this->cardValue) {
            case 1:
                $this->name = "Ace of " . $this->suit;
                break;
            case 11:
                $this->name = "Jack of " . $this->suit;
                break;
            case 12:
                $this->name = "Queen of " . $this->suit;
                break;
            case 13:
                $this->name = "King of " . $this->suit;
                break;
            default:
                $this->name = $this->cardValue . " of " . $this->suit;
        }
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getCardValue(): ?string
    {
        return $this->cardValue;
    }

    public function getAsString(): string
    {
        return "[{$this->cardValue}]";
    }
}
