<?php

namespace App\Project;

use App\Entity\Choices;
use App\Repository\ChoicesRepository;
use App\Entity\Items;
use App\Repository\ItemsRepository;
use App\Entity\Path;
use App\Repository\PathRepository;
use App\Entity\Rooms;
use App\Repository\RoomsRepository;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\Persistence\ManagerRegistry;

class ApiFindByHelper
{
    //----- Get everything from a room ------
    /**
     * @return array $data all paths you can take from one room
     */
    public function getPathData(
        int $roomNumber,
        PathRepository $pathRepository,
    ): array {
        $pathsData = $pathRepository->findBy(['fromRoom' => $roomNumber]);
        $data = [];
        foreach ($pathsData as $p) {
            $path = [
                'from_room' => $p->getFromRoom(),
                'direction' => $p->getDirection(),
                'to_room' => $p->getToRoom(),
                'required_item' => $p->getRequiredItem()
            ];
            array_push($data, $path);
        }
        return $data;
    }
    /**
     * @return array $data all choices in one room
     */
    public function getChoiceData(
        int $roomNumber,
        ChoicesRepository $choicesRepository,
    ): array {
        $choiceData = $choicesRepository->findBy(['room' => $roomNumber]);
        $data = [];
        foreach ($choiceData as $c) {
            $choice = [
                'room' => $c->getRoom(),
                'choice' => $c->getChoice(),
                'item' => $c->getItem(),
                'dialogue' => $c->getDialogue(),
                'require' => $c->getRequire()
            ];
            array_push($data, $choice);
        }
        return $data;
    }
    /**
     * @return array $data one rooms data
     */
    public function getRoomsData(
        int $roomNumber,
        RoomsRepository $roomsRepository,
    ): array {
        $roomData = $roomsRepository->findOneBy(['id' => $roomNumber]);
        $data = [
            'name' => $roomData->getName(),
            'description' => $roomData->getDescription()
        ];
        return $data;
    }
    /**
     * @return array $data items in the session backpack
     */
    public function getItemsData(
        SessionInterface $projectSession
    ): array {
        $itemsData = $projectSession->get('backpack');
        $data = [];
        foreach ($itemsData as $i) {
            $items = [
                'name' => $i->getName(),
                'description' => $i->getDescription(),
            ];
            array_push($data, $items);
        }
        return $data;
    }
}