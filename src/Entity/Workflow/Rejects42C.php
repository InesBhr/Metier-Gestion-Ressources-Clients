<?php

namespace App\Entity\Workflow;

use App\Repository\Rejects42CRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: '212_rejects_42c')]
#[ORM\Entity(repositoryClass: Rejects42CRepository::class)]
class Rejects42C
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 16, nullable: true)]
    private ?string $base = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateRejet = null;

    #[ORM\Column(length: 48, nullable: true)]
    private ?string $infosSite = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $operation = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateTraitement = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?AnomalieState $rejectState = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBase(): ?string
    {
        return $this->base;
    }

    public function setBase(?string $base): static
    {
        $this->base = $base;

        return $this;
    }

    public function getDateRejet(): ?\DateTimeInterface
    {
        return $this->dateRejet;
    }


    public function getDateRejetFormatted(): ?string
    {
        return $this->dateRejet->format('Y-m-d');
    }

    public function setDateRejet(?\DateTimeInterface $dateRejet): static
    {
        $this->dateRejet = $dateRejet;

        return $this;
    }

    public function getInfosSite(): ?string
    {
        return $this->infosSite;
    }

    public function setInfosSite(?string $infosSite): static
    {
        $this->infosSite = $infosSite;

        return $this;
    }

    public function getOperation(): ?string
    {
        return $this->operation;
    }

    public function setOperation(?string $operation): static
    {
        $this->operation = $operation;

        return $this;
    }


    public function getDateTraitement(): ?\DateTimeInterface
    {
        return $this->dateTraitement;
    }

    public function getDateTraitementFormatted(): ?string
    {
        if ($this->dateTraitement != null) {
            return $this->dateTraitement->format('Y-m-d');
        } else {
            return null;
        }
    }

    public function setDateTraitement(?\DateTimeInterface $dateTraitement): static
    {
        $this->dateTraitement = $dateTraitement;

        return $this;
    }

    public function getRejectState(): ?AnomalieState
    {
        return $this->rejectState;
    }

    public function setRejectState(?AnomalieState $rejectState): static
    {
        $this->rejectState = $rejectState;

        return $this;
    }
}
