<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\FoodItemDetailsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: FoodItemDetailsRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection()
    ],
    normalizationContext: ['groups' => ['food-item-detail-read']],
    denormalizationContext: ['groups' => ['food-item-detail-write']],
)]
class FoodItemDetails
{
    #[ORM\Id]
    #[ORM\OneToOne(targetEntity: FoodItem::class, inversedBy: 'foodItemDetails', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: 'food_key', referencedColumnName: 'food_key', nullable: false, onDelete: 'CASCADE')]
    private ?FoodItem $foodItem = null;

    #[ORM\Column]
    private ?int $classification = null;

    #[ORM\Column(length: 255)]
    #[Groups(['meal-plan-read', 'meal-read', 'meal-option-read', 'food-item-entry-read', 'food-item-read', 'food-item-detail-read', 'recipe-read'])]
    private ?string $foodName = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['meal-plan-read', 'meal-read', 'meal-option-read', 'food-item-entry-read', 'food-item-read', 'food-item-detail-read', 'recipe-read'])]
    private ?float $energyWithFibreKj = null;

    #[ORM\Column(nullable: true)]
    private ?float $energyWithoutFibreKj = null;

    #[ORM\Column(nullable: true)]
    private ?float $water = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['meal-plan-read', 'meal-read', 'meal-option-read', 'food-item-entry-read', 'food-item-read', 'food-item-detail-read', 'recipe-read'])]
    private ?float $protein = null;

    #[ORM\Column(nullable: true)]
    private ?float $nitrogen = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['meal-plan-read', 'meal-read', 'meal-option-read', 'food-item-entry-read', 'food-item-read', 'food-item-detail-read', 'recipe-read'])]
    private ?float $fat = null;

    #[ORM\Column(nullable: true)]
    private ?float $ash = null;

    #[ORM\Column(nullable: true)]
    private ?float $fibre = null;

    #[ORM\Column(nullable: true)]
    private ?float $alcohol = null;

    #[ORM\Column(nullable: true)]
    private ?float $fructose = null;

    #[ORM\Column(nullable: true)]
    private ?float $glucose = null;

    #[ORM\Column(nullable: true)]
    private ?float $sucrose = null;

    #[ORM\Column(nullable: true)]
    private ?float $maltose = null;

    #[ORM\Column(nullable: true)]
    private ?float $lactose = null;

    #[ORM\Column(nullable: true)]
    private ?float $galactose = null;

    #[ORM\Column(nullable: true)]
    private ?float $maltotrios = null;

    #[ORM\Column(nullable: true)]
    private ?float $totalSugar = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['meal-plan-read', 'meal-read', 'meal-option-read', 'food-item-entry-read', 'food-item-read', 'food-item-detail-read', 'recipe-read'])]
    private ?float $calcium = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['meal-plan-read', 'meal-read', 'meal-option-read', 'food-item-entry-read', 'food-item-read', 'food-item-detail-read', 'recipe-read'])]
    private ?float $iron = null;

    #[ORM\Column(nullable: true)]
    private ?float $leadPb = null;

    #[ORM\Column(nullable: true)]
    private ?float $magnesium = null;

    #[ORM\Column(nullable: true)]
    private ?float $manganese = null;

    #[ORM\Column(nullable: true)]
    private ?float $phosphorus = null;

    #[ORM\Column(nullable: true)]
    private ?float $potassium = null;

    #[ORM\Column(nullable: true)]
    private ?float $selenium = null;

    #[ORM\Column(nullable: true)]
    private ?float $sodium = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['meal-plan-read', 'meal-read', 'meal-option-read', 'food-item-entry-read', 'food-item-read', 'food-item-detail-read', 'recipe-read'])]
    private ?float $vitaminAretinol = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['meal-plan-read', 'meal-read', 'meal-option-read', 'food-item-entry-read', 'food-item-read', 'food-item-detail-read', 'recipe-read'])]
    private ?float $vitaminB1thiamin = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['meal-plan-read', 'meal-read', 'meal-option-read', 'food-item-entry-read', 'food-item-read', 'food-item-detail-read', 'recipe-read'])]
    private ?float $vitaminB2riboflavin = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['meal-plan-read', 'meal-read', 'meal-option-read', 'food-item-entry-read', 'food-item-read', 'food-item-detail-read', 'recipe-read'])]
    private ?float $vitaminB3niacin = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['meal-plan-read', 'meal-read', 'meal-option-read', 'food-item-entry-read', 'food-item-read', 'food-item-detail-read', 'recipe-read'])]
    private ?float $vitaminB6pyridoxine = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['meal-plan-read', 'meal-read', 'meal-option-read', 'food-item-entry-read', 'food-item-read', 'food-item-detail-read', 'recipe-read'])]
    private ?float $vitaminB12cobalamin = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['meal-plan-read', 'meal-read', 'meal-option-read', 'food-item-entry-read', 'food-item-read', 'food-item-detail-read', 'recipe-read'])]
    private ?float $vitaminC = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['meal-plan-read', 'meal-read', 'meal-option-read', 'food-item-entry-read', 'food-item-read', 'food-item-detail-read', 'recipe-read'])]
    private ?float $vitaminE = null;

    #[ORM\Column(nullable: true)]
    private ?float $saturatedFattyAcids = null;

    #[ORM\Column(nullable: true)]
    private ?float $monoSaturatedFattyAcids = null;

    #[ORM\Column(nullable: true)]
    private ?float $polySaturatedFattyAcids = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['meal-plan-read', 'meal-read', 'meal-option-read', 'food-item-entry-read', 'food-item-read', 'food-item-detail-read', 'recipe-read'])]
    private ?float $zinc = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['meal-plan-read', 'meal-read', 'meal-option-read', 'food-item-entry-read', 'food-item-read', 'food-item-detail-read', 'recipe-read'])]
    private ?float $vitaminB7biotin = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['meal-plan-read', 'meal-read', 'meal-option-read', 'food-item-entry-read', 'food-item-read', 'food-item-detail-read', 'recipe-read'])]
    private ?float $carbohydrate = null;

    public function __get($propertyName) {
        return $this->{$propertyName};
    }

    public function getFoodItem(): ?FoodItem
    {
        return $this->foodItem;
    }

    public function setFoodItem(FoodItem $foodItem): static
    {
        $this->foodItem = $foodItem;

        return $this;
    }

    public function getClassification(): ?int
    {
        return $this->classification;
    }

    public function setClassification(int $classification): static
    {
        $this->classification = $classification;

        return $this;
    }

    public function getFoodName(): ?string
    {
        return $this->foodName;
    }

    public function setFoodName(string $foodName): static
    {
        $this->foodName = $foodName;

        return $this;
    }

    public function getEnergyWithFibreKj(): ?float
    {
        return $this->energyWithFibreKj;
    }

    public function setEnergyWithFibreKj(?float $energyWithFibreKj): static
    {
        $this->energyWithFibreKj = $energyWithFibreKj;

        return $this;
    }

    public function getEnergyWithoutFibreKj(): ?float
    {
        return $this->energyWithoutFibreKj;
    }

    public function setEnergyWithoutFibreKj(?float $energyWithoutFibreKj): static
    {
        $this->energyWithoutFibreKj = $energyWithoutFibreKj;

        return $this;
    }

    public function getWater(): ?float
    {
        return $this->water;
    }

    public function setWater(?float $water): static
    {
        $this->water = $water;

        return $this;
    }

    public function getProtein(): ?float
    {
        return $this->protein;
    }

    public function setProtein(?float $protein): static
    {
        $this->protein = $protein;

        return $this;
    }

    public function getNitrogen(): ?float
    {
        return $this->nitrogen;
    }

    public function setNitrogen(?float $nitrogen): static
    {
        $this->nitrogen = $nitrogen;

        return $this;
    }

    public function getFat(): ?float
    {
        return $this->fat;
    }

    public function setFat(?float $fat): static
    {
        $this->fat = $fat;

        return $this;
    }

    public function getAsh(): ?float
    {
        return $this->ash;
    }

    public function setAsh(?float $ash): static
    {
        $this->ash = $ash;

        return $this;
    }

    public function getFibre(): ?float
    {
        return $this->fibre;
    }

    public function setFibre(?float $fibre): static
    {
        $this->fibre = $fibre;

        return $this;
    }

    public function getAlcohol(): ?float
    {
        return $this->alcohol;
    }

    public function setAlcohol(?float $alcohol): static
    {
        $this->alcohol = $alcohol;

        return $this;
    }

    public function getFructose(): ?float
    {
        return $this->fructose;
    }

    public function setFructose(?float $fructose): static
    {
        $this->fructose = $fructose;

        return $this;
    }

    public function getGlucose(): ?float
    {
        return $this->glucose;
    }

    public function setGlucose(?float $glucose): static
    {
        $this->glucose = $glucose;

        return $this;
    }

    public function getSucrose(): ?float
    {
        return $this->sucrose;
    }

    public function setSucrose(?float $sucrose): static
    {
        $this->sucrose = $sucrose;

        return $this;
    }

    public function getMaltose(): ?float
    {
        return $this->maltose;
    }

    public function setMaltose(?float $maltose): static
    {
        $this->maltose = $maltose;

        return $this;
    }

    public function getLactose(): ?float
    {
        return $this->lactose;
    }

    public function setLactose(?float $lactose): static
    {
        $this->lactose = $lactose;

        return $this;
    }

    public function getGalactose(): ?float
    {
        return $this->galactose;
    }

    public function setGalactose(?float $galactose): static
    {
        $this->galactose = $galactose;

        return $this;
    }

    public function getMaltotrios(): ?float
    {
        return $this->maltotrios;
    }

    public function setMaltotrios(?float $maltotrios): static
    {
        $this->maltotrios = $maltotrios;

        return $this;
    }

    public function getTotalSugar(): ?float
    {
        return $this->totalSugar;
    }

    public function setTotalSugar(?float $totalSugar): static
    {
        $this->totalSugar = $totalSugar;

        return $this;
    }

    public function getCalcium(): ?float
    {
        return $this->calcium;
    }

    public function setCalcium(?float $calcium): static
    {
        $this->calcium = $calcium;

        return $this;
    }

    public function getIron(): ?float
    {
        return $this->iron;
    }

    public function setIron(?float $iron): static
    {
        $this->iron = $iron;

        return $this;
    }

    public function getLeadPb(): ?float
    {
        return $this->leadPb;
    }

    public function setLeadPb(?float $lead): static
    {
        $this->leadPb = $lead;

        return $this;
    }

    public function getMagnesium(): ?float
    {
        return $this->magnesium;
    }

    public function setMagnesium(?float $magnesium): static
    {
        $this->magnesium = $magnesium;

        return $this;
    }

    public function getManganese(): ?float
    {
        return $this->manganese;
    }

    public function setManganese(?float $manganese): static
    {
        $this->manganese = $manganese;

        return $this;
    }

    public function getPhosphorus(): ?float
    {
        return $this->phosphorus;
    }

    public function setPhosphorus(?float $phosphorus): static
    {
        $this->phosphorus = $phosphorus;

        return $this;
    }

    public function getPotassium(): ?float
    {
        return $this->potassium;
    }

    public function setPotassium(?float $potassium): static
    {
        $this->potassium = $potassium;

        return $this;
    }

    public function getSelenium(): ?float
    {
        return $this->selenium;
    }

    public function setSelenium(?float $selenium): static
    {
        $this->selenium = $selenium;

        return $this;
    }

    public function getSodium(): ?float
    {
        return $this->sodium;
    }

    public function setSodium(?float $sodium): static
    {
        $this->sodium = $sodium;

        return $this;
    }

    public function getVitaminAretinol(): ?float
    {
        return $this->vitaminAretinol;
    }

    public function setVitaminAretinol(?float $vitaminAretinol): static
    {
        $this->vitaminAretinol = $vitaminAretinol;

        return $this;
    }

    public function getVitaminB1thiamin(): ?float
    {
        return $this->vitaminB1thiamin;
    }

    public function setVitaminB1thiamin(?float $vitaminB1thiamin): static
    {
        $this->vitaminB1thiamin = $vitaminB1thiamin;

        return $this;
    }

    public function getVitaminB2riboflavin(): ?float
    {
        return $this->vitaminB2riboflavin;
    }

    public function setVitaminB2riboflavin(?float $vitaminB2riboflavin): static
    {
        $this->vitaminB2riboflavin = $vitaminB2riboflavin;

        return $this;
    }

    public function getVitaminB3niacin(): ?float
    {
        return $this->vitaminB3niacin;
    }

    public function setVitaminB3niacin(?float $vitaminB3niacin): static
    {
        $this->vitaminB3niacin = $vitaminB3niacin;

        return $this;
    }

    public function getVitaminB6pyridoxine(): ?float
    {
        return $this->vitaminB6pyridoxine;
    }

    public function setVitaminB6pyridoxine(?float $vitaminB6pyridoxine): static
    {
        $this->vitaminB6pyridoxine = $vitaminB6pyridoxine;

        return $this;
    }

    public function getVitaminB12cobalamin(): ?float
    {
        return $this->vitaminB12cobalamin;
    }

    public function setVitaminB12cobalamin(?float $vitaminB12cobalamin): static
    {
        $this->vitaminB12cobalamin = $vitaminB12cobalamin;

        return $this;
    }

    public function getVitaminC(): ?float
    {
        return $this->vitaminC;
    }

    public function setVitaminC(?float $vitaminC): static
    {
        $this->vitaminC = $vitaminC;

        return $this;
    }

    public function getVitaminE(): ?float
    {
        return $this->vitaminE;
    }

    public function setVitaminE(?float $vitaminE): static
    {
        $this->vitaminE = $vitaminE;

        return $this;
    }

    public function getSaturatedFattyAcids(): ?float
    {
        return $this->saturatedFattyAcids;
    }

    public function setSaturatedFattyAcids(?float $saturatedFattyAcids): static
    {
        $this->saturatedFattyAcids = $saturatedFattyAcids;

        return $this;
    }

    public function getMonoSaturatedFattyAcids(): ?float
    {
        return $this->monoSaturatedFattyAcids;
    }

    public function setMonoSaturatedFattyAcids(?float $monoSaturatedFattyAcids): static
    {
        $this->monoSaturatedFattyAcids = $monoSaturatedFattyAcids;

        return $this;
    }

    public function getPolySaturatedFattyAcids(): ?float
    {
        return $this->polySaturatedFattyAcids;
    }

    public function setPolySaturatedFattyAcids(?float $polySaturatedFattyAcids): static
    {
        $this->polySaturatedFattyAcids = $polySaturatedFattyAcids;

        return $this;
    }

    public function getZinc(): ?float
    {
        return $this->zinc;
    }

    public function setZinc(?float $zinc): static
    {
        $this->zinc = $zinc;

        return $this;
    }

    public function getVitaminB7biotin(): ?float
    {
        return $this->vitaminB7biotin;
    }

    public function setVitaminB7biotin(?float $vitaminB7biotin): static
    {
        $this->vitaminB7biotin = $vitaminB7biotin;

        return $this;
    }

    public function getCarbohydrate(): ?float
    {
        return $this->carbohydrate;
    }

    public function setCarbohydrate(?float $carbohydrate): static
    {
        $this->carbohydrate = $carbohydrate;

        return $this;
    }
}
