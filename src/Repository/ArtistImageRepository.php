<?php

namespace App\Repository;

use App\Entity\ArtistImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ArtistImage>
 *
 * @method ArtistImage|null find($id, $lockMode = null, $lockVersion = null)
 * @method ArtistImage|null findOneBy(array $criteria, array $orderBy = null)
 * @method ArtistImage[]    findAll()
 * @method ArtistImage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArtistImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ArtistImage::class);
    }

//    /**
//     * @return ArtistImage[] Returns an array of ArtistImage objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ArtistImage
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
