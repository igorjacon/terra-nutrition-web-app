<?php

namespace App\Entity;

use App\Repository\FoodItemEntryRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FoodItemEntryRepository::class)]
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

    public function totalNutritionalValue(?string $nutrient = "")
    {
        try {
            $nutrientValue = $this->getFoodItem()->getFoodItemDetails()->__get($nutrient);
            $quantity = $this->getQuantity();
            $gramQuantity = $this->getMeasurement()->getGramQuantity();

            if ($nutrientValue != null) {
                return (($quantity * $gramQuantity)/100) * $nutrientValue;
            }
        } catch (\ErrorException $exception) {}

        return 0;
    }

    public function totalCalories() {
        try {
            $kj = $this->getFoodItem()->getFoodItemDetails()->__get("energyWithFibreKj");
            $quantity = $this->getQuantity();
            $gramQuantity = $this->getMeasurement()->getGramQuantity();

            if ($kj != null) {
                $kjTotal = (($quantity * $gramQuantity)/100) * $kj;
                $calories = $kjTotal * 0.239;
                return round($calories, 2);
            }
        } catch (\ErrorException $exception) {}

        return 0;
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
