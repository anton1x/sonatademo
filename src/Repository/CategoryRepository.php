<?php


namespace App\Repository;


use App\Application\Sonata\ClassificationBundle\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function getQueryBuilderForFilterCategoryByContext($context)
    {
        $qb = $this->createQueryBuilder('category');

        $qb
            ->select('category')
            ->join('category.context', 'context')
            ->leftJoin('category.children', 'children')
            ->leftJoin('category.parent', 'parent')
            ->andWhere('context.id = :context')
            ->groupBy('category')
            ->andHaving('COUNT(children) < 1')
            ->setParameter('context', $context)
        ;

        return $qb;
    }
}