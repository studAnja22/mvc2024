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

class ControllerTwig extends AbstractController
{
    #[Route("/", name: "home")]
    public function home(): Response
    {
        return $this->render('home.html.twig');
    }

    #[Route("/about", name: "about")]
    public function about(): Response
    {
        return $this->render('about.html.twig');
    }

    #[Route("/report", name: "report")]
    public function report(): Response
    {
        return $this->render('report.html.twig');
    }

    #[Route("/card", name: "card")]
    public function card(): Response
    {
        return $this->render('card.html.twig');
    }

    #[Route("card/deck", name: "deck")]
    public function deck(
        SessionInterface $session
    ): Response {
        $newDeck = new DeckOfCards();

        $data = [
            'deck' => $newDeck->getDeck(),
        ];

        return $this->render('deck.html.twig', $data);
    }

    #[Route("card/deck/shuffle", name: "deckShuffle")]
    public function deckShuffle(
        SessionInterface $session
    ): Response {
        $session->clear();

        $hand = new Hand();
        $hand->shuffle();

        $data = [
            'shuffle' => $hand->shuffle(),
        ];
        $session->set('hand', $hand);

        return $this->render('deckShuffle.html.twig', $data);
    }

    #[Route("card/deck/draw", name: "draw_init", methods: ['POST'])]
    public function init_draw(
        //Request $request,
        SessionInterface $session
    ): Response {
        $session->clear();

        $hand = new Hand();
        $session->set('hand', $hand);
        return $this->redirectToRoute('draw_part2');
    }

    #[Route("card/deck/draw", name: "draw_part2", methods: ['GET'])]
    public function drawPart2(
        SessionInterface $session
    ): Response {
        if ($session->get('hand')->howManyLeft() == 0) {
            $hand = new Hand();
            $session->set('hand', $hand);
            $hand = $session->get('hand');
        } else {
            $hand = $session->get('hand');
        }

        $data = [
            'card' => $hand->drawTopCard(),
            'message' => $hand->howManyLeft() - 1,
        ];

        if ($session->get('hand')->howManyLeft() <= 1) {
            $newHand = new Hand();
            $session->set('hand', $newHand);
        } else {
            $hand->removeTopCard();
            $session->set('hand', $hand);
        }

        return $this->render('deckDraw.html.twig', $data);
    }

    #[Route("card/deck/draw/:number", name: "callbackNumber", methods: ['POST'])]
    public function initCallback(
        Request $request,
        SessionInterface $session
    ): Response {
        $numCards = $request->request->get('num_cards');

        if ($session->get('hand')->howManyLeft() == 0) {
            $hand = new Hand();
            $session->set('hand', $hand);
            $hand = $session->get('hand');
        } else {
            $hand = $session->get('hand');
        }

        if ($hand->howManyLeft() < $numCards) { // om det är fler kort i numcard än i leken så drar vi de sista korten.
            $numCards = $hand->howManyLeft();
        }

        $session->set('amount', $numCards);

        return $this->redirectToRoute('deckNumber');
    }

    #[Route("card/deck/draw/:number", name: "deckNumber", methods: ['GET'])]
        public function init(
            SessionInterface $session
        ): Response {
            $hand = $session->get('hand');
            $card = array();

            if ($session->get('amount') > 0) {
                for ($x = 0; $x < $session->get('amount'); $x++) {
                    $hand->drawAndDiscard();
                }
                $card = $hand->getDrawnByIndex($session->get('amount'));
            }

            $data = [
                'card' => $card,
                'number' => $hand->howManyLeft(),
            ];
            $session->set('amount', 0);
            return $this->render('deckNumber.html.twig', $data);
        }
}