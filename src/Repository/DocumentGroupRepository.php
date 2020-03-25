<?php

namespace App\Repository;

use App\Entity\DocumentGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method DocumentGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method DocumentGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method DocumentGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DocumentGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DocumentGroup::class);
    }

    public function findAll()
    {
        return $this->createQueryBuilder('dg')
            ->select('dg, documents, documents_children, documents_file, child_file')
            ->leftJoin('dg.documents', 'documents')
            ->leftJoin('documents.children', 'documents_children')
            ->leftJoin('documents.file', 'documents_file')
            ->leftJoin('documents_children.file', 'child_file')
            ->andWhere('documents.parent IS NULL')
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return DocumentGroup[] Returns an array of DocumentGroup objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DocumentGroup
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
