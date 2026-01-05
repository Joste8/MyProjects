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

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private ?string $price = null;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $extraAttributes = [];

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ProductAttribute::class, cascade: ['persist', 'remove'])]
    private Collection $attributes;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ProductVariant::class, cascade: ['persist', 'remove'])]
    private Collection $variants;

    public function __construct()
    {
        // ബന്ധിപ്പിക്കപ്പെട്ട കളക്ഷനുകൾ ഇനിഷ്യലൈസ് ചെയ്യുന്നു
        $this->attributes = new ArrayCollection();
        $this->variants = new ArrayCollection();
    }

    // --- Variants മാനേജ് ചെയ്യാനുള്ള ഫംഗ്‌ഷനുകൾ (ഇത് ക്ലാസിനുള്ളിൽ തന്നെ വേണം) ---

    /**
     * @return Collection<int, ProductVariant>
     */
    public function getVariants(): Collection
    {
        return $this->variants;
    }

    public function addVariant(ProductVariant $variant): self
    {
        if (!$this->variants->contains($variant)) {
            $this->variants->add($variant);
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

    // --- JSON ആട്രിബ്യൂട്ടുകൾ ---

    public function getAttributesJson(): string
    {
        return json_encode($this->extraAttributes ?? [], JSON_PRETTY_PRINT);
    }

    public function setAttributesJson(?string $json): self
    {
        $this->extraAttributes = json_decode($json, true) ?? [];
        return $this;
    }

    // --- സാധാരണ Getters & Setters ---

    public function getId(): ?int { return $this->id; }

    public function getName(): ?string { return $this->name; }
    public function setName(string $name): self { $this->name = $name; return $this; }

    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $description): self { $this->description = $description; return $this; }

    public function getPrice(): ?string { return $this->price; }
    public function setPrice(string $price): self { $this->price = $price; return $this; }

    public function __toString(): string { return $this->name ?? 'New Product'; }

} 