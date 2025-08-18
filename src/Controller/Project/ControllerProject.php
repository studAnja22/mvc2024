<?php

namespace App\Controller\Project;

use App\Project\ChoicesHelper;
use App\Project\ItemsHelper;
use App\Project\PathHelper;
use App\Project\RoomsHelper;

use App\Project\CheckDb;

use App\SessionHandlers\CardSessionHandler;
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

class ControllerProject extends AbstractController
{
    #[Route("/project", name: "project", methods: ['GET'] )]
    public function main(
        CheckDb $checkDb,
        ChoicesRepository $choicesRepository,
        ItemsRepository $itemsRepository,
        PathRepository $pathRepository,
        RoomsRepository $roomsRepository,
        SessionInterface $projectSession,
        ManagerRegistry $doctrine
    ): Response
    {
        if (!$projectSession->has('room')) {
            $projectSession->set('room', 1);//Starter room.
            $projectSession->set('backpack', []);
            $projectSession->set('inventory', []);
            $projectSession->set('interact', "");
            $checkDb->checkAllDb($choicesRepository, $itemsRepository, $pathRepository, $roomsRepository, $doctrine);
        }

        $paths = [
            'paths' => $pathRepository->findBy(['fromRoom' => $projectSession->get('room')]),
            'room' => $roomsRepository->findOneBy(['id' => $projectSession->get('room')]),
            'items' => $projectSession->get('backpack'),
            'choices' => $choicesRepository->findBy(['room' => $projectSession->get('room')]),
            'roomNumber' => $projectSession->get('room'),
            'inventory' => $projectSession->get('inventory'),
            'interaction' => $projectSession->get('interact'),
            'keyLimePie' => !array_diff(['key', 'lime', 'pie'], $projectSession->get('inventory'))
        ];

        return $this->render('project/project.html.twig', $paths);
    }

    #[Route("/project/reset", name: "reset", methods: ['POST'])]
    public function init(
        SessionInterface $projectSession
    ): Response {
        $projectSession->clear();
        $projectSession->set('room', 1);
        $projectSession->set('backpack', []);
        $projectSession->set('inventory', []);
        $projectSession->set('interact', "");

        return $this->redirectToRoute('project');
    }

    #[Route("/project/move", name: "move", methods: ['POST'])]
    public function move(
        Request $request,
        SessionInterface $projectSession
    ): Response {
        $goToRoom = (int) $request->request->get('to_room');
        $projectSession->set('room', $goToRoom);
        $projectSession->set('interact', "");

        return $this->redirectToRoute('project');
    }

    #[Route("/project/interact", name: "interact", methods: ['POST'])]
    public function interact(
        ItemsRepository $itemsRepository,
        ChoicesRepository $choicesRepository,
        Request $request,
        SessionInterface $projectSession
    ): Response {
        $backpack = $projectSession->get('backpack');
        $inventory = $projectSession->get('inventory');

        $choice = (string) $request->request->get('item');
        $item = $itemsRepository->findOneBy(['name' => $choice]);
        $dialogue = $choicesRepository->findOneBy([
            'item' => $choice,
            'room' => $projectSession->get('room')
        ]);

        $projectSession->set('interact', $dialogue->getDialogue());

        if ($item) {
            $itemName = $item->getName();
        }

        if ($item && !$projectSession->has($itemName)) {
            $backpack[] = $item;
            $inventory[] = $item->getName();
            $projectSession->set('backpack', $backpack);
            $projectSession->set('inventory', $inventory);
            $projectSession->set($itemName, true);//ensures we don't add duplicates to the backpack
        }

        return $this->redirectToRoute('project');
    }
}
