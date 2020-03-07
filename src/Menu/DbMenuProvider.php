<?php


namespace App\Menu;


use App\Repository\MenuSchemaItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\ArrayTransformerInterface;

class DbMenuProvider implements MenuSchemaProviderInterface
{


    /**
     * @var MenuSchemaItemRepository
     */
    private $repository;
    /**
     * @var ArrayTransformerInterface
     */
    private $serializer;

    private $schema = null;

    public function __construct(MenuSchemaItemRepository $repository, ArrayTransformerInterface $serializer)
    {
        $this->repository = $repository;
        $this->serializer = $serializer;
    }

    public function getSchema()
    {
        if (null === $this->schema) {
            $this->loadSchema();
        }

        return $this->schema;
    }

    protected function loadSchema()
    {
//        $qb = $this->repository->getChildrenQueryBuilder($this->repository->find(1), true);
//        $qb
//            ->leftJoin('node.parent', 'p')
//            ->leftJoin('node.children', 'c')
//            ->addSelect('p')
//            ->addSelect('c')
//        ;
//        $query = $qb->getQuery()->useQueryCache(true)->enableResultCache(3600, 'menutree');



 //       $list = $query->getResult();

        $list = $this->repository->getItemsForMenu();
        $result = $this->serializer->toArray($list);

        $this->schema =  $result;
    }


}