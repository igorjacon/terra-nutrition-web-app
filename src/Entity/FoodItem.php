<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\FoodItemRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FoodItemRepository::class)]
#[ApiResource]
class FoodItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "NONE")]
    #[ORM\Column(type: 'string', length: 10, nullable: false)]
    #[Assert\NotNull]
    #[Assert\Length(max: 10)]
    #[Groups(['meal-plan-read'])]
    private ?string $foodKey = null;

    #[ORM\Column(nullable: true)]
    private ?int $profileId = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $derivation = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull]
    #[Groups(['meal-plan-read'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['meal-plan-read'])]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $samplingDetails = null;

    #[ORM\Column(nullable: true)]
    private ?float $nitrogenFactor = null;

    #[ORM\Column(nullable: true)]
    private ?float $fatFactor = null;

    #[ORM\Column(nullable: true)]
    private ?float $specificGravity = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $analysedPortion = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $unanalysedPortion = null;

    #[ORM\Column(nullable: true)]
    private ?int $classification = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $classificationName = null;

    #[ORM\OneToOne(mappedBy: 'foodItem', cascade: ['persist', 'remove'])]
    #[Groups(['meal-plan-read'])]
    private ?FoodItemDetails $foodItemDetails = null;

    public function __toString(): string
    {
        return $this->name;
    }


    public function getFoodKey(): ?string
    {
        return $this->foodKey;
    }

    public function setFoodKey(?string $foodKey): void
    {
        $this->foodKey = $foodKey;
    }

    public function getProfileId(): ?int
    {
        return $this->profileId;
    }

    public function setProfileId(int $profileId): static
    {
        $this->profileId = $profileId;

        return $this;
    }

    public function getDerivation(): ?string
    {
        return $this->derivation;
    }

    public function setDerivation(?string $derivation): static
    {
        $this->derivation = $derivation;

        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getSamplingDetails(): ?string
    {
        return $this->samplingDetails;
    }

    public function setSamplingDetails(?string $samplingDetails): static
    {
        $this->samplingDetails = $samplingDetails;

        return $this;
    }

    public function getNitrogenFactor(): ?float
    {
        return $this->nitrogenFactor;
    }

    public function setNitrogenFactor(?float $nitrogenFactor): static
    {
        $this->nitrogenFactor = $nitrogenFactor;

        return $this;
    }

    public function getFatFactor(): ?float
    {
        return $this->fatFactor;
    }

    public function setFatFactor(?float $fatFactor): static
    {
        $this->fatFactor = $fatFactor;

        return $this;
    }

    public function getSpecificGravity(): ?float
    {
        return $this->specificGravity;
    }

    public function setSpecificGravity(?float $specificGravity): static
    {
        $this->specificGravity = $specificGravity;

        return $this;
    }

    public function getAnalysedPortion(): ?string
    {
        return $this->analysedPortion;
    }

    public function setAnalysedPortion(?string $analysedPortion): static
    {
        $this->analysedPortion = $analysedPortion;

        return $this;
    }

    public function getUnanalysedPortion(): ?string
    {
        return $this->unanalysedPortion;
    }

    public function setUnanalysedPortion(?string $unanalysedPortion): static
    {
        $this->unanalysedPortion = $unanalysedPortion;

        return $this;
    }

    public function getClassification(): ?int
    {
        return $this->classification;
    }

    public function setClassification(?int $classification): static
    {
        $this->classification = $classification;

        return $this;
    }

    public function getClassificationName(): ?string
    {
        return $this->classificationName;
    }

    public function setClassificationName(?string $classificationName): static
    {
        $this->classificationName = $classificationName;

        return $this;
    }

    public function getFoodItemDetails(): ?FoodItemDetails
    {
        return $this->foodItemDetails;
    }

    public function setFoodItemDetails(FoodItemDetails $foodItemDetails): static
    {
        // set the owning side of the relation if necessary
        if ($foodItemDetails->getFoodItem() !== $this) {
            $foodItemDetails->setFoodItem($this);
        }

        $this->foodItemDetails = $foodItemDetails;

        return $this;
    }
}
