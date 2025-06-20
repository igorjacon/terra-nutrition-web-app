<?php

namespace App\Entity;

use App\Repository\MeasurementRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MeasurementRepository::class)]
class CustomerMeasurement
{
    use TimestampableEntity, BlameableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'text')]
    #[Assert\NotNull]
    private string $description;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotNull]
    private ?string $height;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotNull]
    private ?string $currWeight = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $idealWeight = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $rightArmRelax = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $leftArmRelax = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $rightArmContracted = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $leftArmContracted = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $rightForearm = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $leftForearm = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $rightWrist = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $leftWrist = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $neck = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $shoulder = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $breastplate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $waist = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $abs = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $hip = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $rightCalf = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $leftCalf = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $rightThigh = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $leftThigh = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $rightProximalThigh = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $leftProximalThigh = null;

    // BMI REQUIRED FIELDS
    #[ORM\Column(nullable: true)]
    private ?float $chest = null;
    #[ORM\Column(nullable: true)]
    private ?float $abdomen = null;
    #[ORM\Column(nullable: true)]
    private ?float $thigh = null;
    #[ORM\Column(nullable: true)]
    private ?float $triceps = null;
    #[ORM\Column(nullable: true)]
    private ?float $biceps = null;
    #[ORM\Column(nullable: true)]
    private ?float $suprailiac = null;
    #[ORM\Column(nullable: true)]
    private ?float $subscapular = null;
    #[ORM\Column(nullable: true)]
    private ?float $midaxillary = null;

    // BMI results
    #[ORM\Column(nullable: true)]
    private ?float $bmi = null;
    #[ORM\Column(nullable: true)]
    private ?float $bfp = null;
    #[ORM\Column(nullable: true)]
    private ?float $lfp = null;

    #[ORM\Column(nullable: true)]
    private ?float $bf = null;

    #[ORM\Column(nullable: true)]
    private ?float $lm = null;

    #[ORM\Column(nullable: true)]
    private ?float $body_density = null;

    #[ORM\Column(nullable: true)]
    private ?float $sum_skinfolds = null;

    #[ORM\ManyToOne(targetEntity: Customer::class, inversedBy: 'measurements')]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    private $customer;

    public function __toString(): string
    {
        return $this->description;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
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

    public function getCurrWeight(): ?string
    {
        return $this->currWeight;
    }

    public function setCurrWeight(?string $currWeight): static
    {
        $this->currWeight = $currWeight;

        return $this;
    }

    public function getIdealWeight(): ?string
    {
        return $this->idealWeight;
    }

    public function setIdealWeight(?string $idealWeight): static
    {
        $this->idealWeight = $idealWeight;

        return $this;
    }

    public function getRightArmRelax(): ?string
    {
        return $this->rightArmRelax;
    }

    public function setRightArmRelax(?string $rightArmRelax): static
    {
        $this->rightArmRelax = $rightArmRelax;

        return $this;
    }

    public function getLeftArmRelax(): ?string
    {
        return $this->leftArmRelax;
    }

    public function setLeftArmRelax(?string $leftArmRelax): static
    {
        $this->leftArmRelax = $leftArmRelax;

        return $this;
    }

    public function getRightArmContracted(): ?string
    {
        return $this->rightArmContracted;
    }

    public function setRightArmContracted(?string $rightArmContracted): static
    {
        $this->rightArmContracted = $rightArmContracted;

        return $this;
    }

    public function getLeftArmContracted(): ?string
    {
        return $this->leftArmContracted;
    }

    public function setLeftArmContracted(?string $leftArmContracted): static
    {
        $this->leftArmContracted = $leftArmContracted;

        return $this;
    }

    public function getRightForearm(): ?string
    {
        return $this->rightForearm;
    }

    public function setRightForearm(?string $rightForearm): static
    {
        $this->rightForearm = $rightForearm;

        return $this;
    }

    public function getLeftForearm(): ?string
    {
        return $this->leftForearm;
    }

    public function setLeftForearm(?string $leftForearm): static
    {
        $this->leftForearm = $leftForearm;

        return $this;
    }

    public function getRightWrist(): ?string
    {
        return $this->rightWrist;
    }

    public function setRightWrist(?string $rightWrist): static
    {
        $this->rightWrist = $rightWrist;

        return $this;
    }

    public function getLeftWrist(): ?string
    {
        return $this->leftWrist;
    }

    public function setLeftWrist(?string $leftWrist): static
    {
        $this->leftWrist = $leftWrist;

        return $this;
    }

    public function getNeck(): ?string
    {
        return $this->neck;
    }

    public function setNeck(?string $neck): static
    {
        $this->neck = $neck;

        return $this;
    }

    public function getShoulder(): ?string
    {
        return $this->shoulder;
    }

    public function setShoulder(?string $shoulder): static
    {
        $this->shoulder = $shoulder;

        return $this;
    }

    public function getBreastplate(): ?string
    {
        return $this->breastplate;
    }

    public function setBreastplate(?string $breastplate): static
    {
        $this->breastplate = $breastplate;

        return $this;
    }

    public function getWaist(): ?string
    {
        return $this->waist;
    }

    public function setWaist(?string $waist): static
    {
        $this->waist = $waist;

        return $this;
    }

    public function getAbs(): ?string
    {
        return $this->abs;
    }

    public function setAbs(?string $abs): static
    {
        $this->abs = $abs;

        return $this;
    }

    public function getHip(): ?string
    {
        return $this->hip;
    }

    public function setHip(?string $hip): static
    {
        $this->hip = $hip;

        return $this;
    }

    public function getRightCalf(): ?string
    {
        return $this->rightCalf;
    }

    public function setRightCalf(?string $rightCalf): static
    {
        $this->rightCalf = $rightCalf;

        return $this;
    }

    public function getLeftCalf(): ?string
    {
        return $this->leftCalf;
    }

    public function setLeftCalf(?string $leftCalf): static
    {
        $this->leftCalf = $leftCalf;

        return $this;
    }

    public function getRightThigh(): ?string
    {
        return $this->rightThigh;
    }

    public function setRightThigh(?string $rightThigh): static
    {
        $this->rightThigh = $rightThigh;

        return $this;
    }

    public function getLeftThigh(): ?string
    {
        return $this->leftThigh;
    }

    public function setLeftThigh(?string $leftThigh): static
    {
        $this->leftThigh = $leftThigh;

        return $this;
    }

    public function getRightProximalThigh(): ?string
    {
        return $this->rightProximalThigh;
    }

    public function setRightProximalThigh(?string $rightProximalThigh): static
    {
        $this->rightProximalThigh = $rightProximalThigh;

        return $this;
    }

    public function getLeftProximalThigh(): ?string
    {
        return $this->leftProximalThigh;
    }

    public function setLeftProximalThigh(?string $leftProximalThigh): static
    {
        $this->leftProximalThigh = $leftProximalThigh;

        return $this;
    }

    public function getCustomer()
    {
        return $this->customer;
    }

    public function setCustomer($customer): void
    {
        $this->customer = $customer;
    }

    /**
     * @return float|null
     */
    public function getChest(): ?float
    {
        return $this->chest;
    }

    /**
     * @param float|null $chest
     */
    public function setChest(?float $chest): void
    {
        $this->chest = $chest;
    }

    /**
     * @return float|null
     */
    public function getAbdomen(): ?float
    {
        return $this->abdomen;
    }

    /**
     * @param float|null $abdomen
     */
    public function setAbdomen(?float $abdomen): void
    {
        $this->abdomen = $abdomen;
    }

    /**
     * @return float|null
     */
    public function getThigh(): ?float
    {
        return $this->thigh;
    }

    /**
     * @param float|null $thigh
     */
    public function setThigh(?float $thigh): void
    {
        $this->thigh = $thigh;
    }

    /**
     * @return float|null
     */
    public function getTriceps(): ?float
    {
        return $this->triceps;
    }

    /**
     * @param float|null $triceps
     */
    public function setTriceps(?float $triceps): void
    {
        $this->triceps = $triceps;
    }

    /**
     * @return float|null
     */
    public function getSuprailiac(): ?float
    {
        return $this->suprailiac;
    }

    /**
     * @param float|null $suprailiac
     */
    public function setSuprailiac(?float $suprailiac): void
    {
        $this->suprailiac = $suprailiac;
    }

    /**
     * @return float|null
     */
    public function getSubscapular(): ?float
    {
        return $this->subscapular;
    }

    /**
     * @param float|null $subscapular
     */
    public function setSubscapular(?float $subscapular): void
    {
        $this->subscapular = $subscapular;
    }

    /**
     * @return float|null
     */
    public function getMidaxillary(): ?float
    {
        return $this->midaxillary;
    }

    /**
     * @param float|null $midaxillary
     */
    public function setMidaxillary(?float $midaxillary): void
    {
        $this->midaxillary = $midaxillary;
    }

    /**
     * @return float|null
     */
    public function getBmi(): ?float
    {
        return $this->bmi;
    }

    /**
     * @param float|null $bmi
     */
    public function setBmi(?float $bmi): void
    {
        $this->bmi = $bmi;
    }

    /**
     * @return float|null
     */
    public function getBfp(): ?float
    {
        return $this->bfp;
    }

    /**
     * @param float|null $bfp
     */
    public function setBfp(?float $bfp): void
    {
        $this->bfp = $bfp;
    }

    /**
     * @return float|null
     */
    public function getLfp(): ?float
    {
        return $this->lfp;
    }

    /**
     * @param float|null $lfp
     */
    public function setLfp(?float $lfp): void
    {
        $this->lfp = $lfp;
    }

    /**
     * @return float|null
     */
    public function getBf(): ?float
    {
        return $this->bf;
    }

    /**
     * @param float|null $bf
     */
    public function setBf(?float $bf): void
    {
        $this->bf = $bf;
    }

    /**
     * @return float|null
     */
    public function getLm(): ?float
    {
        return $this->lm;
    }

    /**
     * @param float|null $lm
     */
    public function setLm(?float $lm): void
    {
        $this->lm = $lm;
    }

    /**
     * @return float|null
     */
    public function getBodyDensity(): ?float
    {
        return $this->body_density;
    }

    /**
     * @param float|null $body_density
     */
    public function setBodyDensity(?float $body_density): void
    {
        $this->body_density = $body_density;
    }

    /**
     * @return float|null
     */
    public function getSumSkinfolds(): ?float
    {
        return $this->sum_skinfolds;
    }

    /**
     * @param float|null $sum_skinfolds
     */
    public function setSumSkinfolds(?float $sum_skinfolds): void
    {
        $this->sum_skinfolds = $sum_skinfolds;
    }

    /**
     * @return float|null
     */
    public function getBiceps(): ?float
    {
        return $this->biceps;
    }

    /**
     * @param float|null $biceps
     */
    public function setBiceps(?float $biceps): void
    {
        $this->biceps = $biceps;
    }
}
