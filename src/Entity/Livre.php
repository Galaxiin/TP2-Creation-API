<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\LivreRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ORM\Entity(repositoryClass=LivreRepository::class)
 * @ApiResource()
 * @ApiFilter(
 * 		SearchFilter::class,
 *		properties={
 *			"titre": "ipartial",
 *			"auteur": "exact"
 *		}
 * )
 * @ApiFilter(
 * 		RangeFilter::class,
 *		properties={
 *			"prix"
 *		}
 * )
 * @ApiFilter(
 * 		OrderFilter::class,
 *		properties={
 *			"titre",
 *		    "prix",
 *			"auteur.nom"
 *		}
 * )
 */
class Livre
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $isbn;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @ORM\Column(type="float")
     */
    private $prix;

    /**
     * @ORM\ManyToOne(targetEntity=Auteur::class, inversedBy="livres")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Auteur;

    /**
     * @ORM\ManyToOne(targetEntity=Editeur::class, inversedBy="livres")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Editeur;

    /**
     * @ORM\ManyToOne(targetEntity=Genre::class, inversedBy="livres")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Genre;

    /**
     * @ORM\Column(type="integer")
     */
    private $annee;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $langue;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(string $isbn): self
    {
        $this->isbn = $isbn;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getAuteur(): ?Auteur
    {
        return $this->Auteur;
    }

    public function setAuteur(?Auteur $Auteur): self
    {
        $this->Auteur = $Auteur;

        return $this;
    }

    public function getEditeur(): ?Editeur
    {
        return $this->Editeur;
    }

    public function setEditeur(?Editeur $Editeur): self
    {
        $this->Editeur = $Editeur;

        return $this;
    }

    public function getGenre(): ?Genre
    {
        return $this->Genre;
    }

    public function setGenre(?Genre $Genre): self
    {
        $this->Genre = $Genre;

        return $this;
    }

    public function getAnnee(): ?int
    {
        return $this->annee;
    }

    public function setAnnee(int $annee): self
    {
        $this->annee = $annee;

        return $this;
    }

    public function getLangue(): ?string
    {
        return $this->langue;
    }

    public function setLangue(string $langue): self
    {
        $this->langue = $langue;

        return $this;
    }
    public function __toString()
    {
        return (string)$this->titre;
    }
}
