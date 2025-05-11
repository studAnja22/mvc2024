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
    private function game21CalculatePoints($data) {
        //Returns the arrays ($data) sum. Checks if there's an ACE and changes the value from 1 to 14
        //If the sum remains equal to or lower than 21.
        if (in_array(1, $data) && (array_sum($data) + 13) <= 21) {
            return array_sum($data) + 13;
        }
        return array_sum($data);
    }

    public function getPoints($data): int {
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

    public function determineWinner($bank, $player): string {
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

    public function getGameData($data) {
        /** @var Hand $hand */
        $hand = $data['hand'];
        $player = $data['player'];
        $bank = $data['bank'];
        $winner = "";
        /** @var Games $game */

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