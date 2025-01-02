<?php

namespace App\Entity;

use App\Repository\NotificationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NotificationRepository::class)]
class Notification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $message = null;



    #[ORM\Column(type: 'datetime_immutable')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: 'integer')]
    private int $isRead = 0;

    public function getIsRead(): int
    {
        return $this->isRead;
    }

    public function setIsRead(int $isRead): self
    {
        if (!in_array($isRead, [0, 1])) {
            throw new \InvalidArgumentException('isRead doit être 0 ou 1.');
        }

        $this->isRead = $isRead;
        return $this;
    }

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    #[ORM\ManyToOne(targetEntity: Developer::class, inversedBy: 'notifications')]
    private ?Developer $developer;

    #[ORM\ManyToOne(targetEntity: FicheDePoste::class)]
    private ?FicheDePoste $job;

    public function getDeveloper(): ?Developer
    {
        return $this->developer;
    }

    public function setDeveloper(?Developer $developer): self
    {
        $this->developer = $developer;

        return $this;
    }

    public function getJob(): ?FicheDePoste
    {
        return $this->job;
    }

    public function setJob(?FicheDePoste $job): self
    {
        $this->job = $job;

        return $this;
    }

    #[ORM\ManyToOne(targetEntity: Company::class, inversedBy: 'notifications')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Company $company = null;

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }



}
