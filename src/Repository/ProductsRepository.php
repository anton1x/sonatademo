<?php


namespace App\Repository;


use App\Application\Sonata\ClassificationBundle\Entity\Category;
use App\Entity\BaseProduct;
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
    public function __construct(ManagerRegistry $registry, $class = null)
    {
        if(null === $class){
            parent::__construct($registry, BaseProduct::class);
            return;
        }
        parent::__construct($registry, $class);
    }

    public function getAllProductsGroupedByCategory()
    {
        $iterator = $this->createQueryBuilder('p')
            ->select('p')
            ->leftJoin('p.category', 'category')
            ->orderBy("category.id")
            ->getQuery()
            ->iterate();

        $result = [];

        foreach ($iterator as $key => $row) {
            /**
             * @var $productItem BaseProduct
             */
            $productItem = $row [0];
            $result [$productItem->getCategory()->getCode()] [$productItem->getId()] = $productItem;
        }


        return $result;
    }

    public function createQueryBuilderFindByCategoryCode()
    {
        return $this->createQueryBuilder('p')
            ->select('p')
            ->join('p.category', 'category')
            ->join('category.parent', 'category_parent')
            ->andWhere('category_parent.code = :code')
            ->orderBy('p.category,p.sort')
        ;
    }


}