<?php

namespace App\Repository;

use App\Entity\Workflow\Rejects42C;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Rejects42C>
 *
 * @method Rejects42C|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rejects42C|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rejects42C[]    findAll()
 * @method Rejects42C[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class Rejects42CRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rejects42C::class);
    }
}
