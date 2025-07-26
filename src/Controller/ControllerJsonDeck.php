<?php

namespace App\Controller;

use App\Cards\Cards;
use App\Cards\Games;
use App\Cards\Hand;
use App\JsonHelper\JsonHelper;
use App\SessionHandlers\GameSessionHandler;
use App\SessionHandlers\CardSessionHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ControllerJsonDeck extends AbstractController
{
    #[Route("/api", name: "start")]
    public function startApi(): Response
    {
        return $this->render('json_api.html.twig');
    }

    #[Route("/api/deck", name: "api_deck", methods: ['GET'])]
    public function apiDeck(): Response
    {
        $deck = new Hand();
        $json = array();
        foreach ($deck->deck as $card) {
            array_push($json, $card->getName());
        }

        $data = [
            'deck' => $json,
        ];

        $jsonHelper = new JsonHelper();
        $responseJson = $jsonHelper->getJsonPrettyPrint($data);
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
        $deck = new Hand();
        $deck->shuffle();
        $json = array();
        foreach ($deck->deck as $card) {
            array_push($json, $card->getName());
        }
        $data = [
            'deck' => $json,
        ];

        $jsonHelper = new JsonHelper();
        $responseJson = $jsonHelper->getJsonPrettyPrint($data);
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
