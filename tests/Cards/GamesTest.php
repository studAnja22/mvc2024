<?php

namespace App\Cards;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for Games class
 */
class GamesTest extends TestCase
{
    /**
     * Checks if we get the variables we need to properly rendering the html page.
     * getGameData() takes an array with the keys 'hand', 'player', 'bank'.
     * hand contains the deck of cards, player and bank holds their respective drawn cards.
     *
     * In this test case we have no drawn cards.
     */
    public function testVariablesForHTMLPage(): void
    {
        $game = new Games();
        /** @var array<string,Hand|Cards[]> $gameData */
        $gameData = [
            'hand' => new Hand(),
            'player' => [],
            'bank' => [],
        ];

        $data = $game->getGameData($gameData);
        $this->assertEquals($data['player'], []);
        $this->assertEquals($data['bank'], []);
        $this->assertEquals($data['playerScore'], 0);
        $this->assertEquals($data['bankScore'], 0);
        $this->assertEquals($data['playersTurn'], true);
        $this->assertEquals($data['playerGotMoreThan21'], false);
        $this->assertEquals($data['gameOngoing'], true);
        $this->assertEquals($data['winner'], "");
    }
    /**
     * Player wins by having more than the bank,
     * or the bank draws more than 21.
     */
    public function testPlayerWinsGame(): void
    {
        $game = new Games();
        $hand = new Hand();
        //Player: (Ace of Hearts, 2 of Hearts, 3 of Hearts)
        $playerCards = [];
        for ($i = 0; $i < 3; $i++) {
            $card = $hand->drawAndDiscard();
            if ($card != false) {
                array_push($playerCards, $card);
            }
        }

        //Bank: (4 of Hearts, 5 of hearts, 6 of Hearts)
        $bankCards = [];
        for ($i = 0; $i < 3; $i++) {
            $card = $hand->drawAndDiscard();
            if ($card != false) {
                array_push($bankCards, $card);
            }
        }

        $gameData = [
            'hand' => $hand,
            'player' => $playerCards,
            'bank' => $bankCards,
        ];
        //Player wins by having a higher number
        $data = $game->getGameData($gameData);
        $this->assertEquals($data['player'], $playerCards);
        $this->assertEquals($data['bank'], $bankCards);
        $this->assertEquals($data['playerScore'], 19);
        $this->assertEquals($data['bankScore'], 15);
        $this->assertEquals($data['playersTurn'], false);
        $this->assertEquals($data['playerGotMoreThan21'], false);
        $this->assertEquals($data['gameOngoing'], false);
        $this->assertEquals($data['winner'], "Player Wins!");

        /**
         * Player wins if the bank gets more than 21.
         * We add two more cards to the banks hand. (7 and 8 of hearts)
         */
        for ($i = 0; $i < 2; $i++) {
            $card = $hand->drawAndDiscard();
            if ($card != false) {
                array_push($bankCards, $card);
            }
        }

        $gameData = [
                    'hand' => $hand,
                    'player' => $playerCards,
                    'bank' => $bankCards,
                ];

        $data = $game->getGameData($gameData);
        $this->assertEquals($data['bank'], $bankCards);
        $this->assertEquals($data['playerScore'], 19);
        $this->assertEquals($data['bankScore'], 30);
        $this->assertEquals($data['winner'], "Player Wins!");
    }
    /**
     * Bank wins if it has higher value than the player,
     * or if the player get more than 21.
     */
    public function testBankWinsGame(): void
    {
        $game = new Games();
        $hand = new Hand();
        //Bank: (Ace of Hearts, 2 of Hearts, 3 of Hearts)
        $bankCards = [];
        for ($i = 0; $i < 3; $i++) {
            $card = $hand->drawAndDiscard();
            if ($card != false) {
                array_push($bankCards, $card);
            }
        }

        //Player: (4 of Hearts, 5 of hearts, 6 of Hearts)
        $playerCards = [];
        for ($i = 0; $i < 3; $i++) {
            $card = $hand->drawAndDiscard();
            if ($card != false) {
                array_push($playerCards, $card);
            }
        }

        $gameData = [
            'hand' => $hand,
            'player' => $playerCards,
            'bank' => $bankCards,
        ];

        $data = $game->getGameData($gameData);
        $this->assertEquals($data['player'], $playerCards);
        $this->assertEquals($data['bank'], $bankCards);
        $this->assertEquals($data['playerScore'], 15);
        $this->assertEquals($data['bankScore'], 19);
        $this->assertEquals($data['winner'], "Bank Wins!");

        /**
         * Bank wins if player gets more than 21
         */
        for ($i = 0; $i < 2; $i++) {
            $card = $hand->drawAndDiscard();
            if ($card != false) {
                array_push($playerCards, $card);
            }
        }

        $gameData = [
                    'hand' => $hand,
                    'player' => $playerCards,
                    'bank' => $bankCards,
                ];

        $data = $game->getGameData($gameData);
        $this->assertEquals($data['player'], $playerCards);
        $this->assertEquals($data['bank'], $bankCards);
        $this->assertEquals($data['playerScore'], 30);
        $this->assertEquals($data['bankScore'], 19);
        $this->assertEquals($data['winner'], "Player Wins!");
    }
    /**
     * The player can choose not to draw any cards.
     * The bank will play as normal.
     * The bank will win unless it draws more than 21.
     *
     * We check what happens when bank draws more than 21.
     */
    public function testPlayersDrawsNoCards(): void
    {
        $game = new Games();
        $hand = new Hand();
        //Bank: (Ace of Hearts, 2 of Hearts, 3 of Hearts)
        $bankCards = [];
        for ($i = 0; $i < 7; $i++) {
            $card = $hand->drawAndDiscard();
            if ($card != false) {
                array_push($bankCards, $card);
            }
        }

        //Player: draws no cards
        $playerCards = [];

        $gameData = [
            'hand' => $hand,
            'player' => $playerCards,
            'bank' => $bankCards,
        ];

        $data = $game->getGameData($gameData);
        $this->assertEquals($data['player'], $playerCards);
        $this->assertEquals($data['bank'], $bankCards);
        $this->assertEquals($data['playerScore'], 0);
        $this->assertEquals($data['bankScore'], 28);
        $this->assertEquals($data['winner'], "No winners here, just losers!");
    }
}
