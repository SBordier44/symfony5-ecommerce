<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\AddressRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;

/**
 * @ORM\Entity(repositoryClass=AddressRepository::class)
 */
class Address
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
     * @ORM\Column(type="string", length=255)
     */
    private ?string $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $lastName;

    /**
     * @ORM\Column(type="date")
     */
    private ?DateTimeInterface $birthDate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $addressName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $country;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $city;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private ?string $zipCode;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $street;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $additionalAddress;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $postalAddressService;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="addresses")
     */
    private ?User $user;

    /**
     * @ORM\Column(type="boolean", options={"default": false})
     */
    private bool $deliveryDefault = false;

    /**
     * @ORM\Column(type="boolean", options={"default": false})
     */
    private bool $invoiceDefault = false;

    public function getId(): mixed
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getBirthDate(): ?DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(DateTimeInterface $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getAddressName(): ?string
    {
        return $this->addressName;
    }

    public function setAddressName(string $addressName): self
    {
        $this->addressName = $addressName;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setZipCode(string $zipCode): self
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getAdditionalAddress(): ?string
    {
        return $this->additionalAddress;
    }

    public function setAdditionalAddress(?string $additionalAddress): self
    {
        $this->additionalAddress = $additionalAddress;

        return $this;
    }

    public function getPostalAddressService(): ?string
    {
        return $this->postalAddressService;
    }

    public function setPostalAddressService(?string $postalAddressService): self
    {
        $this->postalAddressService = $postalAddressService;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getDeliveryDefault(): ?bool
    {
        return $this->deliveryDefault;
    }

    public function setDeliveryDefault(bool $deliveryDefault): self
    {
        $this->deliveryDefault = $deliveryDefault;

        return $this;
    }

    public function getInvoiceDefault(): ?bool
    {
        return $this->invoiceDefault;
    }

    public function setInvoiceDefault(bool $invoiceDefault): self
    {
        $this->invoiceDefault = $invoiceDefault;

        return $this;
    }
}
