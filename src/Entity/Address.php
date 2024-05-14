<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection()
    ],
    normalizationContext: ['groups' => ['address-read']],
    denormalizationContext: ['groups' => ['address-write']],
)]
class Address
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255)]
    #[Assert\NotNull]
    #[Groups(['user-read', 'customer-read', 'professional-read', 'location-read', 'address-read'])]
    private ?string $lineOne = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255)]
    #[Groups(['user-read', 'customer-read', 'professional-read', 'location-read', 'address-read'])]
    private ?string $lineTwo = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255)]
    #[Groups(['user-read', 'customer-read', 'professional-read', 'location-read', 'address-read'])]
    private ?string $city = null;

    #[ORM\Column(length: 10, nullable: true)]
    #[Assert\Length(max: 10)]
    #[Groups(['user-read', 'customer-read', 'professional-read', 'location-read', 'address-read'])]
    private ?string $zipCode = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255)]
    #[Groups(['user-read', 'customer-read', 'professional-read', 'location-read', 'address-read'])]
    private ?string $state = null;

    #[ORM\Column(nullable: true)]
    #[Assert\NotNull]
    #[Groups(['user-read', 'customer-read', 'professional-read', 'location-read', 'address-read'])]
    private ?string $country = null;

    public function __toString(): string
    {
        $address = $this->lineOne;
        if ($this->lineTwo) {
            $address .= ", " . $this->lineTwo;
        }
        if ($this->city) {
            $address .= ", " . $this->city;
        }
        if ($this->state) {
            $address .= "<br>" . $this->state;
        }
        if ($this->zipCode) {
            $address .= ", " . $this->zipCode;
        }
        if ($this->country) {
            $address .= ", " . $this->country;
        }

        return $address;
    }

    /**
     * @return string|null
     */
    public function getLineOne(): ?string
    {
        return $this->lineOne;
    }

    /**
     * @param string|null $lineOne
     */
    public function setLineOne(?string $lineOne): void
    {
        $this->lineOne = $lineOne;
    }

    /**
     * @return string|null
     */
    public function getLineTwo(): ?string
    {
        return $this->lineTwo;
    }

    /**
     * @param string|null $lineTwo
     */
    public function setLineTwo(?string $lineTwo): void
    {
        $this->lineTwo = $lineTwo;
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string|null $city
     */
    public function setCity(?string $city): void
    {
        $this->city = $city;
    }

    /**
     * @return string|null
     */
    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    /**
     * @param string|null $zipCode
     */
    public function setZipCode(?string $zipCode): void
    {
        $this->zipCode = $zipCode;
    }

    /**
     * @return string|null
     */
    public function getState(): ?string
    {
        return $this->state;
    }

    /**
     * @param string|null $state
     */
    public function setState(?string $state): void
    {
        $this->state = $state;
    }

    /**
     * @return string|null
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @param string|null $country
     */
    public function setCountry(?string $country): void
    {
        $this->country = $country;
    }
}