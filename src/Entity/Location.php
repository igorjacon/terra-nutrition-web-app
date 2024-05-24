<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\LocationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: LocationRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection()
    ],
    normalizationContext: ['groups' => ['location-read']],
    denormalizationContext: ['groups' => ['location-write']],
)]
class Location
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['customer-read', 'professional-read', 'location-read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull]
    #[Groups(['customer-read', 'professional-read', 'location-read'])]
    private ?string $name = null;

    #[ORM\OneToOne(targetEntity: Phone::class, cascade: ['persist', 'remove'])]
    #[Assert\Valid]
    #[Groups(['customer-read', 'professional-read', 'location-read'])]
    private $phone;

    #[ORM\OneToOne(targetEntity: Address::class, cascade: ['persist', 'remove'])]
    #[Assert\Valid]
    #[Groups(['customer-read', 'professional-read', 'location-read'])]
    private $address;

    #[ORM\ManyToOne(targetEntity: Professional::class, inversedBy: 'locations')]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    private $professional;

    public function __construct()
    {
        $this->address = new Address();
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getProfessional()
    {
        return $this->professional;
    }

    /**
     * @param mixed $professional
     */
    public function setProfessional($professional): void
    {
        $this->professional = $professional;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address): void
    {
        $this->address = $address;
    }
}
