<?php

namespace App\Repository;

use App\Entity\MenuSchemaItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MenuSchemaItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method MenuSchemaItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method MenuSchemaItem[]    findAll()
 * @method MenuSchemaItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MenuSchemaItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MenuSchemaItem::class);
    }

    // /**
    //  * @return MenuSchemaItem[] Returns an array of MenuSchemaItem objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MenuSchemaItem
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
