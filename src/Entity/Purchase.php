<?php

namespace App\Entity;

use App\Repository\PurchaseRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PurchaseRepository::class)]
class Purchase
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $itemName = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: true)]
    private ?string $totalPrice = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $purchasedAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $productVariant = null;

    #[ORM\ManyToOne(targetEntity: Customer::class, inversedBy: 'purchases')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Customer $customer = null;

    public function getId(): ?int { return $this->id; }

    public function getItemName(): ?string { return $this->itemName; }
    public function setItemName(string $itemName): static { $this->itemName = $itemName; return $this; }

    public function getPrice(): ?float { return $this->price; }
    public function setPrice(float $price): static { $this->price = $price; return $this; }

    public function getQuantity(): ?int { return $this->quantity; }
    public function setQuantity(int $quantity): static { $this->quantity = $quantity; return $this; }

    public function getTotalPrice(): ?string 
    { 
        return $this->totalPrice; 
    }

    public function setTotalPrice(?string $totalPrice): static 
    { 
        $this->totalPrice = $totalPrice; 
        return $this; 
    }

    public function getPurchasedAt(): ?\DateTimeImmutable { return $this->purchasedAt; }
    public function setPurchasedAt(\DateTimeImmutable $purchasedAt): static { $this->purchasedAt = $purchasedAt; return $this; }

    public function getCustomer(): ?Customer { return $this->customer; }
    public function setCustomer(?Customer $customer): static { $this->customer = $customer; return $this; }

    public function getProductVariant(): ?string { return $this->productVariant; }
    public function setProductVariant(?string $productVariant): static { $this->productVariant = $productVariant; return $this; }
} 