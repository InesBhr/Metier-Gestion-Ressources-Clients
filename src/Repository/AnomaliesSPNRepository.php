<?php

namespace App\Repository;

use App\Entity\Workflow\AnomaliesSPN;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AnomaliesSPN>
 *
 * @method AnomaliesSPN|null find($id, $lockMode = null, $lockVersion = null)
 * @method AnomaliesSPN|null findOneBy(array $criteria, array $orderBy = null)
 * @method AnomaliesSPN[]    findAll()
 * @method AnomaliesSPN[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnomaliesSPNRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnomaliesSPN::class);
    }
}
