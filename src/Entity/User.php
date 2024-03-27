<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Scheb\TwoFactorBundle\Model\Email\TwoFactorInterface;
use Scheb\TwoFactorBundle\Model\TrustedDeviceInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
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

    #[ORM\Column(length: 180)]
    private ?string $username = null;

    /** @var string */
    #[ORM\Column(unique: true, nullable: true)]
    private ?string $email;

    /** @var bool */
    #[ORM\Column(type: 'boolean')]
    protected bool $enabled = true;

    /** @var list<string> The user roles */
    #[ORM\Column]
    private array $roles = [];

    /** @var string The hashed password */
    #[ORM\Column]
    private ?string $password = null;

    /** @var \DateTime */
    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTime $lastLoginAt;

    /** @var string */
    #[ORM\Column(unique: true, nullable: true)]
    private ?string $confirmationToken = null;

    /** @var \DateTime */
    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTime $passwordRequestedAt;

    /** @var int */
    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $trustedVersion;

    /** @var int */
    #[ORM\Column(type: 'integer', nullable: true)]
    private $authCode;

    /** @var string */
    #[ORM\Column]
    private ?string $firstName;

    /** @var string */
    #[ORM\Column]
    private ?string $lastName;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->trustedVersion = 1;
    }

    public function __toString(): string
    {
        return $this->firstName . " " . $this->lastName;
    }

    public function getId(): ?int
    {
        return $this->id;
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
}
