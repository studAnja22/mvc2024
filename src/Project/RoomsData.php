<?php

namespace App\Project;

class RoomsData
{
    /**
     *  @return list<array{name:string,description:string}>
     */
    public static function getRoomData(): array {
        return [
            ['name' => 'enchanted autumn', 'description' => "Upon entering the enchanted autumn forest you can hear the birds chirping. "],//Room id: 1
            ['name' => 'wizards muschroom hut', 'description' => 'You walk up to the wizards hut. He looks at you with kind eyes.'],//Room id: 2
            ['name' => 'fluffy wolves clearing', 'description' => 'You follow the path to the clearing. The fluffy wolf looks at you expectantly...'],//Room id: 3
            ['name' => 'wavyleaf sea lavender field', 'description' => 'The smell of the magically enchanted wavyleaf sea lavender fills the air, filling your heart with joy.'],//Room id: 4
            ['name' => 'heart of the forest', 'description' => 'The sound of your friends calling your name draws you closer to the heart of the forest.'],//Room id: 5
        ];
    }
}