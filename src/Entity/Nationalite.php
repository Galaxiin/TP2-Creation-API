<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\NationaliteRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=NationaliteRepository::class)
 */
class Nationalite
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"ListeSimpleNatio"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"ListeComplexeGenre"})
     * @Groups({"ListeSimpleAuteur","ListeComplexeAuteur"})
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=Auteur::class, mappedBy="nationalite")
     * @Groups({"ListeComplexeGenre"})
     */
    private $auteurs;

    public function __construct()
    {
        $this->auteurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection|Auteur[]
     */
    public function getAuteurs(): Collection
    {
        return $this->auteurs;
    }

    public function addAuteur(Auteur $auteur): self
    {
        if (!$this->auteurs->contains($auteur)) {
            $this->auteurs[] = $auteur;
            $auteur->setNationalite($this);
        }

        return $this;
    }

    public function removeAuteur(Auteur $auteur): self
    {
        if ($this->auteurs->removeElement($auteur)) {
            // set the owning side to null (unless already changed)
            if ($auteur->getNationalite() === $this) {
                $auteur->setNationalite(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return (string)$this->libelle;
    }
}
