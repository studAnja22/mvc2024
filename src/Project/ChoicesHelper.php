<?php

namespace App\Project;

use App\Entity\Choices;
use App\Project\ChoicesData;
use App\Repository\ChoicesRepository;
use Doctrine\Persistence\ManagerRegistry;

class ChoicesHelper
{
    public function createChoices(
        ManagerRegistry $doctrine
    ): void {
        $entityManager = $doctrine->getManager();
        
        /** @var array<string,mixed> $ChoicesData */
        $choicesData = ChoicesData::getChoicesData();

        foreach ($choicesData as $row) {
            /** @var array{room:int,choice:string,gain:string,require:string|null} $row*/
            /** @var Choices $choices */
            $choices = new Choices();
            $choices->setRoom($row['room']);
            $choices->setChoice($row['choice']);
            $choices->setItem($row['item']);
            $choices->setDialogue($row['dialogue']);
            $choices->setRequire($row['require']);

            $entityManager->persist($choices);
            $entityManager->flush();
        }
    }
}