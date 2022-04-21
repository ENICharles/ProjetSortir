<?php

namespace App\Entity;

use App\Repository\LocalisationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: LocalisationRepository::class)]
class Localisation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 250)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 2,
        max: 250,
        minMessage: 'Le nombre de caractère minimum est de 2',
        maxMessage: 'Le nombre de caractère maximum est de 250')]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 2,
        max: 250,
        minMessage: 'Le nombre de caractère minimum est de 2',
        maxMessage: 'Le nombre de caractère maximum est de 255')]
    #[Groups('lieu')]
    private $street;

    #[ORM\Column(type: 'float', nullable: true)]
    #[Groups('lieu')]
    private $latitude;

    #[ORM\Column(type: 'float', nullable: true)]
    #[Groups('lieu')]
    private $longitude;

    #[ORM\OneToMany(mappedBy: 'localisation', targetEntity: Event::class)]
    private $events;

    #[ORM\ManyToOne(targetEntity: City::class, inversedBy: 'localisations')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('lieu')]
    private $city;

    public function __construct()
    {
        $this->events = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
            $event->setLocalisation($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getLocalisation() === $this) {
                $event->setLocalisation(null);
            }
        }

        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function __toString(){
        return $this->getName();
//               $this->getStreet().' '.
//               $this->getCity()->getPostcode().' '.
//               $this->getCity()->getName().' '.
//               $this->getLatitude().' '.
//               $this->getLongitude();
    }
}
