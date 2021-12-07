<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\VatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: VatRepository::class)]
class Vat
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private Uuid $id;

    #[ORM\Column(type: Types::STRING, length: 50)]
    private ?string $label;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    private ?float $value;

    #[ORM\OneToMany(mappedBy: 'vat', targetEntity: Product::class)]
    private Collection $products;

    public function __construct()
    {
        $this->id = Uuid::v4();
        $this->products = new ArrayCollection();
    }

    public function getId(): Uuid
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
        if ($this->products->removeElement($product) && $product->getVat() === $this) {
            $product->setVat(null);
        }

        return $this;
    }
}
