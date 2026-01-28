<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private ?string $price = null;

    #[ORM\Column]
    private ?int $stock = null;

    #[ORM\ManyToOne(targetEntity: SubCategory::class)]
    private ?SubCategory $subCategory = null;

    
    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ProductVariant::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $variants;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $status = 'Active';

    public function __construct()
    {
        $this->variants = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }

    public function getName(): ?string { return $this->name; }

    public function setName(string $name): self 
    { 
        $this->name = $name; 
        return $this; 
    }

    public function getPrice(): ?string { return $this->price; }

    public function setPrice(string $price): self 
    { 
        $this->price = $price; 
        return $this; 
    }

    public function getStock(): ?int { return $this->stock; }

    public function setStock(int $stock): self 
    { 
        $this->stock = $stock; 
        return $this; 
    }

    public function getSubCategory(): ?SubCategory { return $this->subCategory; }

    public function setSubCategory(?SubCategory $subCategory): self 
    { 
        $this->subCategory = $subCategory; 
        return $this; 
    }

    public function getStatus(): ?string { return $this->status; }

    public function setStatus(?string $status): self 
    { 
        $this->status = $status; 
        return $this; 
    }

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

    public function __toString(): string 
    { 
        return $this->name ?? ''; 
    }
    public function getVariantDetails(): string
{
    if ($this->variants->isEmpty()) {
        return 'No Variants';
    }

    $details = [];
    foreach ($this->variants as $variant) {
      
        $details[] = sprintf('%s: %s (Stock: %d)', $variant->getName(), $variant->getValue(), $variant->getStock());
    }

    return implode(', ', $details);
}
}