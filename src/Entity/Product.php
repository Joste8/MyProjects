<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

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

    public function removeVariant(ProductVariant $variant): self
    {
        if ($this->variants->removeElement($variant)) {
            if ($variant->getProduct() === $this) {
                $variant->setProduct(null);
            }
        }

        return $this;
    }

    // ✅ Total Stock
    public function getTotalStock(): int
    {
        $total = 0;

        foreach ($this->variants as $variant) {
            $total += $variant->getStock() ?? 0;
        }

        return $total;
    }

    // ✅ Size / Colour / Capacity summary
    public function getVariantAttributes(): string
    {
        $result = [];

        foreach ($this->variants as $variant) {
            $attrs = [];

            foreach ($variant->getAttributes() as $attr) {
                $attrs[] =
                    $attr->getAttribute()->getName() . ': ' . $attr->getValue();
            }

            if (!empty($attrs)) {
                $result[] = implode(', ', $attrs);
            }
        }

        return implode(' | ', $result);
    }
}
