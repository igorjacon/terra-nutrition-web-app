<?php

namespace App\Entity;

use App\Repository\MealRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MealRepository::class)]
class Meal
{
    use TimestampableEntity, BlameableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['meal-plan-read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull]
    #[Groups(['meal-plan-read'])]
    private ?string $time = null;

    #[ORM\ManyToOne(targetEntity: MealType::class)]
    #[Assert\NotNull]
    #[Groups(['meal-plan-read'])]
    private $type;

    #[ORM\ManyToMany(targetEntity: MealOption::class, inversedBy: 'meals', cascade: ['persist'])]
    #[Assert\Count(min: 1)]
    #[Assert\Valid]
    #[Groups(['meal-plan-read'])]
    private Collection $options;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['meal-plan-read'])]
    private ?string $notes = null;

    #[ORM\ManyToMany(targetEntity: MealPlan::class, mappedBy: 'meals')]
    private Collection $mealPlans;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->options = new ArrayCollection();
        $this->mealPlans = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->type;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTime(): ?string
    {
        return $this->time;
    }

    public function setTime(string $time): static
    {
        $timeParts = explode(":", $time);
        $hour = (int) $timeParts[0];
        $minutes = (int) $timeParts[1];
        $meridian = substr($time, -2);

        if ($meridian === "PM" && $hour !== 12) {
            $hour += 12;
        }

        $formattedTime = sprintf("%02d:%02d", $hour, $minutes);
        $this->time = $formattedTime;

        return $this;
    }

    public function getType(): ?MealType
    {
        return $this->type;
    }

    public function setType(?MealType $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, MealOption>
     */
    public function getOptions(): Collection
    {
        return $this->options;
    }

    public function addOption(MealOption $option): static
    {
        if (!$this->options->contains($option)) {
            $this->options->add($option);
        }

        return $this;
    }

    public function removeOption(MealOption $option): static
    {
        $this->options->removeElement($option);

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

    public function getMealPlans(): Collection
    {
        return $this->mealPlans;
    }

    public function addMealPlan(MealPlan $mealPlan): static
    {
        if (!$this->mealPlans->contains($mealPlan)) {
            $this->mealPlans->add($mealPlan);
            $mealPlan->addMeal($this);
        }

        return $this;
    }

    public function removeMealPlan(MealPlan $mealPlan): static
    {
        if ($this->mealPlans->removeElement($mealPlan)) {
            $mealPlan->removeMeal($this);
        }

        return $this;
    }
}
