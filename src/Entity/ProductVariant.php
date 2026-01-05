<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
class ProductVariant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column]
    private ?int $stock = null;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'variants')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $size = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $color = null;

    // --- Getters & Setters ---

    public function getId(): ?int { return $this->id; }

    public function getPrice(): ?float { return $this->price; }
    public function setPrice(float $price): self { $this->price = $price; return $this; }

    public function getStock(): ?int { return $this->stock; }
    public function setStock(int $stock): self { $this->stock = $stock; return $this; }

    public function getProduct(): ?Product { return $this->product; }
    
    // 👇 ഈ മെത്തേഡ് ആണ് റിലേഷൻ വർക്ക് ആകാൻ ഏറ്റവും പ്രധാനം
    public function setProduct(?Product $product): self 
    { 
        $this->product = $product; 
        return $this; 
    }

    public function getSize(): ?string { return $this->size; }
    public function setSize(?string $size): self { $this->size = $size; return $this; }

    public function getColor(): ?string { return $this->color; }
    public function setColor(?string $color): self { $this->color = $color; return $this; }

    // 👇 EasyAdmin-ൽ വേരിയന്റുകൾ കാണിക്കാൻ ഇത് സഹായിക്കും
    public function __toString(): string
    {
        return sprintf('%s - %s (Stock: %d)', $this->color ?? 'No Color', $this->size ?? 'No Size', $this->stock ?? 0);
    }
}