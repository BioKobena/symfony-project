<?php

namespace App\Entity;

use App\Repository\CompanyRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\User;


#[ORM\Entity(repositoryClass: CompanyRepository::class)]
class Company
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $localisation = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(type: 'json')]
    private array $taille_entreprise = [];

    #[ORM\Column(type: 'json')]
    private array $secteur = [];

    #[ORM\Column(type: 'json')]
    private array $type_entreprise = [];

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $avatar = null;



    #[ORM\OneToMany(mappedBy: 'entreprise', targetEntity: FicheDePoste::class, cascade: ['persist', 'remove'])]
    private $fichesDePostes;

    #[ORM\OneToOne(inversedBy: 'entreprise', targetEntity: User::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'company', targetEntity: Notification::class, cascade: ['persist', 'remove'])]
    private Collection $notifications;
    public function __construct()
    {
        $this->fichesDePostes = new ArrayCollection();
        $this->notifications = new ArrayCollection();
    }

    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(string $localisation): static
    {
        $this->localisation = $localisation;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getTailleEntreprise(): array
    {
        return $this->taille_entreprise;
    }

    public function setTailleEntreprise(array $taille_entreprise): self
    {
        $this->taille_entreprise = $taille_entreprise;

        return $this;
    }

    public function getSecteur(): array
    {
        return $this->secteur;
    }

    public function setSecteur(array $secteur): self
    {
        $this->secteur = $secteur;

        return $this;
    }
    public function getTypeEntreprise(): array
    {
        return $this->type_entreprise;
    }

    public function setTypeEntreprise(array $type_entreprise): self
    {
        $this->type_entreprise = $type_entreprise;

        return $this;
    }

    /**
     * @return Collection<int, FicheDePoste>
     */
    public function getFichesDePostes(): Collection
    {
        return $this->fichesDePostes;
    }

    public function addFicheDePoste(FicheDePoste $ficheDePoste): self
    {
        if (!$this->fichesDePostes->contains($ficheDePoste)) {
            $this->fichesDePostes[] = $ficheDePoste;
            $ficheDePoste->setCompany($this);
        }

        return $this;
    }

    public function removeFicheDePoste(FicheDePoste $ficheDePoste): self
    {
        if ($this->fichesDePostes->removeElement($ficheDePoste)) {
            if ($ficheDePoste->getCompany() === $this) {
                $ficheDePoste->setCompany(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): static
    {
        $this->avatar = $avatar;

        return $this;
    }

}
