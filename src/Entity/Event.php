<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le nom de l\'événement est obligatoire')]
    #[Assert\Length(min: 3, max: 255, minMessage: 'Le nom de l\'événement doit faire au moins {{ limit }} caractères', maxMessage: 'Le nom de l\'événement doit faire au plus {{ limit }} caractères')]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message:'La date de début de l\'événement est obligatoire')]
    #[Assert\GreaterThanOrEqual('now', message:'La date doit être égale ou postérieure à aujourd\'hui')]
    private ?\DateTimeInterface $openingDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message:'La date de fin de l\'événement est obligatoire')]
    #[Assert\GreaterThanOrEqual(propertyPath:"openingDate")]
    private ?\DateTimeInterface $closingDate = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:'Le créneau horaire est obligatoire (ex: "de 10h à 12h et de 14h à 18h")')]
    private ?string $schedule = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Url(message : "Veuillez saisir une URL valide commençant par http:// ou https://" )]
    private ?string $websiteLink = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Url(message : "Veuillez saisir une URL valide commençant par http:// ou https://" )]
    private ?string $facebookLink = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Url(message : "Veuillez saisir une URL valide commençant par http:// ou https://" )]
    private ?string $instagramLink = null;

    #[ORM\ManyToMany(targetEntity: Artist::class, mappedBy: 'events')]
    private Collection $artists;

    public function __construct()
    {
        $this->artists = new ArrayCollection();
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

    public function getOpeningDate(): ?\DateTimeInterface
    {
        return $this->openingDate;
    }

    public function setOpeningDate(\DateTimeInterface $openingDate): static
    {
        $this->openingDate = $openingDate;

        return $this;
    }

    public function getClosingDate(): ?\DateTimeInterface
    {
        return $this->closingDate;
    }

    public function setClosingDate(\DateTimeInterface $closingDate): static
    {
        $this->closingDate = $closingDate;

        return $this;
    }

    public function getSchedule(): ?string
    {
        return $this->schedule;
    }

    public function setSchedule(?string $schedule): static
    {
        $this->schedule = $schedule;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getWebsiteLink(): ?string
    {
        return $this->websiteLink;
    }

    public function setWebsiteLink(?string $websiteLink): static
    {
        $this->websiteLink = $websiteLink;

        return $this;
    }

    public function getFacebookLink(): ?string
    {
        return $this->facebookLink;
    }

    public function setFacebookLink(?string $facebookLink): static
    {
        $this->facebookLink = $facebookLink;

        return $this;
    }

    public function getInstagramLink(): ?string
    {
        return $this->instagramLink;
    }

    public function setInstagramLink(?string $instagramLink): static
    {
        $this->instagramLink = $instagramLink;

        return $this;
    }

    /**
     * @return Collection<int, Artist>
     */
    public function getArtists(): Collection
    {
        return $this->artists;
    }

    public function addArtist(Artist $artist): static
    {
        if (!$this->artists->contains($artist)) {
            $this->artists->add($artist);
            $artist->addEvent($this);
        }

        return $this;
    }

    public function removeArtist(Artist $artist): static
    {
        if ($this->artists->removeElement($artist)) {
            $artist->removeEvent($this);
        }

        return $this;
    }

}
