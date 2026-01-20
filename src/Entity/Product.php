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

    #[ORM\Column(length: 150)]
    private ?string $name = null;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $price = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $stock = null;

    #[ORM\ManyToOne(targetEntity: SubCategory::class)]
    #[ORM\JoinColumn(nullable: true)] 
    private ?SubCategory $subCategory = null;

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
        $this->stock = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;
        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(?int $stock): self
    {
        $this->stock = $stock;
        return $this;
    }

    // SubCategory Getter & Setter
    public function getSubCategory(): ?SubCategory
    {
        return $this->subCategory;
    }

    public function setSubCategory(?SubCategory $subCategory): self
    {
        $this->subCategory = $subCategory;
        return $this;
    }

    /**
     * @return Collection<int, ProductAttributeValue>
     */
    public function getAttributeValues(): Collection
    {
        return $this->attributeValues;
    }

    public function addAttributeValue(ProductAttributeValue $value): self
    {
        if (!$this->attributeValues->contains($value)) {
            $this->attributeValues->add($value);
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