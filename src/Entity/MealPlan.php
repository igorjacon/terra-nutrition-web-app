<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Filter\MealPlanDayFilter;
use App\Repository\MealPlanRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MealPlanRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection()
    ],
    normalizationContext: ['groups' => ['meal-plan-read']],
    denormalizationContext: ['groups' => ['meal-plan-write']],
)]
class MealPlan
{
    const DAYS = [
        1 => 'form.day.monday',
        2 => 'form.day.tuesday',
        3 => 'form.day.wednesday',
        4 => 'form.day.thursday',
        5 => 'form.day.friday',
        6 => 'form.day.saturday',
        0 => 'form.day.sunday',
    ];
    use TimestampableEntity, BlameableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['meal-plan-read'])]
    private ?int $id = null;

    #[ORM\Column(type: 'boolean')]
    #[Groups(['meal-plan-read'])]
    protected bool $active = true;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull]
    #[Groups(['meal-plan-read'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['meal-plan-read'])]
    #[ApiFilter(MealPlanDayFilter::class)]
    private ?array $days = null;

    #[ORM\ManyToMany(targetEntity: Meal::class, inversedBy: 'mealPlans', cascade: ['persist', 'remove'])]
    #[Assert\Count(min: 1)]
    #[Groups(['meal-plan-read'])]
    #[ORM\OrderBy(["time" => "ASC"])]
    private Collection $meals;

    #[ORM\ManyToMany(targetEntity: Customer::class, inversedBy: 'mealPlans', cascade: ['persist'])]
    private Collection $customers;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->meals = new ArrayCollection();
        $this->customers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDays(): ?array
    {
        return $this->days;
    }

    public function setDays(?array $days): static
    {
        $this->days = array_values($days);

        return $this;
    }

    public function getMeals(): Collection
    {
        return $this->meals;
    }

    public function addMeal(Meal $meal): static
    {
        if (!$this->meals->contains($meal)) {
            $this->meals->add($meal);
        }

        return $this;
    }

    public function removeMeal(Meal $meal): static
    {
        $this->meals->removeElement($meal);

        return $this;
    }

    public function getCustomers(): Collection
    {
        return $this->customers;
    }

    public function addCustomer(Customer $customer): static
    {
        if (!$this->customers->contains($customer)) {
            $this->customers->add($customer);
        }

        return $this;
    }

    public function removeCustomer(Customer $customer): static
    {
        $this->customers->removeElement($customer);

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }
}
