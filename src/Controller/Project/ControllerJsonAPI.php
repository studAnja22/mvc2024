<?php

namespace App\Controller\Project;

use App\Project\CheckDb;
use App\Project\ApiHelper;
use App\Project\ApiFindByHelper;
use App\JsonHelper\JsonHelper;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use App\Entity\Choices;
use App\Repository\ChoicesRepository;
use App\Entity\Items;
use App\Repository\ItemsRepository;
use App\Entity\Path;
use App\Repository\PathRepository;
use App\Entity\Rooms;
use App\Repository\RoomsRepository;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class ControllerJsonAPI extends AbstractController
{
    #[Route("/proj/api", name: "api")]
    public function api(
        SessionInterface $projectSession
    ): Response {
        $projectSession->set('cheat', false);
        $paths = [
            'cheat' => $projectSession->get('cheat'),
        ];
        return $this->render('project/json.html.twig', $paths);
    }

    #[Route("/proj/api/rooms", name: "all_rooms", methods: ['GET'])]
    public function allRooms(
        RoomsRepository $roomsRepository,
    ): Response {
        /** @var ApiHelper $apiHelper helps sort objects into json friendly arrays  */
        $apiHelper = new ApiHelper();
        $rooms = $apiHelper->getAllRoomsData($roomsRepository);
        /** @var JsonHelper $jsonHelper helps formatting json responses */
        $jsonHelper = new JsonHelper();
        $responseJson = $jsonHelper->getJsonPrettyPrint($rooms);

        return $responseJson;
    }

    #[Route("/proj/api/paths", name: "all_paths", methods: ['GET'])]
    public function allPaths(
        PathRepository $pathRepository,
    ): Response {
        /** @var ApiHelper $apiHelper helps sort objects into json friendly arrays  */
        $apiHelper = new ApiHelper();
        $rooms = $apiHelper->getAllPathsData($pathRepository);
        /** @var JsonHelper $jsonHelper helps formatting json responses */
        $jsonHelper = new JsonHelper();
        $responseJson = $jsonHelper->getJsonPrettyPrint($rooms);

        return $responseJson;
    }

    #[Route("/proj/api/items", name: "all_items", methods: ['GET'])]
    public function allItems(
        ItemsRepository $itemsRepository,
    ): Response {
        /** @var ApiHelper $apiHelper helps sort objects into json friendly arrays  */
        $apiHelper = new ApiHelper();
        $rooms = $apiHelper->getAllItemsData($itemsRepository);
        /** @var JsonHelper $jsonHelper helps formatting json responses */
        $jsonHelper = new JsonHelper();
        $responseJson = $jsonHelper->getJsonPrettyPrint($rooms);

        return $responseJson;
    }

    #[Route("/proj/api/choices", name: "all_choices", methods: ['GET'])]
    public function allChoices(
        ChoicesRepository $choicesRepository,
    ): Response {
        /** @var ApiHelper $apiHelper helps sort objects into json friendly arrays  */
        $apiHelper = new ApiHelper();
        $rooms = $apiHelper->getAllChoicesData($choicesRepository);
        /** @var JsonHelper $jsonHelper helps formatting json responses */
        $jsonHelper = new JsonHelper();
        $responseJson = $jsonHelper->getJsonPrettyPrint($rooms);

        return $responseJson;
    }

    #[Route("/proj/api/room/", name: "post_this_room", methods: ['POST'])]
    public function postRoom(
        ChoicesRepository $choicesRepository,
        PathRepository $pathRepository,
        RoomsRepository $roomsRepository,
        Request $requestJson
    ): Response {
        /** @var ApiFindByHelper $ApiFindByHelper helps sort objects into json friendly arrays  */
        $apiHelper = new ApiFindByHelper();
        /** @var int $roomNumber */
        $roomNumber = $requestJson->request->getInt('room_number_post');
        $roomData = [
            'room_id' => $roomNumber,
            'room' => $apiHelper->getRoomsData($roomNumber, $roomsRepository),
            'paths' => $apiHelper->getPathData($roomNumber, $pathRepository),
            'choices' => $apiHelper->getChoiceData($roomNumber, $choicesRepository),
        ];

        $jsonHelper = new JsonHelper();
        $responseJson = $jsonHelper->getJsonPrettyPrint($roomData);
        return $responseJson;
    }

    #[Route("/proj/api/room/:number", name: "see_this_room", methods: ['POST'])]
    public function prgPostRoom(
        SessionInterface $sessionProjectJson,
        Request $requestJson
    ): Response {
        /** @var int $roomNumber */
        $roomNumber = $requestJson->request->getInt('room_number_prg');
        $sessionProjectJson->set('show_room', $roomNumber);

        return $this->redirectToRoute('get_this_room', ['number' => $roomNumber]);
    }

    #[Route("/proj/api/room/{number}", name: "get_this_room", methods: ['GET'])]
    public function prgGetRoom(
        ChoicesRepository $choicesRepository,
        PathRepository $pathRepository,
        RoomsRepository $roomsRepository,
        SessionInterface $sessionProjectJson
    ): Response {
        /** @var ApiFindByHelper $ApiFindByHelper helps sort objects into json friendly arrays  */
        $apiHelper = new ApiFindByHelper();

        $roomNumber = $sessionProjectJson->get('show_room');

        $roomData = [
            'room_id' => $roomNumber,
            'room' => $apiHelper->getRoomsData($roomNumber, $roomsRepository),
            'paths' => $apiHelper->getPathData($roomNumber, $pathRepository),
            'choices' => $apiHelper->getChoiceData($roomNumber, $choicesRepository),
        ];

        $jsonHelper = new JsonHelper();
        $responseJson = $jsonHelper->getJsonPrettyPrint($roomData);
        return $responseJson;
    }

    #[Route("/proj/api/check/session", name: "adventure_session", methods: ['GET'])]
    public function checkSession(
        ChoicesRepository $choicesRepository,
        PathRepository $pathRepository,
        RoomsRepository $roomsRepository,
        SessionInterface $projectSession,
    ): Response {
        /** @var ApiFindByHelper $ApiFindByHelper helps sort objects into json friendly arrays  */
        $apiHelper = new ApiFindByHelper();
        /** @var int $roomNumber represents the current room in session */
        $roomNumber = $projectSession->get('room');
        /** @var array $paths Holds data needed to render HTML pages */
        $paths = [
            'paths' => $apiHelper->getPathData($roomNumber, $pathRepository),
            'room' => $apiHelper->getRoomsData($roomNumber, $roomsRepository),
            'items' => $apiHelper->getItemsData($projectSession),
            'choices' => $apiHelper->getChoiceData($roomNumber, $choicesRepository),
            'roomNumber' => $projectSession->get('room'),
            'inventory' => $projectSession->get('inventory'),
            'interaction' => $projectSession->get('interact'),
            'cheat' => $projectSession->get('cheat'),
            'keyLimePie' => !array_diff(['key', 'lime', 'pie'], $projectSession->get('inventory'))
        ];

        $jsonHelper = new JsonHelper();
        $responseJson = $jsonHelper->getJsonPrettyPrint($paths);

        return $responseJson;
    }
}
