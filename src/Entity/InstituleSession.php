<?php

namespace App\Entity;

use App\Repository\InstituleSessionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InstituleSessionRepository::class)]
class InstituleSession
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $themesession = null;

    #[ORM\Column]
    private ?int $placetotal = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $datecommerce = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $datefin = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'instituleSession', targetEntity: Programme::class)]
    private Collection $programmes;

    #[ORM\ManyToMany(targetEntity: Stagiaire::class, mappedBy: 'institulesession')]
    private Collection $stagiaires;

    public function __construct()
    {
        $this->programmes = new ArrayCollection();
        $this->stagiaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getThemesession(): ?string
    {
        return $this->themesession;
    }

    public function setThemesession(string $themesession): self
    {
        $this->themesession = $themesession;

        return $this;
    }

    public function getPlacetotal(): ?int
    {
        return $this->placetotal;
    }

    public function setPlacetotal(int $placetotal): self
    {
        $this->placetotal = $placetotal;

        return $this;
    }

    public function getDatecommerce(): ?\DateTimeInterface
    {
        return $this->datecommerce;
    }

    public function setDatecommerce(\DateTimeInterface $datecommerce): self
    {
        $this->datecommerce = $datecommerce;

        return $this;
    }

    public function getDatefin(): ?\DateTimeInterface
    {
        return $this->datefin;
    }

    public function setDatefin(\DateTimeInterface $datefin): self
    {
        $this->datefin = $datefin;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    
    /**
     * @return Collection<int, Programme>
     */
    public function getProgrammes(): Collection
    {
        return $this->programmes;
    }

    public function addProgramme(Programme $programme): self
    {
        if (!$this->programmes->contains($programme)) {
            $this->programmes->add($programme);
            $programme->setInstituleSession($this);
        }

        return $this;
    }

    public function removeProgramme(Programme $programme): self
    {
        if ($this->programmes->removeElement($programme)) {
            // set the owning side to null (unless already changed)
            if ($programme->getInstituleSession() === $this) {
                $programme->setInstituleSession(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Stagiaire>
     */
    public function getStagiaires(): Collection
    {
        return $this->stagiaires;
    }

    public function addStagiaire(Stagiaire $stagiaire): self
    {
        if (!$this->stagiaires->contains($stagiaire)) {
            $this->stagiaires->add($stagiaire);
            $stagiaire->addInstitulesession($this);
        }

        return $this;
    }

    public function removeStagiaire(Stagiaire $stagiaire): self
    {
        if ($this->stagiaires->removeElement($stagiaire)) {
            $stagiaire->removeInstitulesession($this);
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getThemesession();
    }

    public function getPlaceReserve(){
        return count($this->getStagiaires());
    }
    
    public function getPlaceRestant(){
        return $this->getPlacetotal() - $this->getPlaceReserve();
    }
}
