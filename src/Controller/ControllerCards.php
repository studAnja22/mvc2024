<?php

namespace App\Controller;

use App\Cards\Cards;
use App\Cards\DeckOfCards;
use App\Cards\Hand;
use App\SessionHandlers\CardSessionHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ControllerCards extends AbstractController
{
    #[Route("/card", name: "card")]
    public function card(): Response
    {
        return $this->render('card.html.twig');
    }

    #[Route("card/deck", name: "deck")]
    public function deck(): Response
    {
        $hand = new Hand();

        $data = [
            'deck' => $hand->getDeck(),
        ];

        return $this->render('deck.html.twig', $data);
    }

    #[Route("card/deck/shuffle", name: "deckShuffle")]
    public function deckShuffle(
        SessionInterface $session
    ): Response {
        $sessionHelper = new CardSessionHandler();

        $sessionHelper->setNewShuffledHand($session);

        /** @var Hand $hand */
        $hand = $session->get('hand');

        $data = [
            'shuffle' => $hand->getDeck(),
        ];

        return $this->render('deckShuffle.html.twig', $data);
    }

    #[Route("card/deck/draw", name: "draw_init", methods: ['POST'])]
    public function initDraw(
        SessionInterface $session
    ): Response {
        $sessionHelper = new CardSessionHandler();
        $sessionHelper->setNewHand($session);

        return $this->redirectToRoute('draw_part2');
    }

    #[Route("card/deck/draw", name: "draw_part2", methods: ['GET'])]
    public function drawPart2(
        SessionInterface $session
    ): Response {
        $sessionHelper = new CardSessionHandler();

        $sessionHelper->ensureHandIsAvailable($session);

        /** @var Hand $hand */
        $hand = $session->get('hand');

        $data = [
            'card' => $hand->drawTopCard(),
            'message' => $hand->howManyLeft() - 1,
        ];

        $sessionHelper->setHandAfterUserDrewACard($session);

        return $this->render('deckDraw.html.twig', $data);
    }

    #[Route("card/deck/draw/:number", name: "callbackNumber", methods: ['POST'])]
    public function initCallback(
        Request $request,
        SessionInterface $session
    ): Response {
        $sessionHelper = new CardSessionHandler();

        $sessionHelper->ensureHandIsAvailable($session);

        /** @var int $drawThisManyCards*/
        $drawThisManyCards = (int) $request->request->get('num_cards');

        $sessionHelper->setAmountOfCardsToBeDrawn($session, $drawThisManyCards);

        return $this->redirectToRoute('deckNumber');
    }

    #[Route("card/deck/draw/:number", name: "deckNumber", methods: ['GET'])]
    public function init(
        SessionInterface $session
    ): Response {
        /** @var Hand $hand*/
        $hand = $session->get('hand');

        /** @var int $amount*/
        $amount = $session->get('amount');
        $card = [];

        if ($amount > 0) {
            for ($i = 0; $i < $amount; $i++) {
                $hand->drawAndDiscard();
            }
            $card = $hand->getDrawnByIndex($amount);
        }

        $data = [
            'card' => $card,
            'number' => $hand->howManyLeft(),
        ];
        $session->set('amount', 0);
        return $this->render('deckNumber.html.twig', $data);
    }
}
