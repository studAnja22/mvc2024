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

class ApiHelper
{
    /**
     * @return array $allRooms
     */
    public function getAllRoomsData(
        RoomsRepository $roomsRepository,
    ): array {
        /** @var RoomsRepository $item */
        $rooms = $roomsRepository->findAll();
        $allRooms = [];

        foreach ($rooms as $room) {
            $data = [
            'room_number' => $room->getId(),
            'name' => $room->getName(),
            'description' => $room->getDescription()
            ];
            array_push($allRooms, $data);
        }

        return $allRooms;
    }
    /**
     * @return array $allPaths
     */
    public function getAllPathsData(
        PathRepository $pathRepository,
    ): array {
        /** @var PathRepository $item */
        $path = $pathRepository->findAll();
        $allPaths = [];

        foreach ($path as $p) {
            $data = [
            'from_room' => $p->getFromRoom(),
                'direction' => $p->getDirection(),
                'to_room' => $p->getToRoom(),
                'required_item' => $p->getRequiredItem()
            ];
            array_push($allPaths, $data);
        }

        return $allPaths;
    }
    /**
     * @return array $allItems
     */
    public function getAllItemsData(
        ItemsRepository $itemsRepository,
    ): array {
        /** @var ItemsRepository $item */
        $item = $itemsRepository->findAll();
        $allItems = [];

        foreach ($item as $i) {
            $data = [
                'name' => $i->getName(),
                'description' => $i->getDescription(),
            ];
            array_push($allItems, $data);
        }

        return $allItems;
    }
    /**
     * @return array $allChoices
     */
    public function getAllChoicesData(
        ChoicesRepository $choicesRepository,
    ): array {
        /** @var ChoicesRepository $item */
        $choices = $choicesRepository->findAll();
        $allChoices = [];

        foreach ($choices as $c) {
            $data = [
                'room' => $c->getRoom(),
                'choice' => $c->getChoice(),
                'item' => $c->getItem(),
                'dialogue' => $c->getDialogue(),
                'require' => $c->getRequire()
            ];
            array_push($allChoices, $data);
        }

        return $allChoices;
    }
}