<?php

namespace App\Repository;

use App\Entity\Artist;
use App\Filter\SearchData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Artist>
 *
 * @method Artist|null find($id, $lockMode = null, $lockVersion = null)
 * @method Artist|null findOneBy(array $criteria, array $orderBy = null)
 * @method Artist[]    findAll()
 * @method Artist[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArtistRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Artist::class);
    }


    public function findSearch(SearchData $search)
    {
        $query = $this
            ->createQueryBuilder('a')
            ->select('a', 'c')
            ->join('a.categories', 'c');

        if (!empty($search->q)) {
            $query = $query
                ->andWhere('a.artistName LIKE :q')
                ->setParameter('q', "%{$search->q}%");
        }

        if (!empty($search->category)) {
            $query = $query
                ->andWhere(':category MEMBER OF a.categories')
                ->setParameter('category', $search->category);
        }

        return $query->getQuery()->getResult();
    }

   

    
    // public function findOneByArtistName($artistName): ?Artist
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.artistName = :artistName')
    //            ->setParameter('artistName', $artistName)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }




    //    /**
    //     * @return Artist[] Returns an array of Artist objects
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

    //    public function findOneBySomeField($value): ?Artist
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
