<?php

namespace App\Repository\Agenda;

use App\Entity\Agenda\TicketEtat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TicketEtat|null find($id, $lockMode = null, $lockVersion = null)
 * @method TicketEtat|null findOneBy(array $criteria, array $orderBy = null)
 * @method TicketEtat[]    findAll()
 * @method TicketEtat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TicketEtatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TicketEtat::class);
    }

    // /**
    //  * @return TicketEtat[] Returns an array of TicketEtat objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TicketEtat
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
