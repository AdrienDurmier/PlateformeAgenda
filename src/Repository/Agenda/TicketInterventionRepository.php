<?php

namespace App\Repository\Agenda;

use App\Entity\Agenda\TicketIntervention;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TicketIntervention|null find($id, $lockMode = null, $lockVersion = null)
 * @method TicketIntervention|null findOneBy(array $criteria, array $orderBy = null)
 * @method TicketIntervention[]    findAll()
 * @method TicketIntervention[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TicketInterventionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TicketIntervention::class);
    }

    // /**
    //  * @return Ticket[] Returns an array of Ticket objects
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
    public function findOneBySomeField($value): ?Ticket
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
