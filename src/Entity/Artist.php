<?php

namespace App\Entity;

use App\Repository\ArtistRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: ArtistRepository::class)]
#[UniqueEntity(fields: ['artistName'], message: 'Cet artiste existe déjà.')]
class Artist
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le nom d\'artiste est obligatoire')]
    #[Assert\Length(min: 1, max: 255, minMessage: 'Le nom d\'artiste doit faire au moins {{ limit }} caractères', maxMessage: 'Le nom d\'artiste doit faire au plus {{ limit }} caractères')]
    private ?string $artistName = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le prénom est obligatoire')]
    #[Assert\Length(min: 2, max: 255, minMessage: 'Le prénom  doit faire au moins {{ limit }} caractères', maxMessage: 'Le prénom doit faire au plus {{ limit }} caractères')]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le nom est obligatoire')]
    #[Assert\Length(min: 2, max: 255, minMessage: 'Le nom  doit faire au moins {{ limit }} caractères', maxMessage: 'Le nom doit faire au plus {{ limit }} caractères')]
    private ?string $lastName = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Email(
        message: 'L\'email n\'est pas valide',
    )]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Regex(pattern: '/^0[1-9]([-. ]?[0-9]{2}){4}$/')] // for french phone format only  //TODO: changer le regex pour l'international
    private ?string $telephone = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Url(message : "Veuillez saisir une URL valide commençant par http:// ou https://" )]
    private ?string $websiteLink = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Url(message : "Veuillez saisir une URL valide commençant par http:// ou https://" )]
    private ?string $facebookLink = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Url(message : "Veuillez saisir une URL valide commençant par http:// ou https://" )]
    private ?string $instagramLink = null;

    #[ORM\OneToOne(inversedBy: 'artist', cascade: ['persist', 'remove'])] //TODO: vérifier la cascade
    private ?User $user = null;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'artists')]
    private Collection $categories;

    #[ORM\ManyToMany(targetEntity: Event::class, inversedBy: 'artists',)]
    private Collection $events;

    #[ORM\OneToMany(mappedBy: 'artist', targetEntity: ArtistImage::class, cascade: ['persist', 'remove'])]
    private Collection $artistImages;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->artistImages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArtistName(): ?string
    {
        return $this->artistName;
    }

    public function setArtistName(string $artistName): static
    {
        $this->artistName = $artistName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): static
    {
        $this->telephone = $telephone;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }

        return $this;
    }

    public function removeCategory(Category $category): static
    {
        $this->categories->removeElement($category);

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): static
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
        }

        return $this;
    }

    public function removeEvent(Event $event): static
    {
        $this->events->removeElement($event);

        return $this;
    }

    /**
     * @return Collection<int, ArtistImage>
     */
    public function getArtistImages(): Collection
    {
        return $this->artistImages;
    }

    public function addArtistImage(ArtistImage $artistImage): static
    {
        if (!$this->artistImages->contains($artistImage)) {
            $this->artistImages->add($artistImage);
            $artistImage->setArtist($this);
        }

        return $this;
    }

    public function removeArtistImage(ArtistImage $artistImage): static
    {
        if ($this->artistImages->removeElement($artistImage)) {
            // set the owning side to null (unless already changed)
            if ($artistImage->getArtist() === $this) {
                $artistImage->setArtist(null);
            }
        }

        return $this;
    }
}
