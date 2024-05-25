<?php

namespace App\Entity;

use App\Repository\FoodMeasurementRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: FoodMeasurementRepository::class)]
class FoodMeasurement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['meal-plan-read', 'meal-read'])]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $abbreviation = null;

    #[ORM\Column]
    private ?int $gram_quantity = null;

    public function __toString(): string
    {
        $str = $this->abbreviation ?: $this->name;
        if ($this->gram_quantity !== 1) {
            $str .= " (" . $this->gram_quantity . "g)";
        }
        return $str;
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

    public function getGramQuantity(): ?int
    {
        return $this->gram_quantity;
    }

    public function setGramQuantity(int $gram_quantity): static
    {
        $this->gram_quantity = $gram_quantity;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAbbreviation(): ?string
    {
        return $this->abbreviation;
    }

    /**
     * @param string|null $abbreviation
     */
    public function setAbbreviation(?string $abbreviation): void
    {
        $this->abbreviation = $abbreviation;
    }
}
