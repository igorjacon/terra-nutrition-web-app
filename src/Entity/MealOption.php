<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\MealOptionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MealOptionRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection()
    ],
    normalizationContext: ['groups' => ['meal-option-read']],
    denormalizationContext: ['groups' => ['meal-option-write']],
)]
class MealOption
{
    use TimestampableEntity, BlameableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['meal-plan-read', 'meal-read', 'meal-option-read', 'meal-history-read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'mealOption', targetEntity: FoodItemEntry::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[Groups(['meal-plan-read', 'meal-read', 'meal-option-read'])]
    #[Assert\Valid]
    private Collection $foodItemEntries;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['meal-plan-read', 'meal-read', 'meal-option-read'])]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['meal-plan-read', 'meal-read', 'meal-option-read'])]
    private ?string $notes = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['meal-plan-read', 'meal-read', 'meal-option-read'])]
    private ?float $totalQuantity = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['meal-plan-read', 'meal-read', 'meal-option-read'])]
    private ?float $totalProtein = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['meal-plan-read', 'meal-read', 'meal-option-read'])]
    private ?float $totalCarbs = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['meal-plan-read', 'meal-read', 'meal-option-read'])]
    private ?float $totalFat = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['meal-plan-read', 'meal-read', 'meal-option-read'])]
    private ?float $totalCalories = null;

    #[ORM\ManyToMany(targetEntity: Meal::class, mappedBy: 'options')]
    private Collection $meals;

    #[ORM\ManyToOne(targetEntity: Professional::class)]
    private $professional;

    /**
     * @var Collection<int, MealHistory>
     */
    #[ORM\OneToMany(mappedBy: 'mealOption', targetEntity: MealHistory::class)]
    private Collection $mealHistories;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->foodItemEntries = new ArrayCollection();
        $this->meals = new ArrayCollection();
        $this->mealHistories = new ArrayCollection();
    }

    public function __toString(): string
    {
        if ($this->name) {
            $str = $this->name;
        } else {
            $str = "Option " . $this->id . " -";
            if (count($this->foodItemEntries)) {
                foreach ($this->foodItemEntries as $foodItemEntry) {
                    $str .= ' ' . $foodItemEntry;
                }
            }
        }
        return $str;
    }

    public function totalNutritionalValue(?string $nutrient = "")
    {
        $value = 0;
        try {
            foreach ($this->getFoodItemEntries() as $foodItemEntry) {
                $nutrientValue = $foodItemEntry->getFoodItem()->getFoodItemDetails()->__get($nutrient);
                $quantity = $foodItemEntry->getQuantity();
                $gramQuantity = $foodItemEntry->getMeasurement()->getGramQuantity();

                if ($nutrientValue != null) {
                    $value += (($quantity * $gramQuantity)/100) * $nutrientValue;
                }
            }
        } catch (\ErrorException $exception) {}

        return round($value, 2);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, FoodItemEntry>
     */
    public function getFoodItemEntries(): Collection
    {
        return $this->foodItemEntries;
    }

    public function addFoodItemEntry(FoodItemEntry $foodItemEntry): static
    {
        if (!$this->foodItemEntries->contains($foodItemEntry)) {
            $this->foodItemEntries->add($foodItemEntry);
            $foodItemEntry->setMealOption($this);
        }

        return $this;
    }

    public function removeFoodItemEntry(FoodItemEntry $foodItemEntry): static
    {
        if ($this->foodItemEntries->removeElement($foodItemEntry)) {
            // set the owning side to null (unless already changed)
            if ($foodItemEntry->getMealOption() === $this) {
                $foodItemEntry->setMealOption(null);
            }
        }

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

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): static
    {
        $this->notes = $notes;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getTotalQuantity(): ?float
    {
        return $this->totalQuantity;
    }

    /**
     * @param float|null $totalQuantity
     */
    public function setTotalQuantity(?float $totalQuantity): void
    {
        $this->totalQuantity = $totalQuantity;
    }

    /**
     * @return float|null
     */
    public function getTotalProtein(): ?float
    {
        return $this->totalProtein;
    }

    /**
     * @param float|null $totalProtein
     */
    public function setTotalProtein(?float $totalProtein): void
    {
        $this->totalProtein = $totalProtein;
    }

    /**
     * @return float|null
     */
    public function getTotalCarbs(): ?float
    {
        return $this->totalCarbs;
    }

    /**
     * @param float|null $totalCarbs
     */
    public function setTotalCarbs(?float $totalCarbs): void
    {
        $this->totalCarbs = $totalCarbs;
    }

    /**
     * @return float|null
     */
    public function getTotalFat(): ?float
    {
        return $this->totalFat;
    }

    /**
     * @param float|null $totalFat
     */
    public function setTotalFat(?float $totalFat): void
    {
        $this->totalFat = $totalFat;
    }

    /**
     * @return Collection<int, Meal>
     */
    public function getMeals(): Collection
    {
        return $this->meals;
    }

    public function addMeal(Meal $meal): static
    {
        if (!$this->meals->contains($meal)) {
            $this->meals->add($meal);
            $meal->addOption($this);
        }

        return $this;
    }

    public function removeMeal(Meal $meal): static
    {
        if ($this->meals->removeElement($meal)) {
            $meal->removeOption($this);
        }

        return $this;
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
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return Collection<int, MealHistory>
     */
    public function getMealHistories(): Collection
    {
        return $this->mealHistories;
    }

    public function addMealHistory(MealHistory $mealHistory): static
    {
        if (!$this->mealHistories->contains($mealHistory)) {
            $this->mealHistories->add($mealHistory);
            $mealHistory->setMealOption($this);
        }

        return $this;
    }

    public function removeMealHistory(MealHistory $mealHistory): static
    {
        if ($this->mealHistories->removeElement($mealHistory)) {
            // set the owning side to null (unless already changed)
            if ($mealHistory->getMealOption() === $this) {
                $mealHistory->setMealOption(null);
            }
        }

        return $this;
    }

    /**
     * @return float|null
     */
    public function getTotalCalories(): ?float
    {
        return $this->totalCalories;
    }

    /**
     * @param float|null $totalCalories
     */
    public function setTotalCalories(?float $totalCalories): void
    {
        $this->totalCalories = $totalCalories;
    }
}
