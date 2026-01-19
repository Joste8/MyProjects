<?php

namespace App\Entity;

#[ORM\Entity]
class ProductVariant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'variants')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    #[ORM\ManyToMany(targetEntity: ProductAttributeValue::class)]
    #[ORM\JoinTable(name: 'product_variant_attribute_values')]
    private Collection $attributeValues;

    #[ORM\Column(type: 'integer')]
    private int $stock = 0;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private string $price;

    public function __construct()
    {
        $this->attributeValues = new ArrayCollection();
    }

    public function getAttributeValues(): Collection
    {
        return $this->attributeValues;
    }

    public function addAttributeValue(ProductAttributeValue $value): self
    {
        if (!$this->attributeValues->contains($value)) {
            $this->attributeValues->add($value);
        }
        return $this;
    }

    public function removeAttributeValue(ProductAttributeValue $value): self
    {
        $this->attributeValues->removeElement($value);
        return $this;
    }
}
