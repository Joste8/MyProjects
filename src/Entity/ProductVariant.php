<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity]
class ProductVariant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    // ✅ REQUIRED: Product relation
    #[ORM\ManyToOne(inversedBy: 'variants')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    // ✅ Attribute Values relation
    #[ORM\ManyToMany(targetEntity: AttributeValue::class)]
    #[ORM\JoinTable(name: 'product_variant_attribute_value')]
    private Collection $attributeValues;

    public function __construct()
    {
        $this->attributeValues = new ArrayCollection();
    }

    // ---------------- ID ----------------
    public function getId(): ?int
    {
        return $this->id;
    }

    // ---------------- PRODUCT ----------------
    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;
        return $this;
    }

    // ---------------- ATTRIBUTE VALUES ----------------
    public function getAttributeValues(): Collection
    {
        return $this->attributeValues;
    }

    public function addAttributeValue(AttributeValue $value): self
    {
        if (!$this->attributeValues->contains($value)) {
            $this->attributeValues->add($value);
        }
        return $this;
    }

    public function removeAttributeValue(AttributeValue $value): self
    {
        $this->attributeValues->removeElement($value);
        return $this;
    }
}
