<?php

namespace App\Entity;

use App\Repository\DeveloperProfileRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DeveloperProfileRepository::class)]
class DeveloperProfile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'first_name')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user_id = null;

    #[ORM\Column(length: 255)]
    private ?string $last_name = null;

    #[ORM\Column(length: 255)]
    private ?string $location = null;

    #[ORM\Column(length: 255)]
    private ?string $programming_languages = null;

    #[ORM\Column]
    private ?int $experience_level = null;

    #[ORM\Column(length: 255)]
    private ?string $min_salary = null;

    #[ORM\Column(length: 255)]
    private ?string $biography = null;

    #[ORM\Column(length: 255)]
    private ?string $avatar = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): static
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getProgrammingLanguages(): ?string
    {
        return $this->programming_languages;
    }

    public function setProgrammingLanguages(string $programming_languages): static
    {
        $this->programming_languages = $programming_languages;

        return $this;
    }

    public function getExperienceLevel(): ?int
    {
        return $this->experience_level;
    }

    public function setExperienceLevel(int $experience_level): static
    {
        $this->experience_level = $experience_level;

        return $this;
    }

    public function getMinSalary(): ?string
    {
        return $this->min_salary;
    }

    public function setMinSalary(string $min_salary): static
    {
        $this->min_salary = $min_salary;

        return $this;
    }

    public function getBiography(): ?string
    {
        return $this->biography;
    }

    public function setBiography(string $biography): static
    {
        $this->biography = $biography;

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
