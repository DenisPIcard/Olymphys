<?php

namespace App\Repository;

use App\Entity\Essai;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Essai|null find($id, $lockMode = null, $lockVersion = null)
 * @method Essai|null findOneBy(array $criteria, array $orderBy = null)
 * @method Essai[]    findAll()
 * @method Essai[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EssaiRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Essai::class);
    }

    // /**
    //  * @return Essai[] Returns an array of Essai objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Essai
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
