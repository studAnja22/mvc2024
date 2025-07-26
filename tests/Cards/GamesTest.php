<?php

namespace App\Cards;

use App\Tests\Helpers\DummyPlayerMaker;
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

    public function testPlayerWinByPoints(): void
    {
        $game = new Games();
        $hand = new Hand();
        $dummy = new DummyPlayerMaker();
        $playerWith19Points = $dummy->createDummy(3);
        $bankWith16Points = $dummy->createDummy(2);

        $gameData = [
            'hand' => $hand,
            'player' => $playerWith19Points,
            'bank' => $bankWith16Points,
        ];

        $data = $game->getGameData($gameData);
        $this->assertEquals($data['player'], $playerWith19Points);
        $this->assertEquals($data['bank'], $bankWith16Points);
        $this->assertEquals($data['playerScore'], 19);
        $this->assertEquals($data['bankScore'], 16);
        $this->assertEquals($data['playersTurn'], false);
        $this->assertEquals($data['playerGotMoreThan21'], false);
        $this->assertEquals($data['gameOngoing'], false);
        $this->assertEquals($data['winner'], "Player Wins!");
    }

    public function testPlayerWinBankBusted(): void
    {
        $game = new Games();
        $hand = new Hand();
        $dummy = new DummyPlayerMaker();
        $playerWith19Points = $dummy->createDummy(3);
        $bankWith28Points = $dummy->createDummy(7);

        $gameData = [
            'hand' => $hand,
            'player' => $playerWith19Points,
            'bank' => $bankWith28Points,
        ];

        $data = $game->getGameData($gameData);

        $this->assertEquals($data['player'], $playerWith19Points);
        $this->assertEquals($data['bank'], $bankWith28Points);
        $this->assertEquals($data['playerScore'], 19);
        $this->assertEquals($data['bankScore'], 28);
        $this->assertEquals($data['playersTurn'], false);
        $this->assertEquals($data['playerGotMoreThan21'], false);
        $this->assertEquals($data['gameOngoing'], false);
        $this->assertEquals($data['winner'], "Player Wins!");
    }

    public function testBankWinIfEqualPoints(): void
    {
        $game = new Games();
        $hand = new Hand();
        $dummy = new DummyPlayerMaker();
        $playerWith21Points = $dummy->createDummy(6);
        $bankWith21Points = $dummy->createDummy(6);

        $gameData = [
            'hand' => $hand,
            'player' => $playerWith21Points,
            'bank' => $bankWith21Points,
        ];

        $data = $game->getGameData($gameData);

        $this->assertEquals($data['player'], $playerWith21Points);
        $this->assertEquals($data['bank'], $bankWith21Points);
        $this->assertEquals($data['playerScore'], 21);
        $this->assertEquals($data['bankScore'], 21);
        $this->assertEquals($data['playersTurn'], false);
        $this->assertEquals($data['playerGotMoreThan21'], false);
        $this->assertEquals($data['gameOngoing'], false);
        $this->assertEquals($data['winner'], "Bank Wins!");
    }

    public function testBankWinIfPlayerBust(): void
    {
        $game = new Games();
        $hand = new Hand();
        $dummy = new DummyPlayerMaker();
        $playerWith28Points = $dummy->createDummy(7);
        $bankWith15Points = $dummy->createDummy(5);

        $gameData = [
            'hand' => $hand,
            'player' => $playerWith28Points,
            'bank' => $bankWith15Points,
        ];

        $data = $game->getGameData($gameData);

        $this->assertEquals($data['player'], $playerWith28Points);
        $this->assertEquals($data['bank'], $bankWith15Points);
        $this->assertEquals($data['playerScore'], 28);
        $this->assertEquals($data['bankScore'], 15);
        $this->assertEquals($data['playersTurn'], false);
        $this->assertEquals($data['playerGotMoreThan21'], true);
        $this->assertEquals($data['gameOngoing'], false);
        $this->assertEquals($data['winner'], "Bank Wins!");
    }

    public function testPlayerDrawsZeroCards(): void
    {
        $game = new Games();
        $hand = new Hand();
        $dummy = new DummyPlayerMaker();
        $playerWithZeroPoints = $dummy->createDummy(0);
        $bankWith15Points = $dummy->createDummy(5);

        $gameData = [
            'hand' => $hand,
            'player' => $playerWithZeroPoints,
            'bank' => $bankWith15Points,
        ];

        $data = $game->getGameData($gameData);

        $this->assertEquals($data['player'], $playerWithZeroPoints);
        $this->assertEquals($data['bank'], $bankWith15Points);
        $this->assertEquals($data['playerScore'], 0);
        $this->assertEquals($data['bankScore'], 15);
        $this->assertEquals($data['playersTurn'], false);
        $this->assertEquals($data['playerGotMoreThan21'], false);
        $this->assertEquals($data['gameOngoing'], false);
        $this->assertEquals($data['winner'], "Bank Wins!");
    }

    public function testPlayerDrawsZeroCardsBankBust(): void
    {
        $game = new Games();
        $hand = new Hand();
        $dummy = new DummyPlayerMaker();
        $playerWithZeroPoints = $dummy->createDummy(0);
        $bankWith15Points = $dummy->createDummy(7);

        $gameData = [
            'hand' => $hand,
            'player' => $playerWithZeroPoints,
            'bank' => $bankWith15Points,
        ];

        $data = $game->getGameData($gameData);

        $this->assertEquals($data['player'], $playerWithZeroPoints);
        $this->assertEquals($data['bank'], $bankWith15Points);
        $this->assertEquals($data['playerScore'], 0);
        $this->assertEquals($data['bankScore'], 28);
        $this->assertEquals($data['playersTurn'], false);
        $this->assertEquals($data['playerGotMoreThan21'], false);
        $this->assertEquals($data['gameOngoing'], false);
        $this->assertEquals($data['winner'], "No winners here, just losers!");
    }
}
