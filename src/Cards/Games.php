<?php

namespace App\Cards;

use App\Cards\Cards;
use App\Cards\CardGraphic;
use App\Cards\DeckOfCards;
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
     * Returns a string declaring the winner of the game
     *
     * This method takes the banks points and the players points and compare them to determine who won the game 21.
     * Method returns a string declaring who won the game.
     * @param int $bank - banks points
     * @param int $player - players points
     * @return string
     */
    public function determineWinner($bank, $player): string
    {
        if ($player == 0 && $bank > 21) {
            return "No winners here, just losers!";
        }
        if ($player > 21) {
            return "Bank Wins!";
        }
        if ($bank > 21) {
            return "Player Wins!";
        }
        if ($bank >= $player) {
            return "Bank Wins!";
        }
        return "Player Wins!";
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

        /** The players turn. */
        if (count($bank) == 0) {
            $data = $this->getGameState($gameData);
            return $data;
        }

        /** The banks turn. */
        $data = $this->getEndGameState($gameData);
        return $data;
    }
    /**
     * @param array<string,Cards[]|int> $gameData
     * @return array<string,mixed>
     */
    public function getGameState(array $gameData): mixed
    {
        $winner = "";

        $data = [
            'player' => $gameData['player'],//Holds players card object in an array - to be displayed
            'bank' => $gameData['bank'],//Empty array or array with card objects
            'playerScore' => $gameData['playerPoints'],
            'bankScore' => $gameData['bankPoints'],//should be 0 because the bank hasn't played yet.
            'playersTurn' => $gameData['playerPoints'] <= 21,//draw button enabled(on true)/disabled(on false)
            'playerGotMoreThan21' => $gameData['playerPoints'] > 21,//player lost if more than 21 points
            'gameOngoing' => $gameData['playerPoints'] <= 21,//hold button will be removed when game ends (on false).
            'winner' => $winner,
        ];

        return $data;
    }
    /**
     * @param array<string,Cards[]|int> $gameData
     * @return array<string,mixed>
     */
    public function getEndGameState(array $gameData): mixed
    {
        /** @var int $bankScore */
        $bankScore = $gameData['bankPoints'];
        /** @var int $playerScore */
        $playerScore = $gameData['playerPoints'];
        /** @var string $winner */
        $winner = $this->determineWinner($bankScore, $playerScore);//returns string with winner

        $data = [
            'player' => $gameData['player'],//Holds players card object in an array, could be [] if player drew 0 cards.
            'bank' => $gameData['bank'],//Holds banks card objects in an array
            'playerScore' => $gameData['playerPoints'],
            'bankScore' => $gameData['bankPoints'],
            'playersTurn' => false,//removes the draw button from form
            'playerGotMoreThan21' => $gameData['playerPoints'] > 21,//should be false
            'gameOngoing' => false,//removed hold button from form. Show results. Game is done.
            'winner' => $winner,
        ];

        return $data;
    }
    /**
     * @param array<string,mixed> $gameData
     * @return array<string,mixed>
     */
    public function getCurrentGameState(array $gameData): array
    {
        /** @var Cards[] $playerCards */
        $playerCards = $gameData['player'];
        /** @var Cards[] $bankCards */
        $bankCards = $gameData['bank'];

        $playerHasTheseCards = $this->getCardNames($playerCards);

        $bankHasTheseCards = $this->getCardNames($bankCards);

        $jsonData = [
            'player' => $playerHasTheseCards,
            'bank' => $bankHasTheseCards,
            'playerScore' => $gameData['playerScore'],
            'bankScore' => $gameData['bankScore'],
            'playersTurn' => $gameData['playersTurn'],
            'playerGotMoreThan21' => $gameData['playerGotMoreThan21'],
            'gameOngoing' => $gameData['gameOngoing'],
            'winner' => $gameData['winner'],
        ];

        return $jsonData;
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
