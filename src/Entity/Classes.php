<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClassesRepository")
 */
class Classes
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="classes")
     */
    private $classe_user;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->appartenance = new ArrayCollection();
        $this->classe_user = new ArrayCollection();
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

    /**
     * @return Collection|User[]
     */
    public function getClasseUser(): Collection
    {
        return $this->classe_user;
    }

    public function addClasseUser(User $classeUser): self
    {
        if (!$this->classe_user->contains($classeUser)) {
            $this->classe_user[] = $classeUser;
        }

        return $this;
    }

    public function removeClasseUser(User $classeUser): self
    {
        if ($this->classe_user->contains($classeUser)) {
            $this->classe_user->removeElement($classeUser);
        }

        return $this;
    }
}
