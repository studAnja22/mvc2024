<?php

namespace App\Entity;

use App\Repository\PathRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PathRepository::class)]
class Path
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(name: 'from_room', type: 'integer')]
    private ?int $fromRoom = null;

    #[ORM\Column(length: 20)]
    private ?string $direction = null;

    #[ORM\Column(name: 'to_room', type: 'integer')]
    private ?int $toRoom = null;

    #[ORM\Column(name: 'required_item', type: 'string', length: 20, nullable: true)]
    private ?string $requiredItem = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFromRoom(): ?int
    {
        return $this->fromRoom;
    }

    public function setFromRoom(int $fromRoom): static
    {
        $this->fromRoom = $fromRoom;

        return $this;
    }

    public function getDirection(): ?string
    {
        return $this->direction;
    }

    public function setDirection(string $direction): static
    {
        $this->direction = $direction;

        return $this;
    }

    public function getToRoom(): ?int
    {
        return $this->toRoom;
    }

    public function setToRoom(int $toRoom): static
    {
        $this->toRoom = $toRoom;

        return $this;
    }

    public function getRequiredItem(): ?string
    {
        return $this->requiredItem;
    }

    public function setRequiredItem(?string $requiredItem): static
    {
        $this->requiredItem = $requiredItem;

        return $this;
    }
}
