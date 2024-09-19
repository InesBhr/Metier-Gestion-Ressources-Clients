<?php

namespace App\Entity\Workflow;

use App\Repository\Rejects42LRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: '213_rejects_42l')]
#[ORM\Entity(repositoryClass: Rejects42LRepository::class)]
class Rejects42L
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 8, nullable: true)]
    private ?string $dr = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $dateMvt = null;

    #[ORM\Column(length: 56, nullable: true)]
    private ?string $centre = null;

    #[ORM\Column(length: 56, nullable: true)]
    private ?string $nd = null;

    #[ORM\Column(length: 56, nullable: true)]
    private ?string $ne = null;

    #[ORM\Column(length: 4, nullable: true)]
    private ?string $codeMouvement = null;

    #[ORM\Column(length: 8, nullable: true)]
    private ?string $repartiteur = null;

    #[ORM\Column(length: 8, nullable: true)]
    private ?string $codeSituation = null;

    #[ORM\Column(length: 8, nullable: true)]
    private ?string $typeNe = null;

    #[ORM\Column(nullable: true)]
    private ?int $numeroMvt = null;

    #[ORM\Column(nullable: true)]
    private ?int $ancienNd = null;

    #[ORM\Column(length: 8, nullable: true)]
    private ?string $codeAdresseRattachement = null;

    #[ORM\Column(length: 8, nullable: true)]
    private ?string $codeOperateurConcurrent = null;

    #[ORM\Column(length: 16, nullable: true)]
    private ?string $typePortabilite = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $indicateurBlocage = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $info1 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $info2 = null;

    #[ORM\Column(length: 32, nullable: true)]
    private ?string $a054 = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateTraitement = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?AnomalieState $rejectState = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDr(): ?string
    {
        return $this->dr;
    }

    public function setDr(?string $dr): self
    {
        $this->dr = $dr;

        return $this;
    }

    public function getDateMvt(): ?string
    {
        return $this->dateMvt;
    }

    public function setDateMvt(?string $dateMvt): self
    {
        $this->dateMvt = $dateMvt;

        return $this;
    }

    public function getCentre(): ?string
    {
        return $this->centre;
    }

    public function setCentre(?string $centre): self
    {
        $this->centre = $centre;

        return $this;
    }

    public function getNd(): ?string
    {
        return $this->nd;
    }

    public function setNd(?string $nd): self
    {
        $this->nd = $nd;

        return $this;
    }

    public function getNe(): ?string
    {
        return $this->ne;
    }

    public function setNe(?string $ne): self
    {
        $this->ne = $ne;

        return $this;
    }

    public function getCodeMouvement(): ?string
    {
        return $this->codeMouvement;
    }

    public function setCodeMouvement(?string $codeMouvement): self
    {
        $this->codeMouvement = $codeMouvement;

        return $this;
    }

    public function getRepartiteur(): ?string
    {
        return $this->repartiteur;
    }

    public function setRepartiteur(?string $repartiteur): self
    {
        $this->repartiteur = $repartiteur;

        return $this;
    }

    public function getCodeSituation(): ?string
    {
        return $this->codeSituation;
    }

    public function setCodeSituation(?string $codeSituation): self
    {
        $this->codeSituation = $codeSituation;

        return $this;
    }

    public function getTypeNe(): ?string
    {
        return $this->typeNe;
    }

    public function setTypeNe(?string $typeNe): self
    {
        $this->typeNe = $typeNe;

        return $this;
    }

    public function getNumeroMvt(): ?int
    {
        return $this->numeroMvt;
    }

    public function setNumeroMvt(?int $numeroMvt): self
    {
        $this->numeroMvt = $numeroMvt;

        return $this;
    }

    public function getAncienNd(): ?int
    {
        return $this->ancienNd;
    }

    public function setAncienNd(?int $ancienNd): self
    {
        $this->ancienNd = $ancienNd;

        return $this;
    }

    public function getCodeAdresseRattachement(): ?string
    {
        return $this->codeAdresseRattachement;
    }

    public function setCodeAdresseRattachement(?string $codeAdresseRattachement): self
    {
        $this->codeAdresseRattachement = $codeAdresseRattachement;

        return $this;
    }

    public function getCodeOperateurConcurrent(): ?string
    {
        return $this->codeOperateurConcurrent;
    }

    public function setCodeOperateurConcurrent(?string $codeOperateurConcurrent): self
    {
        $this->codeOperateurConcurrent = $codeOperateurConcurrent;

        return $this;
    }

    public function getTypePortabilite(): ?string
    {
        return $this->typePortabilite;
    }

    public function setTypePortabilite(?string $typePortabilite): self
    {
        $this->typePortabilite = $typePortabilite;

        return $this;
    }

    public function getIndicateurBlocage(): ?string
    {
        return $this->indicateurBlocage;
    }

    public function setIndicateurBlocage(?string $indicateurBlocage): self
    {
        $this->indicateurBlocage = $indicateurBlocage;

        return $this;
    }

    public function getInfo1(): ?string
    {
        return $this->info1;
    }

    public function setInfo1(?string $info1): self
    {
        $this->info1 = $info1;

        return $this;
    }

    public function getInfo2(): ?string
    {
        return $this->info2;
    }

    public function setInfo2(?string $info2): self
    {
        $this->info2 = $info2;

        return $this;
    }

    public function getA054(): ?string
    {
        return $this->a054;
    }

    public function setA054(?string $a054): self
    {
        $this->a054 = $a054;

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

    public function setDateTraitement(?\DateTimeInterface $dateTraitement): self
    {
        $this->dateTraitement = $dateTraitement;

        return $this;
    }

    public function getRejectState(): ?AnomalieState
    {
        return $this->rejectState;
    }

    public function setRejectState(?AnomalieState $rejectState): self
    {
        $this->rejectState = $rejectState;

        return $this;
    }
}
