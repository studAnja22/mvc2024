<?php

namespace App\Controller;

use App\Cards\Cards;
use App\Cards\DeckOfCards;
use App\Cards\Games;
use App\Cards\Hand;
use App\SessionHandlers\GameSessionHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ControllerGame extends AbstractController
{
    #[Route("/game", name: "game")]
    public function game(): Response
    {
        return $this->render('game.html.twig');
    }

    #[Route("/game/doc", name: "gameDoc")]
    public function gameDoc(): Response
    {
        return $this->render('gameDoc.html.twig');
    }

    #[Route("/game/play", name: "game_action", methods: ['POST'])]
    public function newGame(
        Request $request,
        SessionInterface $gameSession
    ): Response {
        $action = $request->request->get('game');
        $gameSessionHandler = new GameSessionHandler();

        switch ($action) {
            case "New Game":
                $gameSessionHandler->setNewGame($gameSession);
                return $this->redirectToRoute('gamePlay');
            case "Draw":
                //Player draws cards;
                $gameSessionHandler->setDrawCard($gameSession);
                return $this->redirectToRoute('gamePlay');
            case "Hold":
                // Bank draws cards.
                $gameSessionHandler->setBankPlays($gameSession);
                return $this->redirectToRoute('gamePlay');
            default:
                return $this->redirectToRoute('gamePlay');
        }
    }

    #[Route("/game/play", name: "gamePlay", methods: ['GET'])]
    public function gamePlay(
        SessionInterface $gameSession
    ): Response {
        if (!$gameSession->has('21_deck')) {
            $gameSessionHandler = new GameSessionHandler();
            $gameSessionHandler->setNewGame($gameSession);
        }
        $game = new Games();
        /** @var array<string,Cards[]> $gameData */
        $gameData = [
            'player' => $gameSession->get('player'),
            'bank' => $gameSession->get('bank'),
        ];

        $data = $game->getGameData($gameData);
        return $this->render('gamePlay.html.twig', $data);
    }
}
