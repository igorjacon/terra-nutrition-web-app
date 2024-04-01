<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Scheb\TwoFactorBundle\Model\Email\TwoFactorInterface;
use Scheb\TwoFactorBundle\Model\TrustedDeviceInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[Vich\Uploadable]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_USERNAME', fields: ['username'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface, TrustedDeviceInterface, TwoFactorInterface
{
    const ROLE_ADMIN = 'admin';
    const ROLE_NUTRITIONIST = 'nutritionist';
    const ROLE_CUSTOMER = 'customer';
    const ROLES_ALLOWED = [
        self::ROLE_ADMIN => 'ROLE_ADMIN',
        self::ROLE_NUTRITIONIST => 'ROLE_NUTRITIONIST',
        self::ROLE_CUSTOMER => 'ROLE_CUSTOMER'
    ];

    use TimestampableEntity, BlameableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?string $firstName;

    #[ORM\Column]
    private ?string $lastName;

    #[ORM\OneToMany(targetEntity: Phone::class, mappedBy: 'user', cascade: ['persist', 'remove'])]
    private $phones;

    #[ORM\Column(length: 180)]
    private ?string $username = null;

    #[ORM\Column(unique: true, nullable: true)]
    private ?string $email;

    #[ORM\Column(type: 'boolean')]
    protected bool $enabled = true;

    // NOTE: This is not a mapped field of entity metadata, just a simple property.
    #[Vich\UploadableField(mapping: 'user_files', fileNameProperty: 'profileImg')]
    private $profileFile;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $profileImg = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    private ?string $password = null;

    #[ORM\OneToOne(targetEntity: Address::class)]
    private $address;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTime $lastLoginAt;

    #[ORM\Column(unique: true, nullable: true)]
    private ?string $confirmationToken = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTime $passwordRequestedAt;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $trustedVersion;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $authCode;

    #[ORM\OneToOne(targetEntity: Professional::class, mappedBy: 'user', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private ?Professional $professional = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->trustedVersion = 1;
        $this->phones = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->firstName . " " . $this->lastName;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string|null $firstName
     */
    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string|null $lastName
     */
    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     */
    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|null|UploadedFile $file
     */
    public function setProfileFile(?File $file = null): void
    {
        $this->profileFile = $file;

        if (null !== $file) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    /**
     * @return File|UploadedFile
     */
    public function getProfileFile(): ?UploadedFile
    {
        return $this->profileFile;
    }

    /**
     * @return null|string
     */
    public function getProfileImg(): ?string
    {
        return $this->profileImg;
    }

    /**
     * @param null|string $profilePic
     */
    public function setProfileImg(?string $profilePic): void
    {
        $this->profileImg = $profilePic;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function hasRole($role): bool
    {
        return in_array($role, $this->getRoles());
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
//        $this->roles = $roles;
        $this->roles = array_values($roles);

        return $this;
    }

    public function addRole($role)
    {
        array_push($this->roles, $role);
        $roles = array_unique($this->roles);
        $this->setRoles($roles);
    }

    public function removeRole($role)
    {
        $roles = $this->roles;
        $index = array_search($role, $roles);
        if($index !== false){
            unset($roles[$index]);
        }
        $this->setRoles($roles);
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getLastLoginAt(): ?\DateTime
    {
        return $this->lastLoginAt;
    }

    /**
     * @param \DateTime|null $lastLoginAt
     */
    public function setLastLoginAt(?\DateTime $lastLoginAt): void
    {
        $this->lastLoginAt = $lastLoginAt;
    }

    /**
     * @return string|null
     */
    public function getConfirmationToken(): ?string
    {
        return $this->confirmationToken;
    }

    /**
     * @param string|null $confirmationToken
     */
    public function setConfirmationToken(?string $confirmationToken): void
    {
        $this->confirmationToken = $confirmationToken;
    }

    /**
     * @return \DateTime|null
     */
    public function getPasswordRequestedAt(): ?\DateTime
    {
        return $this->passwordRequestedAt;
    }

    /**
     * @param \DateTime|null $passwordRequestedAt
     */
    public function setPasswordRequestedAt(?\DateTime $passwordRequestedAt): void
    {
        $this->passwordRequestedAt = $passwordRequestedAt;
    }

    /**
     * @return int|null
     */
    public function getTrustedVersion(): ?int
    {
        return $this->trustedVersion;
    }

    /**
     * @param int|null $trustedVersion
     */
    public function setTrustedVersion(?int $trustedVersion): void
    {
        $this->trustedVersion = $trustedVersion;
    }

    /**
     * @return int
     */
    public function getAuthCode(): int
    {
        return $this->authCode;
    }

    /**
     * @param int $authCode
     */
    public function setAuthCode(int $authCode): void
    {
        $this->authCode = $authCode;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function serialize(): string
    {
        return serialize([$this->id, $this->username, $this->password]);
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized): void
    {
        [$this->id, $this->username, $this->password] = unserialize($serialized, ['allowed_classes' => false]);
    }

    public function getTrustedTokenVersion(): int
    {
        return $this->trustedVersion;
    }

    public function isEmailAuthEnabled(): bool
    {
        return true;
    }

    public function getEmailAuthRecipient(): string
    {
        return $this->email;
    }

    public function getEmailAuthCode(): string|null
    {
        return $this->authCode ? $this->authCode : 'null';
    }

    public function setEmailAuthCode(string $authCode): void
    {
        $this->authCode = $authCode;
    }

    /**
     * @return Collection<int, Phone>
     */
    public function getPhones(): Collection
    {
        return $this->phones;
    }

    public function addPhone(Phone $phone): User
    {
        if (!$this->phones->contains($phone)) {
            $this->phones->add($phone);
            $phone->setUser($this);
        }
        return $this;
    }

    public function removePhone(Phone $phone): static
    {
        $this->phones->removeElement($phone);
        return $this;
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
        $professional?->setUser($this);
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address): void
    {
        $this->address = $address;
    }
}
