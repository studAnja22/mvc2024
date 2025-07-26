<?php

namespace App\Tests\Helpers;

use App\Cards\Cards;
use App\Cards\Games;
use App\Cards\Hand;

/**
 * Creates dummy players for the game testing.
 */
class DummyPlayerMaker
{
    /**
     * @param int $numberOfCards number of cards to be drawn from a new deck.
     * @return Cards[]
     */
    public function createDummy(int $numberOfCards): array
    {
        $cards = $this->drawDummyCards($numberOfCards);
        return $cards;
    }

    /**
     * Function draws cards from a new deck. Suit: Hearts, Order: Ace, 2, 3...
     * @param int $numberOfDrawnCards - number of cards to be drawn from a new deck.
     * @return Cards[]
    */
    public function drawDummyCards(int $numberOfDrawnCards): array
    {
        $hand = new Hand();
        $cards = [];

        for ($i = 0; $i < $numberOfDrawnCards; $i++) {
            $card = $hand->drawAndDiscard();
            if ($card != false) {
                array_push($cards, $card);
            }
        }
        return $cards;
    }
}
