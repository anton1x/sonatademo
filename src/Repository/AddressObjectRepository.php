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

    public function getSortedList()
    {
        return $this->createQueryBuilder('a')
            ->select('a, tvplans, connection_type, pricing_type')
            ->leftJoin('a.tvPlans', 'tvplans')
            ->join('a.connectionType', 'connection_type')
            ->join('a.pricingType', 'pricing_type')
            //->leftJoin('pricing_type.internetPlans', 'internet_plans')
            //->leftJoin('connection_type.devices', 'devices')
            ->orderBy('a.position', 'asc')
            ->getQuery()
            ->execute()
            ;
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
