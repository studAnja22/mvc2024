<?php

namespace App\Controller\Project;

use App\Project\ChoicesHelper;
use App\Project\ItemsHelper;
use App\Project\PathHelper;
use App\Project\RoomsHelper;

use App\Project\CheckDb;

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
    /**
     * Landing page of the project.
     * If we have no session we set the default session values.
     * We also check that the db tables aren't empty.
     * $paths holds all the data we need to render the html pages.
     */
    #[Route("/proj", name: "project", methods: ['GET'] )]
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
            $projectSession->set('cheat', false);
            $checkDb->checkAllDb($choicesRepository, $itemsRepository, $pathRepository, $roomsRepository, $doctrine);
        }
        /** @var array $paths Holds data needed to render HTML pages */
        $paths = [
            'paths' => $pathRepository->findBy(['fromRoom' => $projectSession->get('room')]),
            'room' => $roomsRepository->findOneBy(['id' => $projectSession->get('room')]),
            'items' => $projectSession->get('backpack'),
            'choices' => $choicesRepository->findBy(['room' => $projectSession->get('room')]),
            'roomNumber' => $projectSession->get('room'),
            'inventory' => $projectSession->get('inventory'),
            'interaction' => $projectSession->get('interact'),
            'cheat' => $projectSession->get('cheat'),
            'keyLimePie' => !array_diff(['key', 'lime', 'pie'], $projectSession->get('inventory'))
        ];

        return $this->render('project/project.html.twig', $paths);
    }
    /**
     * Here we clear the session data and set the default values for a new game.
     */
    #[Route("/proj/reset", name: "reset", methods: ['POST'])]
    public function init(
        SessionInterface $projectSession
    ): Response {
        $projectSession->clear();
        $projectSession->set('room', 1);
        $projectSession->set('backpack', []);
        $projectSession->set('inventory', []);
        $projectSession->set('interact', "");
        $projectSession->set('cheat', false);

        return $this->redirectToRoute('project');
    }
    /**
     * This moves the user to a new room
     */
    #[Route("/proj/move", name: "move", methods: ['POST'])]
    public function move(
        Request $request,
        SessionInterface $projectSession
    ): Response {
        $goToRoom = (int) $request->request->get('to_room');
        $projectSession->set('room', $goToRoom);
        $projectSession->set('interact', "");

        return $this->redirectToRoute('project');
    }
    /**
     * Whenever a user selects an option the dialogue text will update,
     * if the option yields an item it is added to the session backpack if it isn't there already.
     */
    #[Route("/proj/interact", name: "interact", methods: ['POST'])]
    public function interact(
        ItemsRepository $itemsRepository,
        ChoicesRepository $choicesRepository,
        Request $request,
        SessionInterface $projectSession
    ): Response {
        $backpack = $projectSession->get('backpack');
        $inventory = $projectSession->get('inventory');

        /** @var string $choice The choice option user picked */
        $choice = (string) $request->request->get('item');

        /** @var Items|null $item */
        $item = $itemsRepository->findOneBy(['name' => $choice]);

        /** @var Choices|null $dialogue */
        $dialogue = $choicesRepository->findOneBy([
            'item' => $choice,
            'room' => $projectSession->get('room')
        ]);

        if ($dialogue) {
            $projectSession->set('interact', $dialogue->getDialogue());
        }

        if ($item) {
            /** @var string $itemName - items name */
            $itemName = $item->getName();
        }
        /**
         * If the item is valid and it isn't in the backpack:
         * We add it to the backpack (objects) and inventory (item name). 
         * We document it has been added in the session so we wont add duplicates.
         */
        if ($item && !$projectSession->has($itemName)) {
            $backpack[] = $item;
            $inventory[] = $item->getName();
            $projectSession->set('backpack', $backpack);
            $projectSession->set('inventory', $inventory);
            $projectSession->set($itemName, true);//ensures we don't add duplicates to the backpack
        }

        return $this->redirectToRoute('project');
    }
    /** This enables a div with a cheat sheet in the navbar
     * It also enable the user to see room number and what room the paths lead.
     */
    #[Route("/proj/cheat", name: "cheat")]
    public function cheat(
        SessionInterface $projectSession
    ): Response {
        if ($projectSession->get('cheat') == false) {
            $projectSession->set('cheat', true);
            return $this->redirectToRoute('project');
        }
        $projectSession->set('cheat', false);
        return $this->redirectToRoute('project');
    }

    #[Route("/proj/about", name: "about", methods: ['GET'] )]
    public function about(
        SessionInterface $projectSession,
    ): Response
    {
        $projectSession->set('cheat', false);
        $paths = [
            'cheat' => $projectSession->get('cheat'),
        ];
        return $this->render('project/about.html.twig', $paths);
    }

    #[Route("/proj/about/database", name: "database", methods: ['GET'] )]
    public function database(
        SessionInterface $projectSession,
    ): Response
    {
        $projectSession->set('cheat', false);
        $paths = [
            'cheat' => $projectSession->get('cheat'),
        ];
        return $this->render('project/database.html.twig', $paths);
    }
}
