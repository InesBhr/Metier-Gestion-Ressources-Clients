<?php

namespace App\Repository;

use App\Entity\Workflow\AnomaliesBAN;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AnomaliesBAN>
 *
 * @method AnomaliesBAN|null find($id, $lockMode = null, $lockVersion = null)
 * @method AnomaliesBAN|null findOneBy(array $criteria, array $orderBy = null)
 * @method AnomaliesBAN[]    findAll()
 * @method AnomaliesBAN[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnomaliesBANRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnomaliesBAN::class);
    }
}
