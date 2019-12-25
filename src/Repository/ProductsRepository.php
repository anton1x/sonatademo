<?php


namespace App\Repository;


use App\Application\Sonata\ClassificationBundle\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping;
use Doctrine\ORM\Query\Expr\Join;

/**
 * Class ProductsRepository
 * @package App\Repository
 */
class ProductsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function getProductsBySlugPath($slugPath)
    {
        return $this->createQueryBuilder('p')
            ->select('p')
            ->where('c.slugPath = :slugPath')
            ->join(Category::class, 'c', Join::WITH, 'p.category = c')
            ->setParameter('slugPath', $slugPath)
            ->getQuery()
            ->execute();
    }

}