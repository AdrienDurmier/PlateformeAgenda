<?php

namespace App\Entity\Agenda;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\User;

/**
 * @ORM\Table(name="agenda_ticket")
 * @ORM\Entity(repositoryClass="App\Repository\Agenda\TicketRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Ticket
{
    public function __construct() {
        $this->created = new \Datetime();
        $this->updated = new \Datetime();
        $this->interventions = new ArrayCollection();
    }

    public function __toString() {
        return $this->title;
    }

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

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
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated;

    /**
     * @var TicketEtat
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Agenda\TicketEtat")
     * @ORM\JoinColumn(nullable=false)
     */
    private $etat;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Agenda\TicketIntervention", mappedBy="ticket", cascade={"persist", "remove"})
     */
    private $interventions;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
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

    public function getUpdated(): ?\DateTimeInterface
    {
        return $this->updated;
    }

    public function setUpdated(\DateTimeInterface $updated): self
    {
        $this->updated = $updated;
        return $this;
    }

    /**
     * @ORM\PreUpdate
     */
    public function updateDate() {
        $this->setUpdated(new \Datetime());
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): void
    {
        $this->author = $author;
    }

    public function getEtat(): TicketEtat
    {
        return $this->etat;
    }

    public function setEtat(?TicketEtat $etat): void
    {
        $this->etat = $etat;
    }

    public function addIntervention(TicketIntervention $intervention)
    {
        $this->interventions[] = $intervention;
    }

    public function removeIntervention(TicketIntervention $intervention)
    {
        $this->interventions->removeElement($intervention);
    }

    public function getInterventions()
    {
        return $this->interventions;
    }

}
