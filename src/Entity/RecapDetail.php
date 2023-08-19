<?php

namespace App\Entity;

use App\Repository\RecapDetailRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecapDetailRepository::class)]
class RecapDetail
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

  

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column(length: 255)]
    private ?string $totalRecap = null;

    #[ORM\Column(length: 255)]
    private ?string $product = null;

    #[ORM\ManyToOne(inversedBy: 'recapDetails')]
    private ?Order $orderProduct = null;

    public function getId(): ?int
    {
        return $this->id;
    }

   

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getTotalRecap(): ?string
    {
        return $this->totalRecap;
    }

    public function setTotalRecap(string $totalRecap): static
    {
        $this->totalRecap = $totalRecap;

        return $this;
    }

    public function getProduct(): ?string
    {
        return $this->product;
    }

    public function setProduct(string $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function getOrderProduct(): ?Order
    {
        return $this->orderProduct;
    }

    public function setOrderProduct(?Order $orderProduct): static
    {
        $this->orderProduct = $orderProduct;

        return $this;
    }
}
