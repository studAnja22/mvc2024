<?php

namespace App\Repository;

use App\Entity\Choices;
use App\Repository\ChoicesRepository;
use App\Entity\Items;
use App\Repository\ItemsRepository;
use App\Entity\Path;
use App\Repository\PathRepository;
use App\Entity\Rooms;
use App\Repository\RoomsRepository;
// use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{
    /**
     * This test Choices entity and repository
     */
    public function testChoices(): void
    {
        // $mockDoctrine = $this->createMock(ManagerRegistry::class);
        $mockChoices = $this->createMock(Choices::class);
        $mockChoicesRepo = $this->createMock(ChoicesRepository::class);
        $mockChoicesData = [
            'room' => 1,
            'choice' => 'Unittest this!',
            'item' => 'wizard wand',
            'dialogue' => 'Somehow it worked',
            'require' => null
        ];
        $mockChoicesRepo->method('findAll')->willReturn($mockChoicesData);
        $res = $mockChoicesRepo->findAll();

        $mockChoices->method('getRoom')->willReturn(1);
        $mockChoices->method('getChoice')->willReturn('Unittest this!');
        $mockChoices->method('getItem')->willReturn('wizard wand');
        $mockChoices->method('getDialogue')->willReturn('Somehow it worked');

        $this->assertEquals($mockChoicesData, $res);
        $this->assertEquals('Unittest this!', $mockChoices->getChoice());
        $this->assertEquals('wizard wand', $mockChoices->getItem());
    }
    /**
     * This test Path entity and repository
     */
    public function testPath(): void
    {
        // $mockDoctrine = $this->createMock(ManagerRegistry::class);
        $mockPath = $this->createMock(Path::class);
        $mockPathRepo = $this->createMock(PathRepository::class);
        $mockPathData = [
            'from_room' => 1,
            'direction' => 'North',
            'to_room' => 2,
            'required_item' => 'rubber duck',
        ];
        $mockPathRepo->method('findAll')->willReturn($mockPathData);
        $res = $mockPathRepo->findAll();

        $mockPath->method('getFromRoom')->willReturn(1);
        $mockPath->method('getDirection')->willReturn('North');
        $mockPath->method('getToRoom')->willReturn(2);
        $mockPath->method('getRequiredItem')->willReturn('rubber duck');

        $this->assertEquals($mockPathData, $res);
        $this->assertEquals(1, $mockPath->getFromRoom());
        $this->assertEquals(2, $mockPath->getToRoom());
        $this->assertEquals('North', $mockPath->getDirection());
        $this->assertEquals('rubber duck', $mockPath->getRequiredItem());
    }
    /**
     * This test Rooms entity and repository
     */
    public function testRooms(): void
    {
        // $mockDoctrine = $this->createMock(ManagerRegistry::class);
        $mockRooms = $this->createMock(Rooms::class);
        $mockRoomsRepo = $this->createMock(RoomsRepository::class);
        $mockRoomsData = [
            'name' => 'Test room',
            'description' => 'What is it like in a mock test?',
        ];
        $mockRoomsRepo->method('findAll')->willReturn($mockRoomsData);
        $res = $mockRoomsRepo->findAll();

        $mockRooms->method('getName')->willReturn('Test room');
        $mockRooms->method('getDescription')->willReturn('What is it like in a mock test?');

        $this->assertEquals($mockRoomsData, $res);
        $this->assertEquals('Test room', $mockRooms->getName());
        $this->assertEquals('What is it like in a mock test?', $mockRooms->getDescription());
    }
    /**
     * This test Items entity and repository
     */
    public function testItems(): void
    {
        // $mockDoctrine = $this->createMock(ManagerRegistry::class);
        $mockItems = $this->createMock(Items::class);
        $mockItemsRepo = $this->createMock(ItemsRepository::class);
        $mockItemsData = [
            'name' => 'key',
            'description' => 'rusty key',
        ];
        $mockItemsRepo->method('findAll')->willReturn($mockItemsData);
        $res = $mockItemsRepo->findAll();

        $mockItems->method('getName')->willReturn('key');
        $mockItems->method('getDescription')->willReturn('rusty key');

        $this->assertEquals($mockItemsData, $res);
        $this->assertEquals('key', $mockItems->getName());
        $this->assertEquals('rusty key', $mockItems->getDescription());
    }
}
