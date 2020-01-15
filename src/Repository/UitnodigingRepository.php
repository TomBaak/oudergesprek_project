<?php

namespace App\Repository;

use App\Entity\Uitnodiging;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Uitnodiging|null find($id, $lockMode = null, $lockVersion = null)
 * @method Uitnodiging|null findOneBy(array $criteria, array $orderBy = null)
 * @method Uitnodiging[]    findAll()
 * @method Uitnodiging[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UitnodigingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Uitnodiging::class);
    }

    // /**
    //  * @return Uitnodiging[] Returns an array of Uitnodiging objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Uitnodiging
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
