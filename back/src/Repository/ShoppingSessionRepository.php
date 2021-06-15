<?php

namespace App\Repository;

use App\Entity\ShoppingSession;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ShoppingSession|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShoppingSession|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShoppingSession[]    findAll()
 * @method ShoppingSession[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShoppingSessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShoppingSession::class);
    }

    // /**
    //  * @return ShoppingSession[] Returns an array of ShoppingSession objects
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
    public function findOneBySomeField($value): ?ShoppingSession
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
