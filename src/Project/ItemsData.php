<?php

namespace App\Project;

class ItemsData
{
    /**
     *  @return list<array{name:string,description:string}>
     */
    public static function getItemsData(): array {
        return [
            ['name' => 'key', 'description' => 'a magical key. grants access to the flower fields in the west.'],
            ['name' => 'lime', 'description' => 'the fresh, bright green lime has a tangy scent.'],
            ['name' => 'pie', 'description' => 'still warm. You can smell it from miles away.'],
            ['name' => 'stick', 'description' => 'a funny looking twig.'],
            ['name' => 'rock', 'description' => 'my new favorite pet. I call him mossy.'],
            ['name' => 'flowers', 'description' => 'vibrant blue. my friends will love this.'],
        ];
    }
}