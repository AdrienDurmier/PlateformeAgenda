<?php

namespace App\Repository\Agenda;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use App\Entity\Agenda\Event;
use App\Entity\User;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    /**
     * Recherche d'événements
     * @param $filters
     * @param $username
     * @return Event[] Returns an array of Event objects
     */
    public function search($filters, $username)
    {
        $qb = $this->createQueryBuilder('e');
		
		$qb->andWhere(
			$qb->expr()->like('e.users', ':user')
		);
		$qb->setParameter('user', '%' .  $username . '%');

        // Filtre sur la date de début
        if(isset($filters['date_debut']) && $filters['date_debut'] != ""){
            $date_debut_heure_max = date('Y-m-d H:i:s', strtotime($filters['date_debut']));
            $qb->andWhere('e.start >= :date_debut')->setParameter('date_debut', $date_debut_heure_max);
        }

        // Filtre sur la date de fin
        if(isset($filters['date_fin']) && $filters['date_fin'] != ""){
            $date_fin_heure_max = date('Y-m-d H:i:s', strtotime($filters['date_fin']));
            $qb->andWhere('e.end <= :date_fin')->setParameter('date_fin', $date_fin_heure_max);
        }


        return $qb->getQuery()->getResult();
    }

    /**
     * Recherche d'événements déjà existant dont seul la date de début est comprise pendant le nouvel évenement
     * @param $filters
     * @param $username
     * @return Event[] Returns an array of Event objects
     */
    public function searchSuperposeBefore($filters, $username)
    {
        $qb = $this->createQueryBuilder('e');

		$qb->andWhere(
			$qb->expr()->like('e.users', ':user')
		);
		$qb->setParameter('user', '%' .  $username . '%');

        $qb
            ->andWhere('e.start BETWEEN :start AND :end')
            ->setParameter('start', date('Y-m-d H:i:s', strtotime($filters['date_debut'])))
            ->setParameter('end', date('Y-m-d H:i:s', strtotime($filters['date_fin'])))
        ;
        return $qb->getQuery()->getResult();
    }

    /**
     * Recherche d'événements déjà existant dont seul la date de fin est comprise pendant le nouvel évenement
     * @param $filters
     * @param $username
     * @return Event[] Returns an array of Event objects
     */
    public function searchSuperposeAfter($filters, $username)
    {
        $qb = $this->createQueryBuilder('e');

		$qb->andWhere(
			$qb->expr()->like('e.users', ':user')
		);
		$qb->setParameter('user', '%' .  $username . '%');

        $qb
            ->andWhere('e.end BETWEEN :start AND :end')
            ->setParameter('start', date('Y-m-d H:i:s', strtotime($filters['date_debut'])))
            ->setParameter('end', date('Y-m-d H:i:s', strtotime($filters['date_fin'])))
        ;
        return $qb->getQuery()->getResult();
    }
}
