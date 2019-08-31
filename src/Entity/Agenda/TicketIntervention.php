<?php

namespace App\Entity\Agenda;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\User;

/**
 * @ORM\Table(name="agenda_ticket_intervention")
 * @ORM\Entity(repositoryClass="App\Repository\Agenda\TicketInterventionRepository")
 */
class TicketIntervention
{
    public function __construct() {
        $this->created = new \Datetime();
    }

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime
     */
    private $created;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Agenda\Ticket", inversedBy="interventions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ticket;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;
        return $this;
    }

    public function getTicket(): ?Ticket
    {
        return $this->ticket;
    }

    public function setTicket(Ticket $ticket): self
    {
        $this->ticket = $ticket;

        return $this;
    }


}
