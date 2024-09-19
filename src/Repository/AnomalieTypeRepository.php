<?php

namespace App\Repository;

use App\Entity\Workflow\AnomalieType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AnomalieType>
 *
 * @method AnomalieType|null find($id, $lockMode = null, $lockVersion = null)
 * @method AnomalieType|null findOneBy(array $criteria, array $orderBy = null)
 * @method AnomalieType[]    findAll()
 * @method AnomalieType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnomalieTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnomalieType::class);
    }
}
