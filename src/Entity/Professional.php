<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\ProfessionalRepository;
use App\State\ProfessionalProvider;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProfessionalRepository::class)]
#[ApiResource(
    operations: [
        new Get(provider: ProfessionalProvider::class),
        new GetCollection()
    ],
    normalizationContext: ['groups' => ['professional-read']],
    denormalizationContext: ['groups' => ['professional-write']],
)]
class Professional
{
    #[ORM\Id]
    #[ORM\OneToOne(targetEntity: User::class, inversedBy: 'professional', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: 'id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    #[Assert\Valid]
    #[ApiProperty(identifier: false)]
    #[Groups(['professional-read', 'customer-read'])]
    private $user;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255)]
    #[Groups(['professional-read', 'customer-read'])]
    private ?string $website = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255)]
    #[Groups(['professional-read', 'customer-read'])]
    private ?string $jobTitle = null;

    #[ORM\Column(nullable: true)]
    private ?string $taxNumber = null;

    #[ORM\OneToMany(targetEntity: Customer::class, mappedBy: 'professional', cascade: ['persist', 'remove'])]
    private Collection $customers;

    #[ORM\OneToMany(targetEntity: Location::class, mappedBy: 'professional', cascade: ['persist', 'remove'])]
    #[Assert\Valid]
    #[Groups(['professional-read', 'customer-read'])]
    private Collection $locations;

    #[ORM\OneToMany(targetEntity: MealPlan::class, mappedBy: 'professional')]
    private Collection $mealPlans;

    public function __construct()
    {
        $this->customers = new ArrayCollection();
        $this->locations = new ArrayCollection();
        $this->mealPlans = new ArrayCollection();
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

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): static
    {
        $this->website = $website;

        return $this;
    }

    public function getJobTitle(): ?string
    {
        return $this->jobTitle;
    }

    public function setJobTitle(?string $jobTitle): static
    {
        $this->jobTitle = $jobTitle;

        return $this;
    }

    public function getTaxNumber(): ?string
    {
        return $this->taxNumber;
    }

    public function setTaxNumber(?string $taxNumber): static
    {
        $this->taxNumber = $taxNumber;

        return $this;
    }

    /**
     * @return Collection<int, Location>
     */
    public function getLocations(): Collection
    {
        return $this->locations;
    }

    public function addLocation(Location $location): static
    {
        if (!$this->locations->contains($location)) {
            $this->locations->add($location);
            $location->setProfessional($this);
        }

        return $this;
    }

    public function removeLocation(Location $location): static
    {
        if ($this->locations->removeElement($location)) {
            // set the owning side to null (unless already changed)
            if ($location->getProfessional() === $this) {
                $location->setProfessional(null);
            }
        }

        return $this;
    }

    public function getCustomers(): Collection
    {
        return $this->customers;
    }

    public function AddCustomer(Customer $customer): static
    {
        if (!$this->customers->contains($customer)) {
            $this->customers->add($customer);
            $customer->setProfessional($this);
        }

        return $this;
    }

    public function removeCustomer(Customer $customer): static
    {
        if ($this->customers->removeElement($customer)) {
            // set the owning side to null (unless already changed)
            if ($customer->getProfessional() === $this) {
                $customer->setProfessional(null);
            }
        }

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
            $mealPlan->setProfessional($this);
        }

        return $this;
    }

    public function removeMealPlan(MealPlan $mealPlan): static
    {
        $this->mealPlans->removeElement($mealPlan);
        return $this;
    }
}
