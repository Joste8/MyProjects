<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Product
{
    // ✅ PRIMARY KEY (MANDATORY)
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    private ?int $price = null;

    #[ORM\OneToMany(
        mappedBy: 'product',
        targetEntity: ProductVariant::class,
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    private Collection $variants;

    public function __construct()
    {
        $this->variants = new ArrayCollection();
    }

    // --------------------
    // GETTERS / SETTERS
    // --------------------

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

    // --------------------
    // PRICE FROM VARIANTS
    // --------------------

    public function updatePriceFromVariants(): void
    {
        if ($this->variants->isEmpty()) {
            return;
        }

        $maxPrice = 0;

        foreach ($this->variants as $variant) {
            if ($variant->getPrice() > $maxPrice) {
                $maxPrice = $variant->getPrice();
            }
        }

        $this->price = $maxPrice;
    }

    // --------------------
    // VARIANTS
    // --------------------

    public function getVariants(): Collection
    {
        return $this->variants;
    }

    public function addVariant(ProductVariant $variant): self
    {
        if (!$this->variants->contains($variant)) {
            $this->variants[] = $variant;
            $variant->setProduct($this);
        }

        return $this;
    }

    // ✅ THIS METHOD IS REQUIRED (YOUR ERROR FIX)
    public function removeVariant(ProductVariant $variant): self
    {
        if ($this->variants->removeElement($variant)) {
            if ($variant->getProduct() === $this) {
                $variant->setProduct(null);
            }
        }

        return $this;
    }

    // --------------------
    // FRONT PAGE HELPERS
    // --------------------

    public function getTotalStock(): int
    {
        $total = 0;

        foreach ($this->variants as $variant) {
            $total += $variant->getStock();
        }

        return $total;
    }

    public function getSizesLabel(): string
    {
        $sizes = [];

        foreach ($this->variants as $variant) {
            $sizes[] = $variant->getSize();
        }

        return implode(', ', array_unique($sizes));
    }
}
