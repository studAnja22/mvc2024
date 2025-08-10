<?php

namespace App\Project;

class ChoicesData
{
    /**
     *  @return list<array{room:int,choice:string,item:string,dialogue:string,require:string|null}>
     */
    public static function getChoicesData(): array {
        return [
            ['room' => 1, 'choice' => 'Search the area', 'item' => 'stick', "dialogue" => 'You found a cool stick! Lovely!', 'require' => null],
            ['room' => 1, 'choice' => 'Use the stick to poke around the leaves', 'item' => 'key', "dialogue" => 'Oh! You found the key you dropped earlier. Now you can travel to the flower fields if you go West.', 'require' => 'stick'],
            ['room' => 1, 'choice' => 'Check the map', 'item' => 'map', "dialogue" => 'the map says: up North lives my wizard friend, going East or West will eventually lead me South to where my friends are.', 'require' => null],

            ['room' => 2, 'choice' => 'Greet your friend', 'item' => 'hello', "dialogue" => 'You smile and wave at the wizard. He looks happy and waves his glowing stick.', 'require' => null],
            ['room' => 2, 'choice' => 'Ask the wizard for help', 'item' => 'pie', "dialogue" => 'The wizard smiles at you. "If you have this, the wolf will let you pass. He will know that I am baking him a lovely pie too.". He hands you a freshly baked pie.', 'require' => null],
            ['room' => 2, 'choice' => 'Ask the wizard to join you!', 'item' => 'join', "dialogue" => 'You tell the wizard about how you are going to spend time with your friends. You ask him to join you. He says "Today I will bake lots of yummy treats, so I have to stay here. Ask me another time. Hope you have fun today!"', 'require' => null],
            ['room' => 2, 'choice' => 'Check the map', 'item' => 'map', "dialogue" => 'the map says: North east is where the wolf lives. South will take me back to where I started.', 'require' => null],
            ['room' => 2, 'choice' => 'Show the wizard the cool stick you found', 'item' => 'glow', "dialogue" => 'You wave your cool stick, drawing a happy face in the air. The wizard smiles at you and nods. "You have found the stick of truthiness! :)"', 'require' => 'stick'],
            ['room' => 2, 'choice' => 'Show the wizard the key', 'item' => 'show', "dialogue" => 'The wizard looks at the key and says "That key opens the path to the flower fields. I am sure your friends would like some flowers." ', 'require' => 'key'],

            ['room' => 3, 'choice' => 'Say hello to the wolf', 'item' => 'wolf', "dialogue" => 'The wolf beholds you with unwavering eyes. He seems to want something.', 'require' => null],
            ['room' => 3, 'choice' => 'Check the map', 'item' => 'map', "dialogue" => 'the map says: South West will lead me to my friends. Going West from here will take me back to where I started. North West is where my wizard friend lives, I can smell his baked goods from here.', 'require' => null],
            ['room' => 3, 'choice' => 'Wave the stick!', 'item' => 'wave', "dialogue" => 'You wave the stick at the wolf. The wolf does not move.', 'require' => 'stick'],
            ['room' => 3, 'choice' => 'Give the wolf a piece of pie', 'item' => 'lime', "dialogue" => 'The wolf is very delighted to have a treat! He says "Take this lime and make a key lime pie - if you can :)"', 'require' => 'pie'],

            ['room' => 4, 'choice' => 'Search the area', 'item' => 'rock', "dialogue" => 'You found a rock. You draw a face on it.', 'require' => null],
            ['room' => 4, 'choice' => 'Pick some flowers', 'item' => 'flowers', "dialogue" => 'The flowers are truly beautiful. Your friends will surely smile when they see them. You add a some flowers to your backpack. ', 'require' => null],
            ['room' => 4, 'choice' => 'Check the map', 'item' => 'map', "dialogue" => 'the map says: The path South East will take me to my friends. Going East will take me back to where I started.', 'require' => null],

            ['room' => 5, 'choice' => 'Greet your friends', 'item' => 'hello', "dialogue" => 'You wave happily to your friends. They wave back, calling you name.', 'require' => null],
            ['room' => 5, 'choice' => 'Check the map', 'item' => 'map', "dialogue" => 'You do not need to look at the map to know that you are right where you want to be. With your friends.', 'require' => null],
            ['room' => 5, 'choice' => 'Show them your stick', 'item' => 'show', "dialogue" => 'You show them your cool stick. They are impressed.', 'require' => 'stick'],
            ['room' => 5, 'choice' => 'Give them flowers', 'item' => 'bloom', "dialogue" => 'Your friends are delighted to see the blue flowers in your arms.', 'require' => 'flowers'],
            ['room' => 5, 'choice' => 'Show them your pet rock', 'item' => 'rocky', "dialogue" => 'They liked your rock. They now want to look around for rocks so your rock have some friends.', 'require' => 'rock'],
            ['room' => 5, 'choice' => 'Show them the wizards pie', 'item' => 'yummy', "dialogue" => 'You tell them of how the wizard made a pie for you and for any wolves that we met. They praise the wizards kindness.', 'require' => 'pie'],
            ['room' => 5, 'choice' => 'Show them the wolfs gift', 'item' => 'gift', "dialogue" => '"it is really green" "and it smells really nice!" says your friends.', 'require' => 'lime'],
            ['room' => 5, 'choice' => 'Show them the key', 'item' => 'shiny', "dialogue" => '"It is the magical key that leads to the flower fields!" one of your friends says. "A key that leads you to flowers ... How does that work?" the other one asks. You have no idea.', 'require' => 'key'],
        ];
    }
}