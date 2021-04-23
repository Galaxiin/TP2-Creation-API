<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\AdherentRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=AdherentRepository::class)
 * @ApiResource(
 *      attributes = {
 *          "order" = {
 *              "titre" = "ASC"
 *          }
 *      },
 *      collectionOperations = {
 *          "get_coll" = {
 *              "method" = "GET",
 *              "path" = "/adherents",
 *              "normalization_context" = {
 *                  "groups" = {"get_role_manager"}
 *              }
 *          },
 *          "post_coll" = {
 *              "method" = "post",
 *              "security" = "is_granted('ROLE_MANAGER')",
 *              "security_message" = "Vous ne pouvez pas accéder à ces informations",
 *              "denormalization_context" = {
 *                  "groups" = {"post_role_manager"}
 *              }
 *          }
 *      },
 *      itemOperations = {
 *         "get_item" = {
 *              "method" = "GET",
 *              "path" = "/adherents/{id}",
 *              "security" = "(is_granted('ROLE_ADHERENT') and object.getAdherent() == user) or is_granted('ROLE_MANAGER')",
 *              "security_message" = "Vous ne pouvez pas accéder à ces informations",
 *              "normalization_context" = {
 *                  "groups" = {"get_role_adherent"}
 *              }
 *          },
 *          "put_item" = {
 *              "method" = "put",
 *              "path" = "/adherents/{id}/edit",
 *              "security" = "(is_granted('ROLE_ADHERENT') and object.getAdherent() == user) or is_granted('ROLE_MANAGER')",
 *              "security_message" = "Vous ne pouvez pas accéder à ces informations",
 *              "denormalization_context" = {
 *                  "groups" = {"put_role_adherent","put_role_manager"}
 *              }
 *          },
 *          "delete_item" = {
 *              "method" = "delete",
 *              "path" = "/adherents/{id}",
 *              "security" = "is_granted('ROLE_ADMIN')",
 *              "security_message" = "Vous ne pouvez pas accéder à ces informations"
 *          }
 *      }
 * )
 * @UniqueEntity(
 *      fields={"mail"},
 *      message="il en existe deja un avec {{value}}"
 * )
 */
class Adherent implements UserInterface
{
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_MANAGER = 'ROLE_MANAGER';
    const ROLE_ADHERENT = 'ROLE_ADHERENT';
    const DEFAULT_ROLE = 'ROLE_ADHERENT';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"put_role_adherent"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get_role_adherent","put_role_adherent"})
     */
    private $Nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get_role_adherent","put_role_adherent"})
     */
    private $Prenom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"get_role_adherent","put_role_adherent"})
     */
    private $Adresse;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"get_role_adherent","put_role_adherent"})
     */
    private $CodeCommune;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get_role_adherent","put_role_adherent","put_role_manager"})
     */
    private $mail;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get_role_adherent","put_role_adherent"})
     */
    private $Tel;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"put_role_adherent","put_role_manager"})
     */
    private $Password;

    /**
     * @ORM\OneToMany(targetEntity=Pret::class, mappedBy="Adherent")
     * @Groups({"get_role_adherent","put_role_adherent"})
     */
    private $prets;

    /**
     * @ORM\Column(type="array", length=255, nullable=true)
     * @Groups({"post_role_manager","put_role_adherent","put_role_manager"})
     */
    private $roles;

    public function __construct()
    {
        $this->prets = new ArrayCollection();
        $lerole[] = self::ROLE_ADHERENT;
        $this->roles = [self::DEFAULT_ROLE];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->Prenom;
    }

    public function setPrenom(string $Prenom): self
    {
        $this->Prenom = $Prenom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->Adresse;
    }

    public function setAdresse(?string $Adresse): self
    {
        $this->Adresse = $Adresse;

        return $this;
    }

    public function getCodeCommune(): ?string
    {
        return $this->CodeCommune;
    }

    public function setCodeCommune(?string $CodeCommune): self
    {
        $this->CodeCommune = $CodeCommune;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->Tel;
    }

    public function setTel(string $Tel): self
    {
        $this->Tel = $Tel;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->Password;
    }

    public function setPassword(string $Password): self
    {
        $this->Password = $Password;

        return $this;
    }

    /**
     * @return Collection|Pret[]
     */
    public function getPrets(): Collection
    {
        return $this->prets;
    }

    public function addPret(Pret $pret): self
    {
        if (!$this->prets->contains($pret)) {
            $this->prets[] = $pret;
            $pret->setAdherent($this);
        }

        return $this;
    }

    public function removePret(Pret $pret): self
    {
        if ($this->prets->removeElement($pret)) {
            // set the owning side to null (unless already changed)
            if ($pret->getAdherent() === $this) {
                $pret->setAdherent(null);
            }
        }

        return $this;
    }

    //USER INTERFACE

    /**
     * Returns the roles granted to the user.
     *
     *     public function getRoles()
     *     {
     *         return ['ROLE_USER'];
     *     }
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles() : array{
        return $this->roles;
    }

    /**
     * Undocumented function
     *
     * @param array $roles
     * @return self
     */
    public function setRoles(array $roles) :self{
        $this->roles = $roles;
        return $this;
    }
    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt(){
        return null;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername(){
        return $this->getMail();
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials(){

    }
}
