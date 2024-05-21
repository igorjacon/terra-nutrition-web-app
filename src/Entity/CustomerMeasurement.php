<?php

namespace App\Entity;

use App\Repository\MeasurementRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: MeasurementRepository::class)]
class CustomerMeasurement
{
    use TimestampableEntity, BlameableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $height = null;

    #[ORM\Column(length: 255, nullable: true)]
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
    private ?string $rightFist = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $leftFist = null;

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

    #[ORM\ManyToOne(targetEntity: Customer::class, inversedBy: 'measurements')]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    private $customer;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return $this->description;
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

    public function getRightFist(): ?string
    {
        return $this->rightFist;
    }

    public function setRightFist(?string $rightFist): static
    {
        $this->rightFist = $rightFist;

        return $this;
    }

    public function getLeftFist(): ?string
    {
        return $this->leftFist;
    }

    public function setLeftFist(?string $leftFist): static
    {
        $this->leftFist = $leftFist;

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
}
