<?php

namespace App\Cards;

use App\Cards\CardGraphic;
use App\Cards\DeckOfCards;
use App\Cards\Hand;

/**
 * The game class helps with calculating and comparing values
 * as well as checking winning conditions of a game
 */
class Games
{
    /**
     * The function takes an array of integers. 
     * Checks if there's a 1 and if adding 13 would keep the sum under or equal to 21.
     * Then returns the array sum as an integer.
     * @param int[] $data - an array with integers
     * @return int
     */
    private function game21CalculatePoints($data): int
    {
        if (in_array(1, $data) && (array_sum($data) + 13) <= 21) {
            return array_sum($data) + 13;
        }
        return array_sum($data);
    }

    /**
     * Function takes an array with card objects, collects the cards value as an integer in another array
     * and returns the points calculated for game 21
     * @param Cards[] $data - array holding card objects
     * @return int
     */
    public function getPoints(array $data)
    {
        $points = [];

        //No cards have been drawn yet, return 0.
        if (count($data) == 0) {
            return 0;
        }

        for ($i = 0; $i < count($data); $i++) {
            $value = $data[$i]->getCardValue();
            $points[] = intval($value);//ensure it's an integer
        }
        // returns the array sum
        return $this->game21CalculatePoints($points);
    }

    /**
     * Returns a string declaring the winner of the game
     * @param int $bank - banks points
     * @param int $player - players points
     * @return string
     */
    public function determineWinner($bank, $player): string
    {
        if ($player == 0 && $bank == 0) {
            return "";
        }
        if ($player == 0 && $bank > 21) {
            return "No winners here, just losers!";
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
     * Function creates and updates variables used on the html.twig using session data.
     * Once the bank has played the function checks for a winner.
     * @param  array<string,Hand|Cards[]> $data - holds array with session data for deck of cards(hand), player and bank.
     * @return array<string,Cards[]|bool|int|string> $data - holds variables needed for rendering the html.twig
     */
    public function getGameData(array $data): array
    {
        /** @var Hand $hand */
        $hand = $data['hand'];
        /** @var Cards[] $player */
        $player = $data['player'];
        /** @var Cards[] $bank */
        $bank = $data['bank'];
        $winner = "";

        $playerPoints = $this->getPoints($player);
        $bankPoints = $this->getPoints($bank);

        /** The players turn. */
        if (count($bank) == 0) {
            $data = [
                'card' => $player,//Holds players card object in an array - to be displayed
                'bank' => $bank,//currently empty array
                'playerScore' => $playerPoints,
                'bankScore' => $bankPoints,//should be 0 because the bank hasn't played yet.
                'playersTurn' => $playerPoints <= 21,//draw button enabled(on true)/disabled(on false)
                'playerGotMoreThan21' => $playerPoints > 21,//player lost if more than 21 points
                'gameOngoing' => $playerPoints <= 21,//hold button will be removed when game ends (on false).
                'winner' => $winner,
            ];
            return $data;
        }

        /** The banks turn. */
        $winner = $this->determineWinner($bankPoints, $playerPoints);//returns string with winner

        $data = [
                'card' => $player,//Holds players card object in an array - to be displayed
                'bank' => $bank,//Holds banks card object in an array - to be displayed
                'playerScore' => $playerPoints,
                'bankScore' => $bankPoints,
                'playersTurn' => false,//removes draw button from form.
                'playerGotMoreThan21' => $playerPoints > 21,//should be false now.
                'gameOngoing' => false,//removed hold button from form. Show results. Game is done.
                'winner' => $winner,
            ];
        return $data;
    }
}
