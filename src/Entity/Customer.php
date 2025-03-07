<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Repository\CustomerRepository;
use App\State\CustomerProvider;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CustomerRepository::class)]
#[ApiResource(
    operations: [
        new Get(provider: CustomerProvider::class),
        new GetCollection(),
        new Post(),
        new Delete(provider: CustomerProvider::class)
    ],
    normalizationContext: ['groups' => ['customer-read']],
    denormalizationContext: ['groups' => ['customer-write']],
)]
class Customer
{
    use TimestampableEntity, BlameableEntity;

    const GENDERS = [
        'male' => 'form.gender.male',
        'female' => 'form.gender.female',
    ];

    #[ORM\Id]
    #[ORM\OneToOne(targetEntity: User::class, inversedBy: 'customer', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: 'id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    #[Assert\Valid]
    #[ApiProperty(identifier: false)]
    #[Groups(['customer-read', 'customer-write'])]
    private $user;

    #[ORM\Column(length: 10, nullable: true)]
    #[Groups(['customer-read', 'customer-write'])]
    private ?string $height = null;

    #[ORM\Column(length: 10, nullable: true)]
    #[Groups(['customer-read', 'customer-write'])]
    private ?string $weight = null;

    #[ORM\Column(type: 'date', nullable: true)]
    #[Groups(['customer-read', 'customer-write'])]
    private ?\DateTime $dob = null;

    #[ORM\Column(type: 'string', nullable: true)]
    #[Groups(['customer-read', 'customer-write'])]
    private ?string $gender = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['customer-read', 'customer-write'])]
    private ?string $goalWeight = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['customer-read', 'customer-write'])]
    private ?string $occupation = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['customer-read', 'customer-write'])]
    private ?string $dietaryPreference = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['customer-read', 'customer-write'])]
    private ?string $goals = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['customer-read', 'customer-write'])]
    private ?string $reasonSeekProfessional = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['customer-read', 'customer-write'])]
    private ?string $currExerciseRoutine = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['customer-read', 'customer-write'])]
    private ?string $medicalInfo = null;

    #[ORM\Column(type: 'boolean')]
    #[Groups(['customer-read'])]
    private bool $registrationComplete = false;

    #[ORM\ManyToOne(targetEntity: Professional::class, inversedBy: 'customers')]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    #[Groups(['customer-read'])]
    private $professional;

    #[ORM\ManyToMany(targetEntity: MealPlan::class, mappedBy: 'customers')]
    private Collection $mealPlans;

    #[ORM\OneToMany(targetEntity: CustomerMeasurement::class, mappedBy: 'customer', cascade: ['persist', 'remove'])]
    private Collection $measurements;

    #[ORM\ManyToMany(targetEntity: Recipe::class, mappedBy: 'customers')]
    private Collection $recipes;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['customer-read', 'customer-write'])]
    private ?string $deviceToken = null;

    /**
     * @var Collection<int, MealHistory>
     */
    #[ORM\OneToMany(mappedBy: 'customer', targetEntity: MealHistory::class)]
    private Collection $mealHistories;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->mealPlans = new ArrayCollection();
        $this->measurements = new ArrayCollection();
        $this->recipes = new ArrayCollection();
        $this->mealHistories = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->user->getFirstName() . " " . $this->user->getLastName();
    }

    #[ApiProperty(identifier: true)]
    public function getId(): int
    {
        return $this->user->getId();
    }
    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user): void
    {
        $this->user = $user;
    }

    public function getHeight(): ?string
    {
        return $this->height;
    }

    public function setHeight(?string $height): static
    {
        $this->height = $height;

        return $this;
    }

    public function getWeight(): ?string
    {
        return $this->weight;
    }

    public function setWeight(?string $weight): static
    {
        $this->weight = $weight;

        return $this;
    }

    public function getDob(): ?\DateTime
    {
        return $this->dob;
    }

    public function setDob(?\DateTime $dob): static
    {
        $this->dob = $dob;

        return $this;
    }

    public function getGoalWeight(): ?string
    {
        return $this->goalWeight;
    }

    public function setGoalWeight(?string $goalWeight): static
    {
        $this->goalWeight = $goalWeight;

        return $this;
    }

    public function getOccupation(): ?string
    {
        return $this->occupation;
    }

    public function setOccupation(?string $occupation): static
    {
        $this->occupation = $occupation;

        return $this;
    }

    public function getDietaryPreference(): ?string
    {
        return $this->dietaryPreference;
    }

    public function setDietaryPreference(?string $dietaryPreference): static
    {
        $this->dietaryPreference = $dietaryPreference;

        return $this;
    }

    public function getGoals(): ?string
    {
        return $this->goals;
    }

    public function setGoals(?string $goals): static
    {
        $this->goals = $goals;

        return $this;
    }

    public function getReasonSeekProfessional(): ?string
    {
        return $this->reasonSeekProfessional;
    }

    public function setReasonSeekProfessional(?string $reasonSeekProfessional): static
    {
        $this->reasonSeekProfessional = $reasonSeekProfessional;

        return $this;
    }

    public function getCurrExerciseRoutine(): ?string
    {
        return $this->currExerciseRoutine;
    }

    public function setCurrExerciseRoutine(?string $currExerciseRoutine): static
    {
        $this->currExerciseRoutine = $currExerciseRoutine;

        return $this;
    }

    public function getMedicalInfo(): ?string
    {
        return $this->medicalInfo;
    }

    public function setMedicalInfo(?string $medicalInfo): static
    {
        $this->medicalInfo = $medicalInfo;

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
            $mealPlan->addCustomer($this);
        }

        return $this;
    }

    public function removeMealPlan(MealPlan $mealPlan): static
    {
        if ($this->mealPlans->removeElement($mealPlan)) {
            $mealPlan->removeCustomer($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, CustomerMeasurement>
     */
    public function getMeasurements(): Collection
    {
        return $this->measurements;
    }

    public function addMeasurement(CustomerMeasurement $measurement): static
    {
        if (!$this->measurements->contains($measurement)) {
            $this->measurements->add($measurement);
            $measurement->setCustomer($this);
        }

        return $this;
    }

    public function removeMeasurement(CustomerMeasurement $measurement): static
    {
        if ($this->measurements->removeElement($measurement)) {
            // set the owning side to null (unless already changed)
            if ($measurement->getCustomer() === $this) {
                $measurement->setCustomer(null);
            }
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isRegistrationComplete(): bool
    {
        return $this->registrationComplete;
    }

    /**
     * @param bool $registrationComplete
     */
    public function setRegistrationComplete(bool $registrationComplete): void
    {
        $this->registrationComplete = $registrationComplete;
    }

    /**
     * @return Professional|null
     */
    public function getProfessional(): ?Professional
    {
        return $this->professional;
    }

    /**
     * @param Professional|null $professional
     */
    public function setProfessional(?Professional $professional): void
    {
        $this->professional = $professional;
    }

    /**
     * @return Collection<int, Recipe>
     */
    public function getRecipes(): Collection
    {
        return $this->recipes;
    }

    public function addRecipe(Recipe $recipe): static
    {
        if (!$this->recipes->contains($recipe)) {
            $this->recipes->add($recipe);
            $recipe->addCustomer($this);
        }

        return $this;
    }

    public function removeRecipe(Recipe $recipe): static
    {
        if ($this->recipes->removeElement($recipe)) {
            $recipe->removeCustomer($this);
        }

        return $this;
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
            $mealHistory->setCustomer($this);
        }

        return $this;
    }

    public function removeMealHistory(MealHistory $mealHistory): static
    {
        if ($this->mealHistories->removeElement($mealHistory)) {
            // set the owning side to null (unless already changed)
            if ($mealHistory->getCustomer() === $this) {
                $mealHistory->setCustomer(null);
            }
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDeviceToken(): ?string
    {
        return $this->deviceToken;
    }

    /**
     * @param string|null $deviceToken
     */
    public function setDeviceToken(?string $deviceToken): void
    {
        $this->deviceToken = $deviceToken;
    }

    /**
     * @return string|null
     */
    public function getGender(): ?string
    {
        return $this->gender;
    }

    /**
     * @param string|null $gender
     */
    public function setGender(?string $gender): void
    {
        $this->gender = $gender;
    }
}
