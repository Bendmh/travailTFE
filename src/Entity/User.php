<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(
 *     fields = {"pseudo"},
 *     message = "Ce pseudo existe déjà !"
 * )
 */
class User implements UserInterface
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
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="8", minMessage="Votre mot de passe doit faire au moins 8 caractères")
     */
    private $password;

    /**
     * @Assert\EqualTo(propertyPath="password", message="les deux messages sont différents")
     */
    private $confirm_password;

    /**
     * @return mixed
     */
    public function getConfirmPassword()
    {
        return $this->confirm_password;
    }

    /**
     * @param mixed $confirm_password
     * @return User
     */
    public function setConfirmPassword($confirm_password)
    {
        $this->confirm_password = $confirm_password;
        return $this;
    }


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pseudo;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Classes", mappedBy="classe_user")
     */
    private $classes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserActivity", mappedBy="user_id")
     */
    private $activity_id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Activity", mappedBy="created_by", orphanRemoval=true)
     */
    private $activity_creator;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ReponseSondage", mappedBy="user")
     */
    private $reponseSondages;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $mdpOublie;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ReponseEleveQCM", mappedBy="userId")
     */
    private $reponseEleveQCMs;


    public function __construct()
    {
        $this->classes = new ArrayCollection();
        $this->activity = new ArrayCollection();
        $this->activity_id = new ArrayCollection();
        $this->activity_creator = new ArrayCollection();
        $this->reponseSondages = new ArrayCollection();
        $this->reponseEleveQCMs = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * @param mixed $titre
     * @return User
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;
        return $this;
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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }
    public function getRoles()
    {
        $titre = $this->titre;
        return [$titre];
    }

    public function getUsername()
    {
        return $this->nom;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * @return Collection|Classes[]
     */
    public function getClasses(): Collection
    {
        return $this->classes;
    }

    public function addClass(Classes $class): self
    {
        if (!$this->classes->contains($class)) {
            $this->classes[] = $class;
            $class->addClasseUser($this);
        }

        return $this;
    }

    public function removeClass(Classes $class): self
    {
        if ($this->classes->contains($class)) {
            $this->classes->removeElement($class);
            $class->removeClasseUser($this);
        }

        return $this;
    }

    /**
     * @return Collection|Activity[]
     */
    public function getActivity(): Collection
    {
        return $this->activity;
    }

    public function addActivity(Activity $activity): self
    {
        if (!$this->activity->contains($activity)) {
            $this->activity[] = $activity;
        }

        return $this;
    }

    public function removeActivity(Activity $activity): self
    {
        if ($this->activity->contains($activity)) {
            $this->activity->removeElement($activity);
        }

        return $this;
    }

    /**
     * @return Collection|UserActivity[]
     */
    public function getActivityId(): Collection
    {
        return $this->activity_id;
    }

    public function addActivityId(UserActivity $activityId): self
    {
        if (!$this->activity_id->contains($activityId)) {
            $this->activity_id[] = $activityId;
            $activityId->setUserId($this);
        }

        return $this;
    }

    public function removeActivityId(UserActivity $activityId): self
    {
        if ($this->activity_id->contains($activityId)) {
            $this->activity_id->removeElement($activityId);
            // set the owning side to null (unless already changed)
            if ($activityId->getUserId() === $this) {
                $activityId->setUserId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Activity[]
     */
    public function getActivityCreator(): Collection
    {
        return $this->activity_creator;
    }

    public function addActivityCreator(Activity $activityCreator): self
    {
        if (!$this->activity_creator->contains($activityCreator)) {
            $this->activity_creator[] = $activityCreator;
            $activityCreator->setCreatedBy($this);
        }

        return $this;
    }

    public function removeActivityCreator(Activity $activityCreator): self
    {
        if ($this->activity_creator->contains($activityCreator)) {
            $this->activity_creator->removeElement($activityCreator);
            // set the owning side to null (unless already changed)
            if ($activityCreator->getCreatedBy() === $this) {
                $activityCreator->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ReponseSondage[]
     */
    public function getReponseSondages(): Collection
    {
        return $this->reponseSondages;
    }

    public function addReponseSondage(ReponseSondage $reponseSondage): self
    {
        if (!$this->reponseSondages->contains($reponseSondage)) {
            $this->reponseSondages[] = $reponseSondage;
            $reponseSondage->setUser($this);
        }

        return $this;
    }

    public function removeReponseSondage(ReponseSondage $reponseSondage): self
    {
        if ($this->reponseSondages->contains($reponseSondage)) {
            $this->reponseSondages->removeElement($reponseSondage);
            // set the owning side to null (unless already changed)
            if ($reponseSondage->getUser() === $this) {
                $reponseSondage->setUser(null);
            }
        }

        return $this;
    }

    public function getMdpOublie(): ?bool
    {
        return $this->mdpOublie;
    }

    public function setMdpOublie(?bool $mdpOublie): self
    {
        $this->mdpOublie = $mdpOublie;

        return $this;
    }

    /**
     * @return Collection|ReponseEleveQCM[]
     */
    public function getReponseEleveQCMs(): Collection
    {
        return $this->reponseEleveQCMs;
    }

    public function addReponseEleveQCM(ReponseEleveQCM $reponseEleveQCM): self
    {
        if (!$this->reponseEleveQCMs->contains($reponseEleveQCM)) {
            $this->reponseEleveQCMs[] = $reponseEleveQCM;
            $reponseEleveQCM->setUserId($this);
        }

        return $this;
    }

    public function removeReponseEleveQCM(ReponseEleveQCM $reponseEleveQCM): self
    {
        if ($this->reponseEleveQCMs->contains($reponseEleveQCM)) {
            $this->reponseEleveQCMs->removeElement($reponseEleveQCM);
            // set the owning side to null (unless already changed)
            if ($reponseEleveQCM->getUserId() === $this) {
                $reponseEleveQCM->setUserId(null);
            }
        }

        return $this;
    }
}
