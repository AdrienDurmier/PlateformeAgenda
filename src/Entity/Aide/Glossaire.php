<?php

namespace App\Entity\Aide;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="aide_glossaire")
 * @ORM\Entity(repositoryClass="App\Repository\Aide\GlossaireRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Glossaire
{
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
     * @ORM\Column(type="string", length=255)
     */
    private $content;

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
}
