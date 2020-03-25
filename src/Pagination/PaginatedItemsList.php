<?php


namespace App\Pagination;


use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;
use JMS\Serializer\Annotation as JMS;

/**
 * Class PaginatedItemsList
 * @package App\Pagination
 * @JMS\ExclusionPolicy("all")
 */
class PaginatedItemsList
{

    /**
     * @var Query
     */
    private $query;
    /**
     * @var bool
     * @JMS\Expose()
     * @JMS\SerializedName("nextPage")
     */
    private $hasNextPage;

    /**
     * @var bool
     * @JMS\Expose()
     */
    private $error = false;

    /**
     * @return int
     */
    public function getTotalItems(): int
    {
        return $this->totalItems;
    }

    /**
     * @return int
     */
    public function getPagesCount(): int
    {
        return $this->pagesCount;
    }

    /**
     * @return mixed
     */
    public function getList()
    {
        return $this->list;
    }
    /**
     * @var Paginator
     */
    private $paginator;
    /**
     * @var int
     * @JMS\Expose()
     */
    private $totalItems;

    /**
     * @JMS\Expose()
     */
    private $itemsPerPage;
    /**
     * @var float
     * @JMS\Expose()
     */
    private $pagesCount;

    private $currentPage;

    /**
     * @JMS\Expose()
     */
    private $list;

    public function __construct(Query $query, $page, $itemsPerPage = 10)
    {
        $this->query = $query;
        $this->paginator = new Paginator($query);
        $this->itemsPerPage = $itemsPerPage;
        $this->currentPage = $page;

        $this->totalItems = count($this->paginator);
        $this->pagesCount = ceil($this->totalItems / $this->itemsPerPage);

        $this->hasNextPage = $page < $this->pagesCount;

        $this->loadList($page);

    }

    /**
     * @return bool
     */
    public function isHasNextPage(): bool
    {
        return $this->hasNextPage;
    }

    /**
     * @return mixed
     */
    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    


    public function setError(bool $value = true)
    {
        $this->error = $value;
    }

    private function loadList($page)
    {
        $this->list = $this->paginator
            ->getQuery()
            ->setFirstResult($this->itemsPerPage * ($page-1)) // set the offset
            ->setMaxResults($this->itemsPerPage)
            ->execute()
        ;
    }


}