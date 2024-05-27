<?php

namespace App\Entity;

use App\Repository\ShipRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ShipRepository::class)]
class Ship
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Name = null;

    #[ORM\Column(nullable: true)]
    private ?int $Mass = null;

    #[ORM\Column(nullable: true)]
    private ?int $Cargo_Capacity = null;

    #[ORM\Column(nullable: true)]
    private ?int $hp = null;

    #[ORM\Column(nullable: true)]
    private ?int $hp_shield = null;

    #[ORM\Column(nullable: true)]
    private ?int $speed_scm = null;

    #[ORM\Column(nullable: true)]
    private ?int $speed_max = null;

    #[ORM\Column(nullable: true)]
    private ?int $speed_quantum = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $role = null;

    #[ORM\Column(length: 1024, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $size = null;

    #[ORM\Column(length: 255)]
    private ?string $manufacturer = null;

    #[ORM\Column(nullable: true)]
    private ?int $irl_price = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $PledgeLink = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): static
    {
        $this->Name = $Name;

        return $this;
    }

    public function getMass(): ?int
    {
        return $this->Mass;
    }

    public function setMass(?int $Mass): static
    {
        $this->Mass = $Mass;

        return $this;
    }

    public function getCargoCapacity(): ?int
    {
        return $this->Cargo_Capacity;
    }

    public function setCargoCapacity(?int $Cargo_Capacity): static
    {
        $this->Cargo_Capacity = $Cargo_Capacity;

        return $this;
    }

    public function getHp(): ?int
    {
        return $this->hp;
    }

    public function setHp(?int $hp): static
    {
        $this->hp = $hp;

        return $this;
    }

    public function getHpShield(): ?int
    {
        return $this->hp_shield;
    }

    public function setHpShield(?int $hp_shield): static
    {
        $this->hp_shield = $hp_shield;

        return $this;
    }

    public function getSpeedScm(): ?int
    {
        return $this->speed_scm;
    }

    public function setSpeedScm(?int $speed_scm): static
    {
        $this->speed_scm = $speed_scm;

        return $this;
    }

    public function getSpeedMax(): ?int
    {
        return $this->speed_max;
    }

    public function setSpeedMax(?int $speed_max): static
    {
        $this->speed_max = $speed_max;

        return $this;
    }

    public function getSpeedQuantum(): ?int
    {
        return $this->speed_quantum;
    }

    public function setSpeedQuantum(?int $speed_quantum): static
    {
        $this->speed_quantum = $speed_quantum;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getSize(): ?string
    {
        return $this->size;
    }

    public function setSize(string $size): static
    {
        $this->size = $size;

        return $this;
    }

    public function getManufacturer(): ?string
    {
        return $this->manufacturer;
    }

    public function setManufacturer(string $manufacturer): static
    {
        $this->manufacturer = $manufacturer;

        return $this;
    }

    public function getIrlPrice(): ?int
    {
        return $this->irl_price;
    }

    public function setIrlPrice(?int $irl_price): static
    {
        $this->irl_price = $irl_price;

        return $this;
    }

    public function getPledgeLink(): ?string
    {
        return $this->PledgeLink;
    }

    public function setPledgeLink(?string $PledgeLink): static
    {
        $this->PledgeLink = $PledgeLink;

        return $this;
    }
}
