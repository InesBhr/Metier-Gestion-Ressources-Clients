<?php

namespace App\Entity\Workflow;

use App\Repository\AnomaliesBANRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: '210_anomalies_ban')]
#[ORM\Entity(repositoryClass: AnomaliesBANRepository::class)]
class AnomaliesBAN
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $upr = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $codeBan = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $code42C = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $nra = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $sgtqs = null;

    #[ORM\Column]
    private ?int $nd = null;

    #[ORM\Column(length: 50)]
    private ?string $typePorta = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $crn = null;

    #[ORM\Column(nullable: true)]
    private ?int $op = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $datePorta = null;

    #[ORM\ManyToOne(inversedBy: 'anomaliesBAN')]
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

    public function getCodeBan(): ?string
    {
        return $this->codeBan;
    }

    public function setCodeBan(?string $codeBan): static
    {
        $this->codeBan = $codeBan;

        return $this;
    }

    public function getCode42C(): ?string
    {
        return $this->code42C;
    }

    public function setCode42C(?string $code42C): static
    {
        $this->code42C = $code42C;

        return $this;
    }

    public function getNra(): ?string
    {
        return $this->nra;
    }

    public function setNra(?string $nra): static
    {
        $this->nra = $nra;

        return $this;
    }

    public function getSgtqs(): ?string
    {
        return $this->sgtqs;
    }

    public function setSgtqs(?string $sgtqs): static
    {
        $this->sgtqs = $sgtqs;

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

    public function setTypePorta(string $typePorta): static
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

    public function getOp(): ?int
    {
        return $this->op;
    }

    public function setOp(?int $op): static
    {
        $this->op = $op;

        return $this;
    }

    public function getDatePortaFormatted(): ?string
    {
        return $this->datePorta->format('Y-m-d');
    }

    public function getDatePorta(): ?\DateTimeInterface
    {
        return $this->datePorta;
    }

    public function setDatePorta(?\DateTimeInterface $datePorta): static
    {
        $this->datePorta = $datePorta;

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
