<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\CategoryRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 * @Vich\Uploadable
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\CustomIdGenerator(class=UuidV4Generator::class)
     */
    private mixed $id;

    /**
     * @ORM\Column(type="string", length=120)
     */
    private ?string $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $description;

    /**
     * @Vich\UploadableField(mapping="category_image", fileNameProperty="imageName", size="imageSize")
     */
    private ?File $imageFile = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $imageName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?int $imageSize;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $slug;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?DateTimeInterface $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTimeInterface $updatedAt;

    public function getId(): mixed
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

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug ?? null;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

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
     * @return Category
     */
    public function setImageFile(?File $imageFile = null): Category
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
     * @return Category
     */
    public function setImageName(?string $imageName): Category
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
     * @return Category
     */
    public function setImageSize(?int $imageSize): Category
    {
        $this->imageSize = $imageSize;
        return $this;
    }
}
