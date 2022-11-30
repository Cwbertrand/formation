<?php

namespace App\Entity;

use App\Repository\FormateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FormateurRepository::class)]
class Formateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\OneToMany(mappedBy: 'formateur', targetEntity: InstituleSession::class)]
    private Collection $instituleSessions;

    public function __construct()
    {
        $this->instituleSessions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function __toString()
    {
        return $this->getNom(). ' '. $this->getPrenom();
    }

    /**
     * @return Collection<int, InstituleSession>
     */
    public function getInstituleSessions(): Collection
    {
        return $this->instituleSessions;
    }

    public function addInstituleSession(InstituleSession $instituleSession): self
    {
        if (!$this->instituleSessions->contains($instituleSession)) {
            $this->instituleSessions->add($instituleSession);
            $instituleSession->setFormateur($this);
        }

        return $this;
    }

    public function removeInstituleSession(InstituleSession $instituleSession): self
    {
        if ($this->instituleSessions->removeElement($instituleSession)) {
            // set the owning side to null (unless already changed)
            if ($instituleSession->getFormateur() === $this) {
                $instituleSession->setFormateur(null);
            }
        }

        return $this;
    }
}
