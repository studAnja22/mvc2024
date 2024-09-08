<?php

namespace App\Controller;

use App\Cards\Cards;
use App\Cards\DeckOfCards;
use App\Cards\Hand;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ControllerJsonCards extends AbstractController
{
    #[Route("/api", name: "start")]
    public function startApi(): Response
    {
        return $this->render('json_api.html.twig');
    }

    #[Route("/api/deck", name: "api_deck", methods: ['GET'])]
    public function apiDeck(): Response
    {
        $deck = new DeckOfCards();
        $json = array();
        foreach ($deck->deck as $card) {
            array_push($json, $card->getName());
        }

        $data = [
            'deck' => $json,
        ];

        $responseJson = new JsonResponse($data);
        $responseJson->setEncodingOptions(
            $responseJson->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $responseJson;
    }

    #[Route("/api/deck/shuffle", name: "api_shuffle_post", methods: ['POST'])]
    public function apiShufflePost(): Response
    {
        return $this->redirectToRoute('api_shuffle_get');
    }

    #[Route("/api/deck/shuffle", name: "api_shuffle_get", methods: ['GET'])]
    public function apiShuffleGet(): Response
    {
        $deck = new DeckOfCards();
        $deck->shuffleDeck();
        $json = array();
        foreach ($deck->deck as $card) {
            array_push($json, $card->getName());
        }
        $data = [
            'deck' => $json,
        ];

        $responseJson = new JsonResponse($data);
        $responseJson->setEncodingOptions(
            $responseJson->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $responseJson;
    }
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

        $responseJson = new JsonResponse($data);
        $responseJson->setEncodingOptions(
            $responseJson->getEncodingOptions() | JSON_PRETTY_PRINT
        );

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

        /** @var int $numCards */
        $numCards = $requestJson->request->get('num_cards');
        $sessionJson->set('amount', $numCards);

        return $this->redirectToRoute('api_draw_more_get');
    }

    #[Route("/api/deck/:number/", name: "api_draw_more_get", methods: ['GET'])]
    public function drawMoreGet(
        SessionInterface $sessionJson
    ): Response {
        /** @var Hand $deck */
        $deck = $sessionJson->get('deck');
        $card = array();
        $json = array();

        /** @var int $cardsLeft */
        $cardsLeft = $deck->howManyLeft();

        if ($cardsLeft == 0) {
            $json = "You have drawn all cards";

            $data = [
                'card' => $json,
                'cards_left' => $deck->howManyLeft(),
            ];

            $responseJson = new JsonResponse($data);
            $responseJson->setEncodingOptions(
                $responseJson->getEncodingOptions() | JSON_PRETTY_PRINT
            );

            return $responseJson;
        }

        /** @var int $amount */
        $amount = $sessionJson->get('amount');

        if ($cardsLeft < $amount) {
            $sessionJson->set('amount', $cardsLeft);
        }

        for ($x = 0; $x < $amount; $x++) {
            $deck->drawAndDiscard();
        }

        $card = $deck->getDrawnByIndex($amount);

        foreach ($card as $card) {
            array_push($json, $card->getName());
        }

        $data = [
            'card' => $json,
            'cards_left' => $deck->howManyLeft(),
        ];

        $responseJson = new JsonResponse($data);
        $responseJson->setEncodingOptions(
            $responseJson->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        $sessionJson->set('amount', 0);
        $sessionJson->set('deck', $deck);

        return $responseJson;
    }

    #[Route("/api/deck/resetter", name: "resetter", methods: ['POST'])]
    public function resetter(
        SessionInterface $sessionJson
    ): Response {
        $deck = new Hand();
        $deck->shuffle();
        $sessionJson->set('deck', $deck);
        $sessionJson->set('amount', 0);
        return $this->render('json_api.html.twig');
    }
}
