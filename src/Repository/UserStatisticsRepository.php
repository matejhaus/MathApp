<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\UserStatistics;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserStatistics>
 */
class UserStatisticsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserStatistics::class);
    }

    //    /**
    //     * @return UserStatisticsType[] Returns an array of UserStatisticsType objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?UserStatisticsType
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function countStatisticsByUser($value): int
    {
        return (int) $this->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->andWhere('u.user = :user')
            ->setParameter('user', $value)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findTop10ByTheme(int $themeId): array
    {
        return $this->createQueryBuilder('s')
            ->select('u.firstName', 'u.lastName', 's.correctAnswers', 's.totalAttempts', 's.incorrectAnswers')
            ->join('s.user', 'u')
            ->where('s.theme = :themeId')
            ->setParameter('themeId', $themeId)
            ->orderBy('(s.correctAnswers * 1.0) / NULLIF(s.totalAttempts, 0)', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }


}
