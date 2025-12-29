<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\ManyToMany(targetEntity: AttributeValue::class)]
#[ORM\JoinTable(
    name: 'product_attribute_value',
    joinColumns: [
        new ORM\JoinColumn(name: 'product_id', referencedColumnName: 'id')
    ],
    inverseJoinColumns: [
        new ORM\JoinColumn(name: 'attribute_value_id', referencedColumnName: 'id')
    ]
)]
private Collection $attributeValues;


    public function __construct()
    {
        $this->attributeValues = new ArrayCollection();
    }

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
