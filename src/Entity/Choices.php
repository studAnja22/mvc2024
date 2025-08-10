<?php

namespace App\Entity;

use App\Repository\ChoicesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChoicesRepository::class)]
class Choices
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $room = null;

    #[ORM\Column(length: 255)]
    private ?string $choice = null;

    #[ORM\Column(length: 255)]
    private ?string $item = null;

    #[ORM\Column(length: 255)]
    private ?string $dialogue = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $require = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRoom(): ?int
    {
        return $this->room;
    }

    public function setRoom(int $room): static
    {
        $this->room = $room;

        return $this;
    }

    public function getChoice(): ?string
    {
        return $this->choice;
    }

    public function setChoice(string $choice): static
    {
        $this->choice = $choice;

        return $this;
    }

    public function getItem(): ?string
    {
        return $this->item;
    }

    public function setItem(string $item): static
    {
        $this->item = $item;

        return $this;
    }

    public function getDialogue(): ?string
    {
        return $this->dialogue;
    }

    public function setDialogue(string $dialogue): static
    {
        $this->dialogue = $dialogue;

        return $this;
    }

    public function getRequire(): ?string
    {
        return $this->require;
    }

    public function setRequire(?string $require): static
    {
        $this->require = $require;

        return $this;
    }
}
