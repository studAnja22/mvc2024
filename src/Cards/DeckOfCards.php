<?php

namespace App\Cards;

use App\Cards\CardGraphic;

class DeckOfCards
{
    private $allSuits;
    private $allValues;
    public $deck;

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
        $this->deck = array();
        $this->buildDeck();
    }
    /**
     * Create new cards and add them to the deck.
     */
    public function buildDeck()
    {
        foreach ($this->allSuits as $suit) {
            foreach ($this->allValues as $value) {
                $newCard = new CardGraphic();
                $newCard->setCard($value, $suit);
                array_push($this->deck, $newCard);
            }
        }
    }

    public function getDeck()
    {
        return $this->deck;
    }

    public function shuffleDeck()
    {
        shuffle($this->deck);
        return $this->deck;
    }

}
