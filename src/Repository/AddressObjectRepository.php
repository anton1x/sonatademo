<?php

namespace App\Repository;

use App\Entity\AddressObject;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method AddressObject|null find($id, $lockMode = null, $lockVersion = null)
 * @method AddressObject|null findOneBy(array $criteria, array $orderBy = null)
 * @method AddressObject[]    findAll()
 * @method AddressObject[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AddressObjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AddressObject::class);
    }

    // /**
    //  * @return AddressObject[] Returns an array of AddressObject objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AddressObject
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
