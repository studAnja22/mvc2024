<?php

namespace App\SessionHandlers;

use App\Cards\Cards;
use App\Cards\Games;
use App\Cards\Hand;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CardSessionHandler
{
    /**
     * Clears the session and sets a brand new Hand object.
     * (A new, in order deck of cards)
     */
    public function setNewHand(SessionInterface $session): void
    {
        $session->clear();

        /** @var Hand $hand */
        $hand = new Hand();
        $session->set('hand', $hand);
    }
    /**
     * Clears session. Sets a new shuffled Hand() object to session.
     */
    public function setNewShuffledHand(SessionInterface $session): void
    {
        $session->clear();

        /** @var Hand $hand */
        $hand = new Hand();
        $hand->shuffle();
        $session->set('hand', $hand);
    }
    /**
     * Checks if there's session 'hand' exists and if there's enough cards to draw.
     * If not - it uses setNewHand() to create a new Hand() object.
     */
    public function ensureHandIsAvailable(SessionInterface $session): void
    {
        /** @var Hand $hand */
        $hand = $session->get('hand');

        if ($hand == null || $hand->howManyLeft() == 0) {
            $this->setNewHand($session);
        }
    }
    /**
     * User drew a card, save the change to 'hand' session.
     */
    public function setHandAfterUserDrewACard(SessionInterface $session): void
    {
        /** @var Hand $hand */
        $hand = $session->get('hand');
        $hand->removeTopCard();
        $session->set('hand', $hand);
    }
    /**
     * User wants to draw many cards.
     * The function ensures that user cant draw more cards than what's available.
     */
    public function setAmountOfCardsToBeDrawn(
        SessionInterface $session,
        int $drawThisManyCards
    ): void {
        /** @var Hand $hand*/
        $hand = $session->get('hand');

        if ($hand->howManyLeft() < $drawThisManyCards) {
            $drawThisManyCards = $hand->howManyLeft();
        }

        $session->set('amount', $drawThisManyCards);
    }
}
