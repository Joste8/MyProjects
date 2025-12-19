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
    private ?int $price = null;

    #[ORM\Column]
    private ?int $stock = 0;

    #[ORM\ManyToOne(inversedBy: 'variants')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    #[ORM\OneToMany(
        mappedBy: 'variant',
        targetEntity: VariantAttribute::class,
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    private Collection $attributes;

    public function __construct()
    {
        $this->attributes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;
        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;
        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;
        return $this;
    }

    public function getAttributes(): Collection
    {
        return $this->attributes;
    }

    public function addAttribute(VariantAttribute $attribute): self
    {
        if (!$this->attributes->contains($attribute)) {
            $this->attributes[] = $attribute;
            $attribute->setVariant($this);
        }

        return $this;
    }

    public function removeAttribute(VariantAttribute $attribute): self
    {
        if ($this->attributes->removeElement($attribute)) {
            if ($attribute->getVariant() === $this) {
                $attribute->setVariant(null);
            }
        }

        return $this;
    }
}
