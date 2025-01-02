<?php

namespace App\Entity;

use App\Repository\CompanyRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

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
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $taille_entreprise = null;

    #[ORM\Column]
    private ?string $secteur = null;

    // #[ORM\Column(length: 255)]
    // private ?string $avatar = null;

    #[ORM\Column(length: 255)]
    private ?string $type_entreprise = null;


    #[ORM\OneToMany(mappedBy: 'entreprise', targetEntity: FicheDePoste::class, cascade: ['persist', 'remove'])]
    private $fichesDePostes;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

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

    public function getTailleEntreprise(): ?string
    {
        return $this->taille_entreprise;
    }

    public function setTailleEntreprise(string $taille_entreprise): static
    {
        $this->taille_entreprise = $taille_entreprise;

        return $this;
    }

    public function getSecteur(): ?string
    {
        return $this->secteur;
    }

    public function setSecteur(string $secteur): static
    {
        $this->secteur = $secteur;

        return $this;
    }
    public function getTypeEntreprise(): ?string
    {
        return $this->type_entreprise;
    }

    public function setTypeEntreprise(string $type_entreprise): static
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
            $ficheDePoste->setEntreprise($this);
        }

        return $this;
    }

    public function removeFicheDePoste(FicheDePoste $ficheDePoste): self
    {
        if ($this->fichesDePostes->removeElement($ficheDePoste)) {
            if ($ficheDePoste->getEntreprise() === $this) {
                $ficheDePoste->setEntreprise(null);
            }
        }

        return $this;
    }

}
