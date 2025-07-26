<?php

namespace App\SessionHandlers;

use App\Cards\Cards;
use App\Cards\Games;
use App\Cards\Hand;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class GameSessionHandler
{
    /**
     * This function initiates a new game.
     * The game gets a new shuffled Hand() object (A deck of 52 cards)
     * And the player and banks hands are reset.
     */
    public function setNewGame(SessionInterface $gameSession): void
    {
        $gameSession->clear();
        /** @var Hand $deckOfCards */
        $deckOfCards = new Hand();
        $deckOfCards->shuffle();
        $gameSession->set('21_deck', $deckOfCards);
        $gameSession->set('player', []);
        $gameSession->set('bank', []);
    }
    /**
     * Handles the player drawing a card.
     */
    public function setDrawCard(SessionInterface $gameSession): void
    {
        /** @var Hand $deckOfCards */
        $deckOfCards = $gameSession->get('21_deck');
        /** @var Cards[] $player */
        $player = $gameSession->get('player');
        $player[] = $deckOfCards->drawTopCard();
        $deckOfCards->removeTopCard();
        $gameSession->set('21_deck', $deckOfCards);
        $gameSession->set('player', $player);
    }
    /**
     * Handles the banks turn logic and sets its drawn cards to session
     */
    public function setBankPlays(SessionInterface $gameSession): void
    {
        /** @var Hand $deckOfCards */
        $deckOfCards = $gameSession->get('21_deck');
        /** @var Cards[] $bank */
        $bank = $gameSession->get('bank');
        $game = new Games();
        $continueDrawingCards = true;

        while ($continueDrawingCards) {
            /** @var Cards $card */
            $card = $deckOfCards->drawTopCard();
            $bank[] = $card;

            $deckOfCards->removeTopCard();

            $bankHand = $game->getAllCardValues($bank);
            $bankPoints = $game->game21CalculatePoints($bankHand);

            if ($bankPoints >= 17) {
                $continueDrawingCards = false;
            }
        }
        $gameSession->set('bank', $bank);
    }
}
