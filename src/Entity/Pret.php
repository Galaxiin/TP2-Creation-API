<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PretRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use DateTime;

/**
 * @ORM\Entity(repositoryClass=PretRepository::class)
 * @ApiResource(
 *      collectionOperations = {
 *          "get_coll" = {
 *              "method" = "GET",
 *              "path" = "/prets",
 *              "security" = "(is_granted('ROLE_ADHERENT') and object.getAdherent() == user) or is_granted('ROLE_MANAGER')",
 *              "security_message" = "Vous ne pouvez pas accéder à ces informations"
 *          },
 *          "post_coll" = {
 *              "method" = "post",
 *              "security" = "(is_granted('ROLE_ADHERENT') and object.getAdherent() == user) or is_granted('ROLE_MANAGER')",
 *              "security_message" = "Vous ne pouvez pas accéder à ces informations"
 *          }
 *      },
 *      itemOperations = {
 *         "get_item" = {
 *              "method" = "GET",
 *              "path" = "/prets/{id}",
 *              "security" = "(is_granted('ROLE_ADHERENT') and object.getAdherent() == user) or is_granted('ROLE_MANAGER')",
 *              "security_message" = "Vous ne pouvez pas accéder à ces informations"
 *          },
 *          "put_item" = {
 *              "method" = "put",
 *              "path" = "/prets/{id}/edit",
 *              "security" = "is_granted('ROLE_MANAGER')",
 *              "security_message" = "Vous ne pouvez pas accéder à ces informations",
 *              "denormalization_context" = {
 *                  "groups" = {"role_manager"}
 *              }
 *          },
 *          "delete_item" = {
 *              "method" = "delete",
 *              "path" = "/prets/{id}",
 *              "security" = "is_granted('ROLE_ADMIN')",
 *              "security_message" = "Vous ne pouvez pas accéder à ces informations"
 *          }
 *      }
 * )
 */
class Pret
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $DatePret;

    /**
     * @ORM\Column(type="datetime")
     */
    private $DateRetourPrevue;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $DateRetourReelle;

    /**
     * @ORM\ManyToOne(targetEntity=Livre::class, inversedBy="prets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $livre;

    /**
     * @ORM\ManyToOne(targetEntity=Adherent::class, inversedBy="prets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Adherent;

    public function __construct()
    {
        $this->DatePret = new \DateTime();
        $dateRetourPrevue = date('Y-m-d H:m:n',strtotime('15 days',$this->getDatePret()->getTimestamp()));
        $dateRetourPrevue = \DateTime::createFromFormat('Y-m-d H:m:n',$dateRetourPrevue);
        $this->DateRetourPrevue = $dateRetourPrevue;
        $this->DateRetourReelle = null;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatePret(): ?\DateTimeInterface
    {
        return $this->DatePret;
    }

    public function setDatePret(\DateTimeInterface $DatePret): self
    {
        $this->DatePret = $DatePret;

        return $this;
    }

    public function getDateRetourPrevue(): ?\DateTimeInterface
    {
        return $this->DateRetourPrevue;
    }

    public function setDateRetourPrevue(\DateTimeInterface $DateRetourPrevue): self
    {
        $this->DateRetourPrevue = $DateRetourPrevue;

        return $this;
    }

    public function getDateRetourReelle(): ?\DateTimeInterface
    {
        return $this->DateRetourReelle;
    }

    public function setDateRetourReelle(?\DateTimeInterface $DateRetourReelle): self
    {
        $this->DateRetourReelle = $DateRetourReelle;

        return $this;
    }

    public function getLivre(): ?Livre
    {
        return $this->livre;
    }

    public function setLivre(?Livre $livre): self
    {
        $this->livre = $livre;

        return $this;
    }

    public function getAdherent(): ?Adherent
    {
        return $this->Adherent;
    }

    public function setAdherent(?Adherent $Adherent): self
    {
        $this->Adherent = $Adherent;

        return $this;
    }

    // /**
    //  * @ORM\PrePersist
    //  *
    //  * @return void
    //  */
    // public function RendIndispoLivre(){
    //     $this->getLivre()->setDispo(false);
    // }
}
