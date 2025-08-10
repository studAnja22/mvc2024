<?php

namespace App\Project;

class PathData
{
    /**
     *  @return list<array{from_room:int,direction:string,to_room:int,required_item:string|null}>
     */
    public static function getPathData(): array {
        return [
            ['from_room' => 1, 'direction' => 'North', 'to_room' => 2, 'required_item' => null],
            ['from_room' => 1, 'direction' => 'East', 'to_room' => 3, 'required_item' => null],
            ['from_room' => 1, 'direction' => 'West', 'to_room' => 4, 'required_item' => 'key'],

            ['from_room' => 2, 'direction' => 'North East', 'to_room' => 3, 'required_item' => null],
            ['from_room' => 2, 'direction' => 'South', 'to_room' => 1, 'required_item' => null],

            ['from_room' => 3, 'direction' => 'South West', 'to_room' => 5, 'required_item' => 'pie'],
            ['from_room' => 3, 'direction' => 'West', 'to_room' => 1, 'required_item' => null],
            ['from_room' => 3, 'direction' => 'North West', 'to_room' => 2, 'required_item' => null],

            ['from_room' => 4, 'direction' => 'South East', 'to_room' => 5, 'required_item' => null],
            ['from_room' => 4, 'direction' => 'East', 'to_room' => 1, 'required_item' => null],
        ];
    }
}