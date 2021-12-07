<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProductRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Uid\Uuid;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private Uuid $id;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $name;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $slug = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $stock = 0;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $sku;

    #[Vich\UploadableField(mapping: 'product_image', fileNameProperty: 'imageName', size: 'imageSize')]
    private ?File $imageFile = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $imageName;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?int $imageSize;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'products')]
    private Category $category;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $unitPrice = 0;

    #[ORM\ManyToOne(targetEntity: Vat::class, inversedBy: 'products')]
    private ?Vat $vat;

    #[ORM\Column(type: 'float', options: ['default' => 0.00])]
    private ?float $discountPercentage;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $featured = false;

    public function __construct()
    {
        $this->id = Uuid::v4();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getSku(): ?string
    {
        return $this->sku;
    }

    public function setSku(?string $sku): self
    {
        $this->sku = $sku;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;
        return $this;
    }

    public function getUnitPrice(): ?int
    {
        return $this->unitPrice;
    }

    public function setUnitPrice(int $unitPrice): self
    {
        $this->unitPrice = $unitPrice;

        return $this;
    }

    /**
     * @return File|null
     */
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * @param File|null $imageFile
     * @return Product
     */
    public function setImageFile(?File $imageFile = null): Product
    {
        $this->imageFile = $imageFile;
        if (null !== $imageFile) {
            $this->updatedAt = new DateTimeImmutable();
        }
        return $this;
    }

    /**
     * @return string|null
     */
    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    /**
     * @param string|null $imageName
     * @return Product
     */
    public function setImageName(?string $imageName): Product
    {
        $this->imageName = $imageName;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getImageSize(): ?int
    {
        return $this->imageSize;
    }

    /**
     * @param int|null $imageSize
     * @return Product
     */
    public function setImageSize(?int $imageSize): Product
    {
        $this->imageSize = $imageSize;
        return $this;
    }

    public function getVat(): ?Vat
    {
        return $this->vat;
    }

    public function setVat(?Vat $vat): self
    {
        $this->vat = $vat;

        return $this;
    }

    public function getDiscountPercentage(): ?float
    {
        return $this->discountPercentage;
    }

    public function setDiscountPercentage(float $discountPercentage): self
    {
        $this->discountPercentage = $discountPercentage;

        return $this;
    }

    public function getFeatured(): ?bool
    {
        return $this->featured;
    }

    public function setFeatured(bool $featured): self
    {
        $this->featured = $featured;

        return $this;
    }
}
