<?php

namespace App\Entity\Agenda;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="agenda_calendarevent_event")
 * @ORM\Entity(repositoryClass="App\Repository\Agenda\EventRepository")
 */

class Event extends CalendarEvent {

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

}