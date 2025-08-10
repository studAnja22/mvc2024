<?php

namespace App\Project;

use App\Entity\Rooms;
use App\Project\RoomsData;
use App\Repository\RoomsRepository;
use Doctrine\Persistence\ManagerRegistry;

class RoomsHelper
{
    public function createRooms(
        ManagerRegistry $doctrine
    ): void {
        $entityManager = $doctrine->getManager();
        
        /** @var array<string,mixed> $roomData */
        $roomData = RoomsData::getRoomData();

        foreach ($roomData as $row) {
            /** @var array{name:string,description:string} $row*/
            /** @var Rooms $room */
            $room = new Rooms();
            $room->setName($row['name']);
            $room->setDescription($row['description']);

            $entityManager->persist($room);
            $entityManager->flush();
        }
    }
}