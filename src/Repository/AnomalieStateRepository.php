<?php

namespace App\Repository;

use App\Entity\Workflow\AnomalieState;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AnomalieState>
 *
 * @method AnomalieState|null find($id, $lockMode = null, $lockVersion = null)
 * @method AnomalieState|null findOneBy(array $criteria, array $orderBy = null)
 * @method AnomalieState[]    findAll()
 * @method AnomalieState[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnomalieStateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnomalieState::class);
    }
}
