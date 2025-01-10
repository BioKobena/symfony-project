<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use App\Repository\FicheDePosteRepository;

#[ORM\Entity]
class FicheDePoste
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private $titre = null;

    #[ORM\Column(length: 255)]
    private $localisation = null;

    #[ORM\Column(length: 255)]
    private $technologies = null;

    #[ORM\Column]
    private ?int $niveauExperience = null;

    #[ORM\Column]
    private ?int $salairePropose = null;

    #[ORM\Column(length: 255)]
    private ?string $description;

    #[ORM\ManyToOne(targetEntity: Company::class, inversedBy: 'fichesDePostes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Company $entreprise = null;



    #[ORM\Column]
    private int $views = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

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

    public function getTechnologies(): ?string
    {
        return $this->technologies;
    }

    public function setTechnologies(string $technologies): static
    {
        $this->technologies = $technologies;

        return $this;
    }

    public function getNiveauExperience(): ?int
    {
        return $this->niveauExperience;
    }

    public function setNiveauExperience(int $niveauExperience): static
    {
        $this->niveauExperience = $niveauExperience;

        return $this;
    }

    public function getSalairePropose(): ?int
    {
        return $this->salairePropose;
    }

    public function setSalairePropose(int $salairePropose): static
    {
        $this->salairePropose = $salairePropose;

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

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;
        if ($company !== null && !$company->getFichesDePostes()->contains($this)) {
            $company->addFicheDePoste($this);
        }
        return $this;
    }

    #[ORM\Column(type: 'datetime')]
    private ?\DateTime $createdAt;

    public function __construct()
    {
        // Initialisation automatique de createdAt avec la date actuelle
        $this->createdAt = new \DateTime();
    }

    // Getter et Setter pour createdAt
    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    // Méthode automatique pour définir createdAt lors de l'insertion
    #[ORM\PrePersist]
    public function prePersist(): void
    {
        if ($this->createdAt === null) {
            $this->createdAt = new \DateTime();
        }
    }

    public function getViews(): int
    {
        return $this->views;
    }

    public function incrementViews(): void
    {
        $this->views++;
    }
}
