<?php

// src/Entity/ProductVariant.php
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

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'variants')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private float $price;

    #[ORM\Column]
    private int $stock;

    #[ORM\ManyToMany(targetEntity: AttributeValue::class)]
    #[ORM\JoinTable(name: 'product_variant_attribute_value')]
    private Collection $attributeValues;

    public function __construct()
    {
        $this->attributeValues = new ArrayCollection();
    }

    public function __toString(): string
    {
        $attrs = [];
        foreach ($this->attributeValues as $av) {
            $attrs[] = $av->getAttribute()->getName().': '.$av->getValue();
        }

        return implode(', ', $attrs);
    }
}
