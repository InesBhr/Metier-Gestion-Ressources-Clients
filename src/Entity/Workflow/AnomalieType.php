<?php

namespace App\Entity\Workflow;

use App\Repository\AnomalieTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: '200_anomalie_type')]
#[ORM\Entity(repositoryClass: AnomalieTypeRepository::class)]
class AnomalieType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'anomalieType', targetEntity: AnomaliesSPN::class)]
    private Collection $anomaliesSPN;

    public function __construct()
    {
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
