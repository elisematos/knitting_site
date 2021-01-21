<?php

namespace App\Repository;

use App\Entity\Yarn;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Yarn|null find($id, $lockMode = null, $lockVersion = null)
 * @method Yarn|null findOneBy(array $criteria, array $orderBy = null)
 * @method Yarn[]    findAll()
 * @method Yarn[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class YarnRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Yarn::class);
    }

    // /**
    //  * @return Yarn[] Returns an array of Yarn objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('y')
            ->andWhere('y.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('y.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Yarn
    {
        return $this->createQueryBuilder('y')
            ->andWhere('y.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
