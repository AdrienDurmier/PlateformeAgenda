<?php

namespace App\DataFixtures\Agenda;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Agenda\TicketEtat;

class TicketEtatFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $etat_a_planifier = new TicketEtat();
        $etat_a_planifier->setCode("a-planifier");
        $etat_a_planifier->setLabel("À planifier");
        $manager->persist($etat_a_planifier);

        $etat_planifiee = new TicketEtat();
        $etat_planifiee->setCode("planifiee");
        $etat_planifiee->setLabel("Planifiée");
        $manager->persist($etat_planifiee);

        $etat_en_cours = new TicketEtat();
        $etat_en_cours->setCode("en-cours");
        $etat_en_cours->setLabel("En cours");
        $manager->persist($etat_en_cours);

        $etat_terminee = new TicketEtat();
        $etat_terminee->setCode("terminee");
        $etat_terminee->setLabel("Terminée");
        $manager->persist($etat_terminee);

        $etat_geler = new TicketEtat();
        $etat_geler->setCode("geler");
        $etat_geler->setLabel("Geler");
        $manager->persist($etat_geler);

        $manager->flush();

        $this->addReference('ticket-etat-a-planifier', $etat_a_planifier);
        $this->addReference('ticket-planifiee', $etat_planifiee);
        $this->addReference('ticket-etat-en-cours', $etat_en_cours);
        $this->addReference('ticket-etat-terminee', $etat_terminee);
        $this->addReference('ticket-etat-geler', $etat_geler);

    }
}
