<?php

namespace App\Repository;

use App\Entity\PricingType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method PricingType|null find($id, $lockMode = null, $lockVersion = null)
 * @method PricingType|null findOneBy(array $criteria, array $orderBy = null)
 * @method PricingType[]    findAll()
 * @method PricingType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PricingTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PricingType::class);
    }

    // /**
    //  * @return PricingType[] Returns an array of PricingType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PricingType
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
