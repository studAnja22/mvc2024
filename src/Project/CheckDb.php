<?php

namespace App\Project;

use App\Project\ChoicesHelper;
use App\Project\ItemsHelper;
use App\Project\PathHelper;
use App\Project\RoomsHelper;

use App\Repository\ChoicesRepository;
use App\Repository\ItemsRepository;
use App\Repository\PathRepository;
use App\Repository\RoomsRepository;

use Doctrine\Persistence\ManagerRegistry;

class CheckDb
{
    /**
     * Checks if all the tables in the db are empty and fills it if they are.
     * 
     * @param ChoicesRepository $choicesRepository
     * @param ItemsRepository $itemsRepository
     * @param PathRepository $pathRepository
     * @param RoomsRepository $roomsRepository
     * @param ManagerRegistry $doctrine
     *  @return void
     */
    public function checkAllDb(
        ChoicesRepository $choicesRepository,
        ItemsRepository $itemsRepository,
        PathRepository $pathRepository,
        RoomsRepository $roomsRepository,
        ManagerRegistry $doctrine
    ): void {
        $this->checkChoiceDb($choicesRepository,$doctrine);
        $this->checkPathDb($pathRepository,$doctrine);
        $this->checkItemsDb($itemsRepository,$doctrine);
        $this->checkRoomDb($roomsRepository,$doctrine);
    }
    /**
     * Checks if the choices table is empty and fills it if it is.
     * 
     * @param ChoicesRepository $choicesRepository
     * @param ManagerRegistry $doctrine
     *  @return void
     */
    public function checkChoiceDb(
        ChoicesRepository $choicesRepository,
        ManagerRegistry $doctrine
    ): void {
        if ($choicesRepository->count([]) === 0) {
            $choices = new ChoicesHelper();
            $choices->createChoices($doctrine);
        }
    }
    /**
     * Checks if the items table is empty and fills it if it is.
     * 
     * @param ItemsRepository $itemsRepository
     * @param ManagerRegistry $doctrine
     *  @return void
     */
    public function checkItemsDb(
        ItemsRepository $itemsRepository,
        ManagerRegistry $doctrine
    ): void {
        if ($itemsRepository->count([]) === 0) {
            $items = new ItemsHelper();
            $items->createItems($doctrine);
        }
    }
    /**
     * Checks if the path table is empty and fills it if it is.
     * 
     * @param PathRepository $pathRepository
     * @param ManagerRegistry $doctrine
     *  @return void
     */
    public function checkPathDb(
        PathRepository $pathRepository,
        ManagerRegistry $doctrine
    ): void {
        if ($pathRepository->count([]) === 0) {
            $path = new PathHelper();
            $path->createPaths($doctrine);
        }
    }

    /**
     * Checks if the rooms table is empty and fills it if it is.
     * 
     * @param RoomsRepository $roomRepository
     * @param ManagerRegistry $doctrine
     *  @return void
     */
    public function checkRoomDb(
        RoomsRepository $roomRepository,
        ManagerRegistry $doctrine
    ): void {
        if ($roomRepository->count([]) === 0) {
            $room = new RoomsHelper();
            $room->createRooms($doctrine);
        }
    }
}