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
            ->leftjoin('a.categories', 'c');

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
}
