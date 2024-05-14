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

#[ORM\Entity(repositoryClass: MealOptionRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection()
    ],
    normalizationContext: ['groups' => ['meal-option-read']],
    denormalizationContext: ['groups' => ['meal-option-write']],)]
class MealOption
{
    use TimestampableEntity, BlameableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['meal-plan-read', 'meal-read', 'meal-option-read'])]
    private ?int $id = null;

    #[ORM\OneToMany(targetEntity: FoodItemEntry::class, mappedBy: 'mealOption', cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[Groups(['meal-plan-read', 'meal-read', 'meal-option-read'])]
    private Collection $foodItemEntries;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['meal-plan-read', 'meal-read', 'meal-option-read'])]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['meal-plan-read', 'meal-read', 'meal-option-read'])]
    private ?string $notes = null;

    #[ORM\ManyToMany(targetEntity: Meal::class, mappedBy: 'options')]
    private Collection $meals;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->foodItemEntries = new ArrayCollection();
        $this->meals = new ArrayCollection();
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
}
