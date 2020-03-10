<?php


namespace App\Repository;


use App\Application\Sonata\ClassificationBundle\Entity\Category;
use App\Entity\BaseProduct;
use App\Entity\Product;
use App\Entity\TVPlan;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping;
use Doctrine\ORM\Query\Expr\Join;

/**
 * Class TVPlanRepository
 * @package App\Repository
 */
class TVPlanRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, $class = null)
    {
        if(null === $class){
            parent::__construct($registry, TVPlan::class);
            return;
        }
        parent::__construct($registry, $class);
    }

    public function getSmotreshkaIdList()
    {
        $iterator = $this->createQueryBuilder('tvplan')
            ->select('tvplan.id, tvplan.smotreshkaId')
            ->getQuery()
            ->iterate()
            ;

        $result = [];

        foreach ($iterator as $item) {
            $item = array_shift($item);
            if (null !== $item['smotreshkaId'])
                $result [$item['id']] = $item['smotreshkaId'];
        }

        return $result;
    }


}