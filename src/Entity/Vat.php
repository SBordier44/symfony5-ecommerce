<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\VatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;

/**
 * @ORM\Entity(repositoryClass=VatRepository::class)
 */
class Vat
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\CustomIdGenerator(class=UuidV4Generator::class)
     */
    private mixed $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private ?string $label;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     */
    private ?float $value;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="vat")
     */
    private Collection $products;

    #[Pure]
    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): mixed
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(float $value): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection|array
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setVat($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            if ($product->getVat() === $this) {
                $product->setVat(null);
            }
        }

        return $this;
    }
}
