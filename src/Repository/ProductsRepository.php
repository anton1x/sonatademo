<?php


namespace App\Repository;


use App\Application\Sonata\ClassificationBundle\Entity\Category;
use App\Entity\BaseProduct;
use App\Entity\Device;
use App\Entity\Product;
use App\Entity\TVPlan;
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
        if (null === $class) {
            parent::__construct($registry, BaseProduct::class);
            return;
        }
        parent::__construct($registry, $class);
    }

    public function getAllProductsGroupedByCategory()
    {
        $list = $this->getAllProducts();

        return $this->groupListByCategoryCode($list);
    }

    public function groupListByCategoryCode($list)
    {
        $result = [];

        foreach ($list as $row) {
            /**
             * @var $productItem BaseProduct
             */
            $productItem = $row;
            $result [$productItem->getCategory()->getCode()] [$productItem->getId()] = $productItem;
        }


        return $result;
    }

    public function getAllProducts()
    {
        $list = $this->createQueryBuilder('p')
            ->select('p, category')
            ->leftJoin('p.category', 'category')
            ->orderBy("category.id")
            ->getQuery()
            ->getResult();

        $result = [];

        foreach ($list as $productItem) {
            /**
             * @var $productItem BaseProduct
             */
            $result [$productItem->getId()] = $productItem;
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
            ->orderBy('p.category,p.sort');
    }


}