<?php

namespace App\SessionHandlers;

use App\Cards\Cards;
use App\Cards\Games;
use App\Cards\Hand;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CardSessionJsonHandler
{
    /**
     * @return array<string|null> $json
     */
    public function getDrawnCardsByName(SessionInterface $sessionJson): array
    {
        /** @var Hand $deck */
        $deck = $sessionJson->get('deck');
        /** @var int $amount */
        $amount = $sessionJson->get('amount');

        $json = array();
        $card = $deck->getDrawnByIndex($amount);

        foreach ($card as $card) {
            array_push($json, $card->getName());
        }

        return $json;
    }
    public function setSessionJsonAmountDrawCards(SessionInterface $sessionJson): void
    {
        /** @var Hand $deck */
        $deck = $sessionJson->get('deck');
        /** @var int $drawThisManyCards */
        $drawThisManyCards = $sessionJson->get('amount');

        /** @var int $cardsLeft */
        $cardsLeft = $deck->howManyLeft();

        if ($cardsLeft < $drawThisManyCards) {
            $sessionJson->set('amount', $cardsLeft);
        }
    }
    public function setSessionJsonDeckDrawCards(SessionInterface $sessionJson): void
    {
        /** @var Hand $deck */
        $deck = $sessionJson->get('deck');

        for ($x = 0; $x < $sessionJson->get('amount'); $x++) {
            $deck->drawAndDiscard();
        }
        $sessionJson->set('deck', $deck);
    }
}
