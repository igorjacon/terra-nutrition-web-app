<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\FoodItemEntryRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FoodItemEntryRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection()
    ],
    normalizationContext: ['groups' => ['food-item-entry-read']],
    denormalizationContext: ['groups' => ['food-item-entry-write']],
)]
class FoodItemEntry
{
    use TimestampableEntity, BlameableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['meal-plan-read', 'meal-read', 'meal-option-read', 'food-item-entry-read', 'recipe-read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: FoodItem::class, cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'food_key', referencedColumnName: 'food_key', nullable: false)]
    #[Assert\NotNull]
    #[Groups(['meal-plan-read', 'meal-read', 'meal-option-read', 'food-item-entry-read', 'recipe-read'])]
    private ?FoodItem $foodItem = null;

    #[ORM\ManyToOne(targetEntity: FoodMeasurement::class, cascade: ['persist'])]
    #[Assert\NotNull]
    #[Groups(['meal-plan-read', 'meal-read', 'meal-option-read', 'food-item-entry-read', 'recipe-read'])]
    private ?FoodMeasurement $measurement = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['meal-plan-read', 'meal-read', 'meal-option-read', 'food-item-entry-read', 'recipe-read'])]
    #[Assert\NotNull]
    private ?float $quantity = null;

    #[ORM\ManyToOne(targetEntity: MealOption::class, inversedBy: 'foodItemEntries')]
    private ?MealOption $mealOption = null;

    #[ORM\ManyToOne(targetEntity: Recipe::class, inversedBy: 'foodItemEntries')]
    private ?Recipe $recipe = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    public function __toString(): string
    {
        return $this->foodItem->getName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFoodItem(): ?FoodItem
    {
        return $this->foodItem;
    }

    public function setFoodItem(FoodItem $foodItem): static
    {
        $this->foodItem = $foodItem;

        return $this;
    }

    public function getMeasurement(): ?FoodMeasurement
    {
        return $this->measurement;
    }

    public function setMeasurement(?FoodMeasurement $measurement): static
    {
        $this->measurement = $measurement;

        return $this;
    }

    public function getQuantity(): ?float
    {
        return $this->quantity;
    }

    public function setQuantity(?float $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getMealOption(): ?MealOption
    {
        return $this->mealOption;
    }

    public function setMealOption(?MealOption $mealOption): static
    {
        $this->mealOption = $mealOption;

        return $this;
    }

    public function getRecipe(): ?Recipe
    {
        return $this->recipe;
    }

    public function setRecipe(?Recipe $recipe): static
    {
        $this->recipe = $recipe;

        return $this;
    }
}
