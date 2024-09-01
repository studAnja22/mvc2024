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

class ControllerJson extends AbstractController
{
    #[Route("/api/lucky/number")]
    public function jsonNumber(): Response
    {
        $number = random_int(0, 100);

        $data = [
            'lucky-number' => $number,
            'lucky-message' => 'Hi there!',
        ];
        /*
                $response = new Response();
                $response->setContent(json_encode($data));
                $response->headers->set('Content-Type', 'application/json');

                return $response;
        */
        // return new JsonResponse($data);

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/quote", name: "quote")]
    public function jsonQuote(): Response
    {
        $quotes = array('"Be silent or let thy words be worth more than silence" - Pythagoras',
        '"Do not say a little in many words, but a great deal in few!"- Pythagoras',
        '"The only person with whom you have to compare ourselves, is that you in the past. 
        And the only person better you should be, this is who you are now" - Sigmund Freud',
        '"From error to error, one discovers the entire truth" - Sigmund Freud',
        '"The journey of a thousand miles begins with a single step" - Lao Tzu',
        '"He who knows all the answers has not been asked all the questions" - Confucius',);
        $data = [
            'quote' => $quotes[random_int(0, 5)],
        ];

        $response = new Response();
        $response->setContent(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    #[Route("/api", name: "start")]
    public function start_api(): Response
    {
        return $this->render('json_api.html.twig');
    }

    #[Route("/api/deck", name: "api_deck", methods: ['GET'])]
    public function api_deck(): Response
    {
        $deck = new DeckOfCards();
        $json = array();
        foreach ($deck->deck as $card) {
            array_push($json, $card->getName());
        }

        $data = [
            'deck' => $json,
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    #[Route("/api/deck/shuffle", name: "api_shuffle_post", methods: ['POST'])]
    public function api_shuffle(
        //Request $request,
        SessionInterface $session
    ): Response {
        return $this->redirectToRoute('api_shuffle_get');
    }

    #[Route("/api/deck/shuffle", name: "api_shuffle_get", methods: ['GET'])]
    public function api_shuffle_get(
        SessionInterface $session
    ): Response {
        $deck = new DeckOfCards();
        $deck->shuffleDeck();
        $json = array();
        foreach ($deck->deck as $card) {
            array_push($json, $card->getName());
        }
        $data = [
            'deck' => $json,
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }
    #[Route("/api/deck/draw", name: "api_draw_post", methods: ['POST'])]
    public function draw_one_post(
        Request $request,
        SessionInterface $session
    ): Response {
        if ($session->get('deck') == null) {
            $deck = new Hand();
            $deck->shuffle();
            $session->set('deck', $deck);
        }
        return $this->redirectToRoute('api_draw');
    }
    #[Route("/api/deck/draw", name: "api_draw", methods: ['GET'])]
    public function draw_one(
        SessionInterface $session
    ): Response {
        $deck = $session->get('deck');

        if ($deck->howManyLeft() == 0) {
            $data = [
                'card' => "You have drawn all cards",
                'number' => $deck->howManyLeft(),
            ];
        } else {
            $json = $deck->drawAndDiscard();
            $data = [
                'card' => $json->getName(),
                'cards_left' => $deck->howManyLeft(),
            ];
        }

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        $session->set('deck', $deck);

        return $response;
    }
    #[Route("/api/deck/:number/", name: "api_draw_more_post", methods: ['POST'])]
    public function draw_more_post(
        Request $request,
        SessionInterface $session
    ): Response {
        if ($session->get('deck') == null) {
            $deck = new Hand();
            $deck->shuffle();
            $session->set('deck', $deck);
        }
        $numCards = $request->request->get('num_cards');
        $session->set('amount', $numCards);

        return $this->redirectToRoute('api_draw_more_get');
    }

    #[Route("/api/deck/:number/", name: "api_draw_more_get", methods: ['GET'])]
    public function draw_more_get(
        Request $request,
        SessionInterface $session
    ): Response {
        $deck = $session->get('deck');
        $card = array();
        $json = array();

        $cards_left = $deck->howManyLeft();

        if ($cards_left == 0) {
            $json = "You have drawn all cards";
        } else {
            if ($cards_left < $session->get('amount')) {
                $session->set('amount', $cards_left);
            }
            for ($x = 0; $x < $session->get('amount'); $x++) {
                $deck->drawAndDiscard();
                }
                $card = $deck->getDrawnByIndex($session->get('amount'));
            foreach ($card as $card) {
                array_push($json, $card->getName());
                }
        }

        $data = [
            'card' => $json,
            'cards_left' => $deck->howManyLeft(),
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        $session->set('amount', 0);
        $session->set('deck', $deck);

        return $response;
    }

    #[Route("/api/deck/resetter", name: "resetter", methods: ['POST'])]
    public function resetter(
        Request $request,
        SessionInterface $session
    ): Response {
        $deck = new Hand();
        $deck->shuffle();
        $session->set('deck', $deck);
        $session->set('amount', 0);
        return $this->render('json_api.html.twig');
    }
}
