<?php

namespace App\Entity;

use App\Repository\ProgrammeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProgrammeRepository::class)]
class Programme
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $nbjours = null;

    #[ORM\ManyToOne(inversedBy: 'programmes')]
    private ?InstituleSession $instituleSession = null;

    #[ORM\ManyToOne(inversedBy: 'programmes')]
    private ?Module $module = null;


    public function __construct()
    {
        $this->instituleSessions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbjours(): ?int
    {
        return $this->nbjours;
    }

    public function __toString()
    {
        return $this->getNbjours();
    }

    public function setNbjours(int $nbjours): self
    {
        $this->nbjours = $nbjours;

        return $this;
    }

    public function getInstituleSession(): ?InstituleSession
    {
        return $this->instituleSession;
    }

    public function setInstituleSession(?InstituleSession $instituleSession): self
    {
        $this->instituleSession = $instituleSession;

        return $this;
    }

    public function getModule(): ?Module
    {
        return $this->module;
    }

    public function setModule(?Module $module): self
    {
        $this->module = $module;

        return $this;
    }
}
