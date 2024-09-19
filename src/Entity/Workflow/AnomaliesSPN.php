<?php

namespace App\Entity\Workflow;

use App\Repository\AnomaliesSPNRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: '211_anomalies_spn')]
#[ORM\Entity(repositoryClass: AnomaliesSPNRepository::class)]
class AnomaliesSPN
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $upr = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $codeServeur42L = null;

    #[ORM\Column]
    private ?int $nd = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $typePorta = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $crn = null;

    #[ORM\Column(nullable: true)]
    private ?int $z0bpq = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $datePorta = null;

    #[ORM\ManyToOne(inversedBy: 'anomaliesSPN')]
    #[ORM\JoinColumn(nullable: false)]
    private ?AnomalieType $anomalieType = null;

    #[ORM\ManyToOne(inversedBy: 'anomaliesSPN')]
    #[ORM\JoinColumn(nullable: false)]
    private ?AnomalieState $anomalieState = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUpr(): ?string
    {
        return $this->upr;
    }

    public function setUpr(?string $upr): static
    {
        $this->upr = $upr;

        return $this;
    }

    public function getCodeServeur42L(): ?string
    {
        return $this->codeServeur42L;
    }

    public function setCodeServeur42L(?string $codeServeur42L): static
    {
        $this->codeServeur42L = $codeServeur42L;

        return $this;
    }

    public function getNd(): ?int
    {
        return $this->nd;
    }

    public function setNd(int $nd): static
    {
        $this->nd = $nd;

        return $this;
    }

    public function getTypePorta(): ?string
    {
        return $this->typePorta;
    }

    public function setTypePorta(?string $typePorta): static
    {
        $this->typePorta = $typePorta;

        return $this;
    }

    public function getCrn(): ?string
    {
        return $this->crn;
    }

    public function setCrn(?string $crn): static
    {
        $this->crn = $crn;

        return $this;
    }

    public function getZ0bpq(): ?int
    {
        return $this->z0bpq;
    }

    public function setZ0bpq(?int $z0bpq): static
    {
        $this->z0bpq = $z0bpq;

        return $this;
    }

    public function getDatePorta(): ?\DateTimeInterface
    {
        return $this->datePorta;
    }

    public function getDatePortaFormatted(): ?string
    {
        return $this->datePorta->format('Y-m-d');
    }

    public function setDatePorta(?\DateTimeInterface $datePorta): static
    {
        $this->datePorta = $datePorta;

        return $this;
    }

    public function getAnomalieType(): ?AnomalieType
    {
        return $this->anomalieType;
    }

    public function setAnomalieType(?AnomalieType $anomalieType): static
    {
        $this->anomalieType = $anomalieType;

        return $this;
    }

    public function getAnomalieState(): ?AnomalieState
    {
        return $this->anomalieState;
    }

    public function setAnomalieState(?AnomalieState $anomalieState): static
    {
        $this->anomalieState = $anomalieState;

        return $this;
    }
}
