<?php

namespace App\Repository;

use App\Entity\ConnectionType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ConnectionType|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConnectionType|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConnectionType[]    findAll()
 * @method ConnectionType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConnectionTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConnectionType::class);
    }

    // /**
    //  * @return ConnectionType[] Returns an array of ConnectionType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ConnectionType
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
