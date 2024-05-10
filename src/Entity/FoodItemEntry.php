<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\FoodItemEntryRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FoodItemEntryRepository::class)]
#[ApiResource]
class FoodItemEntry
{
    use TimestampableEntity, BlameableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(targetEntity: FoodItem::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: 'food_key', referencedColumnName: 'food_key', nullable: false)]
    #[Assert\NotNull]
    private ?FoodItem $foodItem = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $measurement = null;

    #[ORM\Column(nullable: true)]
    private ?float $quantity = null;

    #[ORM\ManyToOne(targetEntity: MealOption::class, inversedBy: 'foodItemEntries')]
    #[ORM\JoinColumn(nullable: false)]
    private ?MealOption $mealOption = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
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

    public function getMeasurement(): ?string
    {
        return $this->measurement;
    }

    public function setMeasurement(?string $measurement): static
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
}
