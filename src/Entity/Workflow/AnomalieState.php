<?php

namespace App\Entity\Workflow;

use App\Repository\AnomalieStateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: '201_anomalie_state')]
#[ORM\Entity(repositoryClass: AnomalieStateRepository::class)]
class AnomalieState
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'anomalieState', targetEntity: AnomaliesBAN::class)]
    private Collection $anomaliesBAN;

    #[ORM\OneToMany(mappedBy: 'anomalieState', targetEntity: AnomaliesSPN::class)]
    private Collection $anomaliesSPN;

    public function __construct()
    {
        $this->anomaliesBAN = new ArrayCollection();
        $this->anomaliesSPN = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }
}
