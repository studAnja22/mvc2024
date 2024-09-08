<?php

namespace App\Controller;

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

        $responseJson = new JsonResponse($data);
        $responseJson->setEncodingOptions(
            $responseJson->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $responseJson;
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

        $responseJson = new JsonResponse($data);
        $responseJson->setEncodingOptions(
            $responseJson->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $responseJson;
    }
}
