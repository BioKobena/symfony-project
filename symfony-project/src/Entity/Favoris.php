<?php

namespace App\Entity;

use App\Entity\Developer;
use App\Entity\Company;
use App\Entity\FicheDePoste;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Favoris
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Developer::class, inversedBy: 'favoris')]
    private ?Developer $developer;

    #[ORM\ManyToOne(targetEntity: Company::class, inversedBy: 'favoris')]
    private ?Company $company;

    #[ORM\ManyToOne(targetEntity: FicheDePoste::class)]
    private ?FicheDePoste $ficheDePoste;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDeveloper(): ?Developer
    {
        return $this->developer;
    }

    public function setDeveloper(?Developer $developer): self
    {
        $this->developer = $developer;

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getFicheDePoste(): ?FicheDePoste
    {
        return $this->ficheDePoste;
    }

    public function setFicheDePoste(?FicheDePoste $ficheDePoste): self
    {
        $this->ficheDePoste = $ficheDePoste;

        return $this;
    }
}
