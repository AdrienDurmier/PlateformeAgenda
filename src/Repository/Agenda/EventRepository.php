<?php

namespace App\Repository\Agenda;

use App\Entity\Agenda\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

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

    public function resizeEvent($idEvent, $startDate, $endDate)
    {
        return $this
            ->createQueryBuilder('e')
            ->update(Event::class, 'e')
            ->set('e.start', '?1')
            ->set('e.end', '?2')
            ->where('e.id = ?3')
            ->setParameter(1, $startDate)
            ->setParameter(2, $endDate)
            ->setParameter(3, $idEvent)
            ->getQuery()
            ->getResult();
    }

    public function dropEvent($idEvent, $startDate, $endDate)
    {
        return $this
            ->createQueryBuilder('e')
            ->update(Event::class, 'e')
            ->set('e.startDate', '?1')
            ->set('e.endDate', '?2')
            ->where('e.id = ?3')
            ->setParameter(1, $startDate)
            ->setParameter(2, $endDate)
            ->setParameter(3, $idEvent)
            ->getQuery()
            ->getResult();
    }

    public function editEvent($idEvent, $newTitle, $newComm)
    {
        return $this
            ->createQueryBuilder('e')
            ->update(Event::class, 'e')
            ->set('e.title', '?1')
            ->set('e.commentaire', '?2')
            ->where('e.id = ?3')
            ->setParameter(1, $newTitle)
            ->setParameter(2, $newComm)
            ->setParameter(3, $idEvent)
            ->getQuery()
            ->getResult();
    }

    public function deleteEvent($idEvent)
    {
        return $this
            ->createQueryBuilder('e')
            ->delete(Event::class, 'e')
            ->where('e.id = ?1')
            ->setParameter(1, $idEvent)
            ->getQuery()
            ->getResult();
    }
}
