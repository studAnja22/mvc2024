<?php

namespace App\Controller;

use App\Cards\Cards;
use App\Cards\DeckOfCards;
use App\Cards\Games;
use App\Cards\Hand;
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
    ): Response
    {
        $action = $request->request->get('game');

        switch ($action) {
            case "New Game":
                $gameSession->clear();
                $hand = new Hand();
                $hand->shuffle();
                $gameSession->set('21_deck', $hand);
                $gameSession->set('player', []);
                $gameSession->set('bank', []);
                return $this->redirectToRoute('gamePlay');
            case "Draw":
                //Player draws cards;
                $hand = $gameSession->get('21_deck');
                $player = $gameSession->get('player');
                $player[] = $hand->drawTopCard();
                $hand->removeTopCard();
                $gameSession->set('21_deck', $hand);
                $gameSession->set('player', $player);
                return $this->redirectToRoute('gamePlay');
            case "Hold":
                // Bank draws cards.
                $bank = $gameSession->get('bank');
                $hand = $gameSession->get('21_deck');
                $game = new Games();
                $continueDrawingCards = true;
                /**
                 * Bank draws cards until it has 17 or more in value.
                 * We save the drawn cards in 'bank' session.
                 * We do not save the updated deck in session because the game has ended after the bank has drawn cards.
                 */
                while ($continueDrawingCards) {
                    $bank[] = $hand->drawTopCard();
                    $hand->removeTopCard();

                    $bankPoints = $game->getPoints($bank);

                    if ($bankPoints >= 17) {
                        $continueDrawingCards = false;
                    }
                }
                $gameSession->set('bank', $bank);
                return $this->redirectToRoute('gamePlay');
            default:
                return $this->redirectToRoute('gamePlay');
            }
        
    }

    #[Route("/game/play", name: "gamePlay", methods: ['GET'])]
    public function gamePlay(
        SessionInterface $gameSession
    ): Response
    {
        if (!$gameSession->has('21_deck')) {
            $hand = new Hand();
            $hand->shuffle();
            $gameSession->set('21_deck', $hand);
            $gameSession->set('player', []);
            $gameSession->set('bank', []);
        }
        $game = new Games();
        $gameData = [
            'hand' => $gameSession->get('21_deck'),
            'player' => $gameSession->get('player'),
            'bank' => $gameSession->get('bank'),
        ];

        $data = $game->getGameData($gameData);
        return $this->render('gamePlay.html.twig', $data);
    }
}
