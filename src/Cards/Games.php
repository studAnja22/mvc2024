<?php

namespace App\Cards;

use App\Cards\Cards;
use App\Cards\CardGraphic;
use App\Cards\DeckOfCards;
use App\Cards\Game21Data;
use App\Cards\Hand;

/**
 * The game class helps with calculating and comparing values
 * as well as checking winning conditions of a game
 * and gets the values needed to render the game page.
 */
class Games
{
    /**
     * The function takes an array of integers.
     * Checks if there's a 1 and if adding 13 would keep the sum under or equal to 21.
     * Then returns the array sum as an integer.
     * @param int[] $cards - an array with integers
     * @return int
     */
    public function game21CalculatePoints($cards): int
    {
        $gameNotInitiatedYet = count($cards) == 0;
        if ($gameNotInitiatedYet) {
            return 0;
        }

        $aceInCards = in_array(1, $cards);
        $cardsWithLowAce = (int)array_sum($cards);
        $cardsWithHighAce = (int)array_sum($cards) + 13;

        if ($aceInCards && $cardsWithHighAce <= 21) {
            return $cardsWithHighAce;
        }
        return $cardsWithLowAce;
    }

    /**
     * Method takes an array with card objects, collects the cards value as an integer in another array
     * @param Cards[] $cards - array holding card objects
     * @return int[]
     */
    public function getAllCardValues(array $cards): array
    {
        $cardValues = [];

        foreach ($cards as $card) {
            $cardValues[] = (int) $card->getCardValue();
        }

        return $cardValues;
    }

    /**
     * Method creates and updates variables used on the html.twig using session data.
     * Once the bank has played the function checks for a winner.
     * @param  array<string,Hand|Cards[]> $data - holds array with session data for deck of cards(hand), player and bank.
     * @return array<string,mixed> $data - holds variables needed for rendering the html.twig
     */
    public function getGameData(array $data): array
    {
        /** @var Cards[] $player */
        $player = $data['player'];
        /** @var Cards[] $bank */
        $bank = $data['bank'];

        $playerPoints = $this->getPoints($player);
        $bankPoints = $this->getPoints($bank);

        $gameData = [
            'player' => $player,
            'bank' => $bank,
            'playerPoints' => $playerPoints,
            'bankPoints' => $bankPoints,
        ];
        $game21Data = new Game21Data();
        /** The players turn. */
        if (count($bank) == 0) {
            $data = $game21Data->getGameState($gameData);
            return $data;
        }

        /** The banks turn. */
        $data = $game21Data->getEndGameState($gameData);
        return $data;
    }

    /**
     * @param Cards[] $cards
     * @return array<string|null>
     */
    public function getCardNames(array $cards): array
    {
        $cardNames = [];
        foreach ($cards as $card) {
            $cardNames[] = $card->getName();
        }
        return $cardNames;
    }
    /**
     * @param Cards[] $cards
     * @return int
     */
    public function getPoints(array $cards): int
    {
        /** @var array<int> $cardValues */
        $cardValues = $this->getAllCardValues($cards);

        /** @var int $points */
        $points = $this->game21CalculatePoints($cardValues);
        return $points;
    }
}
