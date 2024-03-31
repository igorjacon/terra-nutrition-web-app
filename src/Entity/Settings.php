<?php

namespace App\Entity;

use App\Repository\SettingsRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: SettingsRepository::class)]
#[Vich\Uploadable]
class Settings
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: 'boolean')]
    private bool $displayName = true;

    // NOTE: This is not a mapped field of entity metadata, just a simple property.
    #[Vich\UploadableField(mapping: 'system_files', fileNameProperty: 'logo')]
    #[Assert\File(mimeTypes: 'image/png', mimeTypesMessage: 'Please upload a PNG image.',)]
    private $logoFile;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $logo = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\File(mimeTypes: 'image/png', mimeTypesMessage: 'Please upload a PNG image.')]
    #[Assert\Image(minWidth: 128, maxWidth: 512, allowLandscape: false, allowPortrait: false,
        allowLandscapeMessage: 'This image must be a square', allowPortraitMessage: 'This image must be a square')]
    private $favicon;

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

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|UploadedFile|null $file
     */
    public function setLogoFile(?File $file = null): void
    {
        $this->logoFile = $file;

        if (null !== $file) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    /**
     * @return File|UploadedFile
     */
    public function getLogoFile(): ?UploadedFile
    {
        return $this->logoFile;
    }

    /**
     * @return null|string
     */
    public function getLogo(): ?string
    {
        return $this->logo;
    }

    /**
     * @param null|string $logo
     */
    public function setLogo(?string $logo): void
    {
        $this->logo = $logo;
    }

    /**
     * @return null|string
     */
    public function getFavicon(): ?string
    {
        return $this->favicon;
    }

    /**
     * @param null|string $favicon
     */
    public function setFavicon(?string $favicon): void
    {
        $this->favicon = $favicon;
    }

    /**
     * @return bool
     */
    public function isDisplayName(): bool
    {
        return $this->displayName;
    }

    /**
     * @param bool $displayName
     */
    public function setDisplayName(bool $displayName): void
    {
        $this->displayName = $displayName;
    }
}
