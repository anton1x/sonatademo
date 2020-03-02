<?php

namespace App\Repository\Sliders;

use App\Entity\Sliders\SlideGallery;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method SlideGallery|null find($id, $lockMode = null, $lockVersion = null)
 * @method SlideGallery|null findOneBy(array $criteria, array $orderBy = null)
 * @method SlideGallery[]    findAll()
 * @method SlideGallery[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SlideGalleryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SlideGallery::class);
    }

    public function findItemByCode($code)
    {
        return $this->createQueryBuilder('sg')
            ->select('sg, slides, slide_image')
            ->join('sg.slides', 'slides')
            ->leftJoin('slides.image', 'slide_image')
            ->orderBy('slides.position', 'ASC')
            ->andWhere('sg.code = :code')
            ->setParameter('code', $code)
            ->getQuery()
            ->getOneOrNullResult();
    }

    // /**
    //  * @return SlideGallery[] Returns an array of SlideGallery objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SlideGallery
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
