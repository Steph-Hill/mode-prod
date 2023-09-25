<?php

namespace App\Entity;

use App\Repository\HairSalonRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HairSalonRepository::class)]
class HairSalon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 80)]
    private ?string $name = null;

    #[ORM\Column(length: 180)]
    private ?string $postalAdress = null;

    #[ORM\Column(length: 30)]
    private ?string $phone = null;

    #[ORM\Column(length: 5)]
    private ?string $employe = null;

    #[ORM\Column(length: 5)]
    private ?string $chair = null;

    #[ORM\Column(type: "datetime_immutable")]
private ?\DateTimeImmutable $createdAt = null;

#[ORM\Column(type: "datetime_immutable", nullable: true)]
private ?\DateTimeImmutable $updatedAt = null;

#[ORM\ManyToOne(inversedBy: 'hairSalons')]
private ?Professional $professional = null;

   

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

    public function getPostalAdress(): ?string
    {
        return $this->postalAdress;
    }

    public function setPostalAdress(string $postalAdress): self
    {
        $this->postalAdress = $postalAdress;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmploye(): ?string
    {
        return $this->employe;
    }

    public function setEmploye(string $employe): self
    {
        $this->employe = $employe;

        return $this;
    }

    public function getChair(): ?string
    {
        return $this->chair;
    }

    public function setChair(string $chair): self
    {
        $this->chair = $chair;

        return $this;
    }

     /* Ajout de la fonction __tostring() */
     public function __toString(): string
     {
         // Convertis le champ le en sting
         return $this->postalAdress;
     }

     public function getCreatedAt(): ?\DateTimeImmutable
     {
         return $this->createdAt;
     }

     public function setCreatedAt(\DateTimeImmutable $createdAt): static
     {
         $this->createdAt = $createdAt;

         return $this;
     }

     public function getUpdatedAt(): ?\DateTimeImmutable
     {
         return $this->updatedAt;
     }

     public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
     {
         $this->updatedAt = $updatedAt;

         return $this;
     }

     public function getProfessional(): ?Professional
     {
         return $this->professional;
     }

     public function setProfessional(?Professional $professional): static
     {
         $this->professional = $professional;

         return $this;
     }

   
}
