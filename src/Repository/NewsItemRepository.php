<?php

namespace App\Repository;

use App\Entity\NewsItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method NewsItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method NewsItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method NewsItem[]    findAll()
 * @method NewsItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NewsItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NewsItem::class);
    }


    public function createNewsListQueryBuilder()
    {
        return $this->createQueryBuilder('n')
            ->select('n,i,c')
            ->leftJoin('n.image', 'i')
            ->join('n.category', 'c')
            ->join('c.parent', 'cparent')
            ->andWhere('n.isActive = 1')
            ->orderBy('n.publishedAt', 'desc')
            ;
    }

    public function createNewsListQuery(?string $itemType = null, ?string $catCode = null)
    {
        $builder = $this->createNewsListQueryBuilder();

        if($itemType) {
            $builder
                ->andWhere('n.itemType = :itemType')
                ->setParameter('itemType', $itemType)
            ;
        }

        if ($catCode) {
            $builder
                ->andWhere('c.code = :catCode')
                ->setParameter('catCode', $catCode)
            ;
        }

        return $builder->getQuery();
    }


//    public function getPaginatedNewsList($page = 1, $itemsPerPage = 1)
//    {
//        $paginator =  new Paginator($this->createNewsListQuery());
//
//        $totalItems = count($paginator);
//        $pagesCount = ceil($totalItems / $itemsPerPage);
//
//        $list = $paginator
//            ->getQuery()
//            ->setFirstResult($itemsPerPage * ($page-1)) // set the offset
//            ->setMaxResults($itemsPerPage)
//            ->execute()
//        ;
//
//
//        return [
//            'nextPage' => $page < $pagesCount,
//            'list' => $list,
//            'error' => 0,
//        ];
//
//    }

    // /**
    //  * @return NewsItem[] Returns an array of NewsItem objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?NewsItem
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
