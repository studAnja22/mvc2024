<?php

namespace App\Controller;

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

    #[Route("/lucky/number/twig", name: "lucky_number")]
    public function number(): Response
    {
        $number = random_int(0, 100);
        $pictures = array('img/cat.jpg', 'img/cake.jpg', 'img/sparrow.jpg', 'img/webapp.jpg', 'img/tinyheader.jpg');
        $quotes = array('"Be silent or let thy words be worth more than silence" - Pythagoras',
        '"Do not say a little in many words, but a great deal in few!"- Pythagoras',
        '"The only person with whom you have to compare ourselves, is that you in the past. 
        And the only person better you should be, this is who you are now" - Sigmund Freud',
        '"From error to error, one discovers the entire truth" - Sigmund Freud',
        '"The journey of a thousand miles begins with a single step" - Lao Tzu',
        '"He who knows all the answers has not been asked all the questions" - Confucius',);
        $data = [
            'number' => $number,
            'pictures' => $pictures[random_int(0, 4)],
            'quotes' => $quotes[random_int(0, 5)],
        ];

        return $this->render('lucky_number.html.twig', $data);
    }
}
