<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Attribute
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\OneToMany(
        mappedBy: 'attribute',
        targetEntity: AttributeValue::class,
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    private Collection $values;

    public function __construct()
    {
        $this->values = new ArrayCollection();
    }

    // ---------------- ID ----------------
    public function getId(): ?int
    {
        return $this->id;
    }

    // ---------------- NAME ----------------
    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    // ---------------- VALUES ----------------
    public function getValues(): Collection
    {
        return $this->values;
    }

    public function addValue(AttributeValue $value): self
    {
        if (!$this->values->contains($value)) {
            $this->values->add($value);
            $value->setAttribute($this);
        }
        return $this;
    }

    public function removeValue(AttributeValue $value): self
    {
        if ($this->values->removeElement($value)) {
            if ($value->getAttribute() === $this) {
                $value->setAttribute(null);
            }
        }
        return $this;
    }

    // ---------------- STRING (EasyAdmin / Forms) ----------------
    public function __toString(): string
    {
        return (string) $this->name;
    }
}
