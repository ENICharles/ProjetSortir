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

    #[ORM\Column(type: 'string', length: 255)]
    private $campus;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'datetime')]
    private $dateStart;

    #[ORM\Column(type: 'datetime')]
    private $inscriptionDateLimit;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCampus(): ?string
    {
        return $this->campus;
    }

    public function setCampus(string $campus): self
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

    public function getInscriptionDateLimit(): ?\DateTimeInterface
    {
        return $this->inscriptionDateLimit;
    }

    public function setInscriptionDateLimit(\DateTimeInterface $inscriptionDateLimit): self
    {
        $this->inscriptionDateLimit = $inscriptionDateLimit;

        return $this;
    }
}
