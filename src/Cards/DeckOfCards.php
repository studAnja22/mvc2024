<?php

namespace App\Cards;

use App\Cards\CardGraphic;

class DeckOfCards
{
    /**
     * @var string[] An array of 4 suits.
     */
    private array $allSuits;

    /**
     * @var string[] An array of values 1-13.
     */
    private array $allValues;

    /**
     * @var CardGraphic[] An array of CardGraphic objects.
     */
    public array $deck;

    public function __construct()
    {
        $this->allSuits = [
            'Hearts',
            'Diamonds',
            'Clubs',
            'Spades',
        ];
        $this->allValues = [
            '1',
            '2',
            '3',
            '4',
            '5',
            '6',
            '7',
            '8',
            '9',
            '10',
            '11',
            '12',
            '13',
        ];
        $this->deck = [];
        $this->buildDeck();
    }
    /**
     * Create new cards and add them to the deck.
     */
    public function buildDeck(): void
    {
        foreach ($this->allSuits as $suit) {
            foreach ($this->allValues as $value) {
                $newCard = new CardGraphic();
                $newCard->setCard($value, $suit);
                array_push($this->deck, $newCard);
            }
        }
    }
    /**
     * @return CardGraphic[] An array of CardGraphic objects.
     */
    public function getDeck(): ?array
    {
        return $this->deck;
    }
    /**
     * @return CardGraphic[] An array of shuffled CardGraphic objects.
     */
    public function shuffleDeck(): ?array
    {
        shuffle($this->deck);
        return $this->deck;
    }

}
