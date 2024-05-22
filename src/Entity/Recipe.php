<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RecipeRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection()
    ],
    normalizationContext: ['groups' => ['recipe-read']],
    denormalizationContext: ['groups' => ['recipe-write']],
)]
class Recipe
{
    use TimestampableEntity, BlameableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['recipe-read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['recipe-read'])]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['recipe-read'])]
    private ?int $portion = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['recipe-read'])]
    private ?string $instructions = null;

    #[ORM\OneToMany(targetEntity: FoodItemEntry::class, mappedBy: 'recipe', cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[Assert\Valid]
    #[Groups(['recipe-read'])]
    private Collection $foodItemEntries;

    #[ORM\ManyToMany(targetEntity: Customer::class, inversedBy: 'recipes', cascade: ['persist'])]
    private Collection $customers;

    #[ORM\ManyToOne(targetEntity: Professional::class, cascade: ['persist'], inversedBy: 'recipes')]
    #[Assert\NotNull]
    private $professional;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->foodItemEntries = new ArrayCollection();
        $this->customers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPortion(): ?int
    {
        return $this->portion;
    }

    public function setPortion(?int $portion): static
    {
        $this->portion = $portion;

        return $this;
    }

    public function getInstructions(): ?string
    {
        return $this->instructions;
    }

    public function setInstructions(?string $instructions): static
    {
        $this->instructions = $instructions;

        return $this;
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
            $foodItemEntry->setRecipe($this);
        }

        return $this;
    }

    public function removeFoodItemEntry(FoodItemEntry $foodItemEntry): static
    {
        if ($this->foodItemEntries->removeElement($foodItemEntry)) {
            // set the owning side to null (unless already changed)
            if ($foodItemEntry->getRecipe() === $this) {
                $foodItemEntry->setRecipe(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Customer>
     */
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
}
