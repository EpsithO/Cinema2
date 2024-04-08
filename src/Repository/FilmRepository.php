<?php

namespace App\Repository;

use App\Entity\Film;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Film>
 *
 * @method Film|null find($id, $lockMode = null, $lockVersion = null)
 * @method Film|null findOneBy(array $criteria, array $orderBy = null)
 * @method Film[]    findAll()
 * @method Film[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FilmRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Film::class);
    }

        public function findFilmAffiche(): array
        {
            return $this->createQueryBuilder('f')
                ->leftJoin('f.seances', 's')
                ->addSelect('s')
                ->andWhere('s.dateProjection >= :currentDate') // Séances dans le futur
                ->setParameter('currentDate', new \DateTime()) // Date actuelle
                ->orderBy('s.dateProjection', 'ASC')
                ->setMaxResults(5) // Limite à 5 résultats
                ->getQuery()
                ->getResult();
        }

        public function findfilmDetail(int $id): ?Film
        {
            return $this->createQueryBuilder('f')
                ->leftJoin('f.seances', 's')
                ->addSelect('s')
                ->andWhere('f.id = :id')
                ->setParameter('id', $id)
                ->getQuery()
                ->getOneOrNullResult();
        }

//    /**
//     * @return Film[] Returns an array of Film objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Film
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
