<?php

namespace App\Project;

use App\Entity\Path;
use App\Project\PathData;
use App\Repository\PathRepository;
use Doctrine\Persistence\ManagerRegistry;

class PathHelper
{
    public function createPaths(
        ManagerRegistry $doctrine
    ): void {
        $entityManager = $doctrine->getManager();
        
        /** @var array<string,mixed> $pathData */
        $pathData = PathData::getPathData();

        foreach ($pathData as $row) {
            /** @var array{from_room:int,direction:string,to_room:int,required_item:string|null} $row*/
            /** @var Path $path */
            $path = new Path();
            $path->setFromRoom($row['from_room']);
            $path->setDirection($row['direction']);
            $path->setToRoom($row['to_room']);
            $path->setRequiredItem($row['required_item']);

            $entityManager->persist($path);
            $entityManager->flush();
        }
    }
}