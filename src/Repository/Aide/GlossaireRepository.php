<?php

namespace App\Repository\Aide;

use App\Entity\Aide\Glossaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Glossaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method Glossaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method Glossaire[]    findAll()
 * @method Glossaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GlossaireRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Glossaire::class);
    }

    /**
     * Recherche de définitions
     * @return Glossaire[] Returns an array of Glossaire objects
     */
    public function search()
    {
        $qb = $this->createQueryBuilder('g');
        $qb->orderBy('g.title', 'ASC');

        return $qb->getQuery()->getResult();
    }
}
