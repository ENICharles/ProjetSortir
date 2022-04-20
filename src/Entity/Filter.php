<?php

namespace App\Entity;

use App\Repository\FilterRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FilterRepository::class)]
class Filter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\OneToOne(targetEntity: Campus::class, cascade: ['persist', 'remove'])]
    private $campus;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $name;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $dateStart;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $dateEnd;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $isOrganisator;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $isRegistered;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $isNotRegistered;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $isPassedEvent;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): self
    {
        $this->campus = $campus;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->dateStart;
    }

    public function setDateStart(\DateTimeInterface $dateStart): self
    {
        $this->dateStart = $dateStart;
        return $this;
    }

    public function getIsOrganisator(): ?bool
    {
        return $this->isOrganisator;
    }

    public function setIsOrganisator(bool $isOrganisator): self
    {
        $this->isOrganisator = $isOrganisator;

        return $this;
    }

    public function getIsRegistered(): ?bool
    {
        return $this->isRegistered;
    }

    public function setIsRegistered(bool $isRegistered): self
    {
        $this->isRegistered = $isRegistered;

        return $this;
    }

    public function getIsNotRegistered(): ?bool
    {
        return $this->isNotRegistered;
    }

    public function setIsNotRegistered(?bool $isNotRegistered): self
    {
        $this->isNotRegistered = $isNotRegistered;

        return $this;
    }

    public function getIsPassedEvent(): ?bool
    {
        return $this->isPassedEvent;
    }

    public function setIsPassedEvent(?bool $isPassedEvent): self
    {
        $this->isPassedEvent = $isPassedEvent;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->dateEnd;
    }
    public function setDateEnd(?\DateTimeInterface $dateEnd): self
    {
        $this->dateEnd = $dateEnd;
        return $this;
    }
}
