<?php

namespace App\Cards;

use App\Cards\Cards;
use App\Cards\CardGraphic;
use App\Cards\DeckOfCards;
use App\Cards\Games;
use App\Cards\Hand;

/**
 * The game class helps with calculating and comparing values
 * as well as checking winning conditions of a game
 * and gets the values needed to render the game page.
 */
class Game21Data
{
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
        $game21 = new Games();

        $playerHasTheseCards = $game21->getCardNames($playerCards);

        $bankHasTheseCards = $game21->getCardNames($bankCards);

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
}
