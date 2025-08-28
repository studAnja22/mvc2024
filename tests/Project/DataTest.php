<?php

namespace App\Project;

use PHPUnit\Framework\TestCase;

/**
 * Tests we get the data in the arrays meant for the database tables.
 */
class DataTest extends TestCase
{
    public function testChoicesData(): void
    {
        /** @var array<string,mixed> $choicesData */
        $choicesData = ChoicesData::getChoicesData();

        $this->assertNotEmpty($choicesData);
        $this->assertIsArray($choicesData);
        $this->assertArrayHasKey('choice', $choicesData[0]);
        $this->assertArrayHasKey('dialogue', $choicesData[0]);
        $this->assertArrayHasKey('item', $choicesData[0]);
        $this->assertEquals('stick', $choicesData[0]['item']);
        $this->assertEquals('key', $choicesData[1]['item']);
    }
    public function testPathData(): void
    {
        /** @var array<string,mixed> $pathData */
        $pathData = PathData::getPathData();

        $this->assertNotEmpty($pathData);
        $this->assertIsArray($pathData);
        $this->assertArrayHasKey('from_room', $pathData[0]);
        $this->assertArrayHasKey('to_room', $pathData[0]);
        $this->assertArrayHasKey('required_item', $pathData[0]);
        $this->assertEquals(1, $pathData[0]['from_room']);
        $this->assertEquals('key', $pathData[2]['required_item']);
    }
    public function testRoomsData(): void
    {
        /** @var array<string,mixed> $roomsData */
        $roomsData = RoomsData::getRoomData();

        $this->assertNotEmpty($roomsData);
        $this->assertIsArray($roomsData);
        $this->assertArrayHasKey('name', $roomsData[0]);
        $this->assertArrayHasKey('description', $roomsData[0]);
        $this->assertEquals('enchanted autumn', $roomsData[0]['name']);
        $this->assertEquals('You walk up to the wizards hut. He looks at you with kind eyes.', $roomsData[1]['description']);
    }
    public function testItemsData(): void
    {
        /** @var array<string,mixed> $itemsData */
        $itemsData = ItemsData::getItemsData();

        $this->assertNotEmpty($itemsData);
        $this->assertIsArray($itemsData);
        $this->assertArrayHasKey('name', $itemsData[0]);
        $this->assertArrayHasKey('description', $itemsData[0]);
        $this->assertEquals('key', $itemsData[0]['name']);
        $this->assertEquals('a magical key. grants access to the flower fields in the west.', $itemsData[0]['description']);
    }
}
