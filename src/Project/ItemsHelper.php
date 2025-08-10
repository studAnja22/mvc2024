<?php

namespace App\Project;

use App\Entity\Items;
use App\Project\ItemsData;
use App\Repository\ItemsRepository;
use Doctrine\Persistence\ManagerRegistry;

class ItemsHelper
{
    public function createItems(
        ManagerRegistry $doctrine
    ): void {
        $entityManager = $doctrine->getManager();
        
        /** @var array<string,mixed> $itemData */
        $itemData = ItemsData::getItemsData();

        foreach ($itemData as $row) {
            /** @var array{name:string,description:string} $row*/
            /** @var Items $item */
            $item = new Items();
            $item->setName($row['name']);
            $item->setDescription($row['description']);

            $entityManager->persist($item);
            $entityManager->flush();
        }
    }
}