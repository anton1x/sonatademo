<?php

namespace App\Repository;

use App\Entity\MenuSchemaItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

/**
 * @method MenuSchemaItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method MenuSchemaItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method MenuSchemaItem[]    findAll()
 * @method MenuSchemaItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MenuSchemaItemRepository extends NestedTreeRepository
{

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, $em->getClassMetadata(MenuSchemaItem::class));
    }

    public function getItemsForMenu()
    {
        return $this->createQueryBuilder('msi')
            ->select('msi, children, grandchildren')
            ->leftJoin('msi.parent', 'parent')
            ->leftJoin('msi.children', 'children')
            ->leftJoin('children.children', 'grandchildren')
            ->andWhere('msi.level = :lvl')
            ->setParameter('lvl', 1)
            ->orderBy('msi.left', 'asc')
            //->addOrderBy('children.left', 'asc')
            ->getQuery()
//            ->setCacheable(true)
//            ->setCacheMode(ClassMetadata::CACHE_USAGE_NONSTRICT_READ_WRITE)
//            ->setCacheRegion('entity_that_rarely_changes')
            ->getResult()
            ;
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
