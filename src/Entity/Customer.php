<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\CustomerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CustomerRepository::class)]
#[ApiResource]
class Customer
{
    use TimestampableEntity, BlameableEntity;

    #[ORM\Id]
    #[ORM\OneToOne(targetEntity: User::class, inversedBy: 'customer', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: 'id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    #[Assert\Valid]
    private $user;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $height = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $weight = null;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTime $dob = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $goalWeight = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $occupation = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $dietaryPreference = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $goals = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $reasonSeekProfessional = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $currExerciseRoutine = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $medicalInfo = null;

    #[ORM\Column(type: 'boolean')]
    private bool $registrationComplete = false;

    #[ORM\ManyToOne(targetEntity: Professional::class, inversedBy: 'customers')]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private $professional;

    #[ORM\OneToMany(targetEntity: Measurement::class, mappedBy: 'customer', cascade: ['persist', 'remove'])]
    private Collection $measurements;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->measurements = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->user->getFirstName() . " " . $this->user->getLastName();
    }

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

    /**
     * @return Collection<int, Measurement>
     */
    public function getMeasurements(): Collection
    {
        return $this->measurements;
    }

    public function addMeasurement(Measurement $measurement): static
    {
        if (!$this->measurements->contains($measurement)) {
            $this->measurements->add($measurement);
            $measurement->setCustomer($this);
        }

        return $this;
    }

    public function removeMeasurement(Measurement $measurement): static
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
}
