<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
class ProductAttribute
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\OneToMany(
        mappedBy: 'attribute',
        targetEntity: ProductAttributeValue::class,
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    private Collection $values;

    public function __construct()
    {
        $this->values = new ArrayCollection();
    }

    // ================= ID =================

    public function getId(): ?int
    {
        return $this->id;
    }

    // ================= NAME =================

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    // ================= VALUES =================

    /**
     * @return Collection<int, ProductAttributeValue>
     */
    public function getValues(): Collection
    {
        return $this->values;
    }

    public function addValue(ProductAttributeValue $value): self
    {
        if (!$this->values->contains($value)) {
            $this->values->add($value);
            $value->setAttribute($this);
        }

        return $this;
    }

    public function removeValue(ProductAttributeValue $value): self
    {
        if ($this->values->removeElement($value)) {
            if ($value->getAttribute() === $this) {
                $value->setAttribute(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->name ?? '';
    }
}
