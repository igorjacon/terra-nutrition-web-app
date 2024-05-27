<?php

namespace App\Entity;


use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\API\SaveMealOption;
use App\Filter\MealHistoryDateFilter;
use App\Repository\MealHistoryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ApiResource(
    operations: [
        new GetCollection(),
        new Post(
            uriTemplate: '/meal-history/new',
            controller: SaveMealOption::class,
            read: false,
            name: 'save_meal_option'
        )
    ],
    normalizationContext: ['groups' => ['meal-history-read']],
    denormalizationContext: ['groups' => ['meal-history-write']]
)]
#[ORM\Entity(repositoryClass: MealHistoryRepository::class)]
class MealHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['meal-history-read'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[ApiFilter(MealHistoryDateFilter::class)]
    #[Groups(['meal-history-read'])]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(targetEntity: Customer::class, inversedBy: 'mealHistories')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['meal-history-read'])]
    private ?Customer $customer = null;

    #[ORM\ManyToOne(targetEntity: Meal::class, inversedBy: 'mealHistories')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['meal-history-read'])]
    private ?Meal $meal = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['meal-history-read'])]
    private ?string $time = null;

    #[ORM\ManyToOne(targetEntity: MealOption::class, inversedBy: 'mealHistories')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['meal-history-read'])]
    private ?MealOption $mealOption = null;

    #[ORM\ManyToOne(targetEntity: MealPlan::class)]
    #[Groups(['meal-history-read'])]
    private ?MealPlan $mealPlan = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): static
    {
        $this->customer = $customer;

        return $this;
    }

    public function getMeal(): ?Meal
    {
        return $this->meal;
    }

    public function setMeal(?Meal $meal): static
    {
        $this->meal = $meal;

        return $this;
    }

    public function getTime(): ?string
    {
        return $this->time;
    }

    public function setTime(?string $time): static
    {
        $this->time = $time;

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

    public function getMealPlan(): ?MealPlan
    {
        return $this->mealPlan;
    }

    public function setMealPlan(?MealPlan $mealPlan): static
    {
        $this->mealPlan = $mealPlan;

        return $this;
    }
}
