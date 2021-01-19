<?php

namespace App\Repository;

use App\Entity\Pattern;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Pattern|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pattern|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pattern[]    findAll()
 * @method Pattern[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PatternRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pattern::class);
    }

    /**
     * Find all published patterns and order them by publication date with newest patterns first.
     * @return Query Returns an array of Pattern objects
     */
    public function findPatternsByDateDESCQuery(): Query
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ;
    }

    /**
     * Search patterns through SearchPatternType form
     * @param null $keyWord
     * @param null $category
     * @param null $skillLevel
     * @param null $yarnWeight
     * @return int|mixed|string
     */
    public function search($keyWord = null, $category = null, $skillLevel = null, $yarnWeight = null){
        $query = $this->createQueryBuilder('p');
        if($keyWord != null){
            $query
                ->andWhere('MATCH_AGAINST(p.name, p.description) AGAINST
                (:keyWord boolean)>0')
                ->setParameter('keyWord', $keyWord);
        }
        if($category != null){
            $query
                ->leftJoin('p.category', 'c')
                ->andWhere('c.id = :id')
                ->setParameter('id', $category);
        }
        if($skillLevel != null){
            $query
                ->andWhere('p.skillLevel = :skillLevel')
                ->setParameter('skillLevel', $skillLevel);
        }
        return $query->getQuery()->getResult();
    }
}
