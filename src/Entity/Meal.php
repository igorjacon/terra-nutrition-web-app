<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
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
#[ApiResource]
class Meal
{
    use TimestampableEntity, BlameableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull]
    #[Groups(['meal-plan-read'])]
    private ?string $time = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull]
    #[Groups(['meal-plan-read'])]
    private ?string $type = null;

    #[ORM\ManyToMany(targetEntity: MealOption::class, inversedBy: 'meals', cascade: ['persist', 'remove'])]
    #[Assert\Count(min: 1)]
    #[Groups(['meal-plan-read'])]
    private Collection $options;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['meal-plan-read'])]
    private ?string $notes = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->options = new ArrayCollection();
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
        $this->time = $time;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
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
}
