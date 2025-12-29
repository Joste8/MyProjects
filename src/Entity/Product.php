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
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\OneToMany(
        mappedBy: 'product',
        targetEntity: ProductAttributeValue::class,
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    private Collection $attributeValues;

    public function __construct()
    {
        $this->attributeValues = new ArrayCollection();
    }

    // 🔥 VERY IMPORTANT
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getAttributeValues(): Collection
    {
        return $this->attributeValues;
    }

    public function addAttributeValue(ProductAttributeValue $value): self
    {
        if (!$this->attributeValues->contains($value)) {
            $this->attributeValues[] = $value;
            $value->setProduct($this);
        }
        return $this;
    }

    public function removeAttributeValue(ProductAttributeValue $value): self
    {
        if ($this->attributeValues->removeElement($value)) {
            if ($value->getProduct() === $this) {
                $value->setProduct(null);
            }
        }
        return $this;
    }

    public function __toString(): string
    {
        return $this->name ?? '';
    }
}
