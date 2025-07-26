<?php

namespace App\Controller;

use App\Cards\Cards;
use App\Cards\Games;
use App\Cards\Hand;
use App\JsonHelper\JsonHelper;
use App\SessionHandlers\GameSessionHandler;
use App\SessionHandlers\CardSessionHandler;
use App\SessionHandlers\CardSessionJsonHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ControllerJsonCards extends AbstractController
{
    #[Route("/api/deck/draw", name: "api_draw_post", methods: ['POST'])]
    public function drawOnePost(
        SessionInterface $sessionJson
    ): Response {
        if ($sessionJson->get('deck') == null) {
            $deck = new Hand();
            $deck->shuffle();
            $sessionJson->set('deck', $deck);
        }
        return $this->redirectToRoute('api_draw');
    }

    #[Route("/api/deck/draw", name: "api_draw", methods: ['GET'])]
    public function drawOne(
        SessionInterface $sessionJson
    ): Response {
        /** @var Hand @deck */
        $deck = $sessionJson->get('deck');

        /** Default: all cards drawn */
        $data = [
            'card' => "You've drawn all cards!",
            'number' => $deck->howManyLeft(),
        ];

        /** @var int $numberOfCardsLeft*/
        $numberOfCardsLeft = $deck->howManyLeft();

        if ($numberOfCardsLeft > 0) {
            /** @var Cards $json */
            $json = $deck->drawAndDiscard();
            $numberOfCardsLeft = $deck->howManyLeft();
            $data = [
                'card' => $json->getName(),
                'cards_left' => $numberOfCardsLeft,
            ];
        }

        $jsonHelper = new JsonHelper();
        $responseJson = $jsonHelper->getJsonPrettyPrint($data);
        $sessionJson->set('deck', $deck);

        return $responseJson;
    }

    #[Route("/api/deck/:number/", name: "api_draw_more_post", methods: ['POST'])]
    public function drawMorePost(
        Request $requestJson,
        SessionInterface $sessionJson
    ): Response {
        /** @var Hand|null $deck */
        $deck = $sessionJson->get('deck');

        if ($deck == null) {
            $deck = new Hand();
            $deck->shuffle();
            $sessionJson->set('deck', $deck);
        }

        /** @var int $drawThisManyCards */
        $drawThisManyCards = $requestJson->request->get('num_cards');
        $sessionJson->set('amount', $drawThisManyCards);
        return $this->redirectToRoute('api_draw_more_get');
    }

    #[Route("/api/deck/:number/", name: "api_draw_more_get", methods: ['GET'])]
    public function drawMoreGet(
        SessionInterface $sessionJson
    ): Response {
        $cardSessionHelper = new CardSessionJsonHandler();
        $cardSessionHelper->setSessionJsonAmountDrawCards($sessionJson);
        /** @var Hand $deck */
        $deck = $sessionJson->get('deck');

        if ($deck->howManyLeft() == 0) {
            $data = [
                'card' => "You have drawn all cards, reset to get a new deck.",
                'cards_left' => "0",
            ];

            $jsonHelper = new JsonHelper();
            $responseJson = $jsonHelper->getJsonPrettyPrint($data);

            return $responseJson;
        }

        $cardSessionHelper->setSessionJsonDeckDrawCards($sessionJson);
        $cardsByName = $cardSessionHelper->getDrawnCardsByName($sessionJson);

        /** @var Hand $deck */
        $deck = $sessionJson->get('deck');

        $data = [
            'card' => $cardsByName,
            'cards_left' => $deck->howManyLeft(),
        ];

        $jsonHelper = new JsonHelper();
        $responseJson = $jsonHelper->getJsonPrettyPrint($data);

        $sessionJson->set('amount', 0);
        $sessionJson->set('deck', $deck);

        return $responseJson;
    }

    #[Route("/api/game", name: "game_21", methods: ['GET'])]
    public function jsonGame21(
        SessionInterface $gameSession
    ): Response {
        $gameSessionHandler = new GameSessionHandler();
        if (!$gameSession->has('21_deck')) {
            $gameSessionHandler->setNewGame($gameSession);
        }

        $game = new Games();
        /** @var array<string,Hand|Cards[]> $gameData */
        $gameData = [
            'player' => $gameSession->get('player'),
            'bank' => $gameSession->get('bank'),
        ];

        $data = $game->getGameData($gameData);
        $jsonData = $game->getCurrentGameState($data);

        $jsonHelper = new JsonHelper();
        $responseJson = $jsonHelper->getJsonPrettyPrint($jsonData);

        return $responseJson;
    }
}
