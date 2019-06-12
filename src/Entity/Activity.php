<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ActivityRepository")
 * @Vich\Uploadable
 * @UniqueEntity(
 *     fields = {"name"},
 *     message = "Ce nom d'activité existe déjà !"
 * )
 */
class Activity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $name;


    /**
     * @var string|null
     * @ORM\column(type="string", length=255, nullable=true)
     */
    private $fileName;

    /**
     * @Vich\UploadableField(mapping="activity_image", fileNameProperty="fileName")
     * @var File|null
     * @Assert\File(
     *     mimeTypes = { "image/png", "image/jpeg" },
     *     mimeTypesMessage = "Choisissez une image png ou jpeg"
     * )
     */
    private $imageFile;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserActivity", mappedBy="activity_id", cascade={"persist", "remove"})
     */
    private $userActivities;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Questions", mappedBy="activity", cascade={"persist", "remove"})
     */
    private $question;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="activity_creator")
     * @ORM\JoinColumn(nullable=false)
     */
    private $created_by;

    /**
     * @ORM\Column(type="boolean")
     */
    private $visible;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\QuestionsGroupes", mappedBy="activity", cascade={"persist", "remove"})
     */
    private $questionsGroupes;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ActivityType", inversedBy="activity")
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\QuestionSondage", mappedBy="activity", cascade={"persist", "remove"})
     */
    private $questionSondage;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Brainstorming", mappedBy="activity", cascade={"persist", "remove"})
     */
    private $questionBrainstorming;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\QuestionAudio", mappedBy="activity", cascade={"persist", "remove"})
     */
    private $questionAudio;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ReponseEleveQCM", mappedBy="activityId", cascade={"remove"})
     */
    private $reponseEleveQCMs;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ReponseEleveAssociation", mappedBy="activityId")
     */
    private $reponseEleveAssociations;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ReponseEleveBrainstorming", mappedBy="activity", cascade={"remove"})
     */
    private $reponseEleveBrainstormings;

    public function __construct()
    {
        $this->userActivities = new ArrayCollection();
        $this->question = new ArrayCollection();
        $this->questionsGroupes = new ArrayCollection();
        $this->reponseEleveQCMs = new ArrayCollection();
        $this->reponseEleveAssociations = new ArrayCollection();
        $this->reponseEleveBrainstormings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    /**
     * @param null|string $fileName
     * @return Questions
     */
    public function setFileName(?string $fileName): Activity
    {
        $this->fileName = $fileName;
        return $this;
    }

    /**
     * @return null|File
     */
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * @param null|File $imageFile
     * @return Questions
     */
    public function setImageFile(?File $imageFile): Activity
    {
        $this->imageFile = $imageFile;
        if ($this->imageFile instanceof UploadedFile) {
            $this->updated_at = new \DateTime('now');
        }
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return Collection|UserActivity[]
     */
    public function getUserActivities(): Collection
    {
        return $this->userActivities;
    }

    public function addUserActivity(UserActivity $userActivity): self
    {
        if (!$this->userActivities->contains($userActivity)) {
            $this->userActivities[] = $userActivity;
            $userActivity->setActivityId($this);
        }

        return $this;
    }

    public function removeUserActivity(UserActivity $userActivity): self
    {
        if ($this->userActivities->contains($userActivity)) {
            $this->userActivities->removeElement($userActivity);
            // set the owning side to null (unless already changed)
            if ($userActivity->getActivityId() === $this) {
                $userActivity->setActivityId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Questions[]
     */
    public function getQuestion(): Collection
    {
        return $this->question;
    }

    public function addQuestion(Questions $question): self
    {
        if (!$this->question->contains($question)) {
            $this->question[] = $question;
            $question->setActivity($this);
        }

        return $this;
    }

    public function removeQuestion(Questions $question): self
    {
        if ($this->question->contains($question)) {
            $this->question->removeElement($question);
            // set the owning side to null (unless already changed)
            if ($question->getActivity() === $this) {
                $question->setActivity(null);
            }
        }

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->created_by;
    }

    public function setCreatedBy(?User $created_by): self
    {
        $this->created_by = $created_by;

        return $this;
    }

    public function getVisible(): ?bool
    {
        return $this->visible;
    }

    public function setVisible(bool $visible): self
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * @return Collection|QuestionsGroupes[]
     */
    public function getQuestionsGroupes(): Collection
    {
        return $this->questionsGroupes;
    }

    public function addQuestionsGroupe(QuestionsGroupes $questionsGroupe): self
    {
        if (!$this->questionsGroupes->contains($questionsGroupe)) {
            $this->questionsGroupes[] = $questionsGroupe;
            $questionsGroupe->setActivity($this);
        }

        return $this;
    }

    public function removeQuestionsGroupe(QuestionsGroupes $questionsGroupe): self
    {
        if ($this->questionsGroupes->contains($questionsGroupe)) {
            $this->questionsGroupes->removeElement($questionsGroupe);
            // set the owning side to null (unless already changed)
            if ($questionsGroupe->getActivity() === $this) {
                $questionsGroupe->setActivity(null);
            }
        }

        return $this;
    }

    public function getType(): ?ActivityType
    {
        return $this->type;
    }

    public function setType(?ActivityType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getQuestionSondage(): ?QuestionSondage
    {
        return $this->questionSondage;
    }

    public function setQuestionSondage(QuestionSondage $questionSondage): self
    {
        $this->questionSondage = $questionSondage;

        // set the owning side of the relation if necessary
        if ($this !== $questionSondage->getActivity()) {
            $questionSondage->setActivity($this);
        }

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
            $reponseEleveQCM->setActivityId($this);
        }

        return $this;
    }

    public function removeReponseEleveQCM(ReponseEleveQCM $reponseEleveQCM): self
    {
        if ($this->reponseEleveQCMs->contains($reponseEleveQCM)) {
            $this->reponseEleveQCMs->removeElement($reponseEleveQCM);
            // set the owning side to null (unless already changed)
            if ($reponseEleveQCM->getActivityId() === $this) {
                $reponseEleveQCM->setActivityId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ReponseEleveAssociation[]
     */
    public function getReponseEleveAssociations(): Collection
    {
        return $this->reponseEleveAssociations;
    }

    public function addReponseEleveAssociation(ReponseEleveAssociation $reponseEleveAssociation): self
    {
        if (!$this->reponseEleveAssociations->contains($reponseEleveAssociation)) {
            $this->reponseEleveAssociations[] = $reponseEleveAssociation;
            $reponseEleveAssociation->setActivityId($this);
        }

        return $this;
    }

    public function removeReponseEleveAssociation(ReponseEleveAssociation $reponseEleveAssociation): self
    {
        if ($this->reponseEleveAssociations->contains($reponseEleveAssociation)) {
            $this->reponseEleveAssociations->removeElement($reponseEleveAssociation);
            // set the owning side to null (unless already changed)
            if ($reponseEleveAssociation->getActivityId() === $this) {
                $reponseEleveAssociation->setActivityId(null);
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getQuestionBrainstorming()
    {
        return $this->questionBrainstorming;
    }

    /**
     * @param mixed $questionBrainstorming
     */
    public function setQuestionBrainstorming($questionBrainstorming): void
    {
        $this->questionBrainstorming = $questionBrainstorming;
    }

    /**
     * @return Collection|ReponseEleveBrainstorming[]
     */
    public function getReponseEleveBrainstormings(): Collection
    {
        return $this->reponseEleveBrainstormings;
    }

    public function addReponseEleveBrainstorming(ReponseEleveBrainstorming $reponseEleveBrainstorming): self
    {
        if (!$this->reponseEleveBrainstormings->contains($reponseEleveBrainstorming)) {
            $this->reponseEleveBrainstormings[] = $reponseEleveBrainstorming;
            $reponseEleveBrainstorming->setActivity($this);
        }

        return $this;
    }

    public function removeReponseEleveBrainstorming(ReponseEleveBrainstorming $reponseEleveBrainstorming): self
    {
        if ($this->reponseEleveBrainstormings->contains($reponseEleveBrainstorming)) {
            $this->reponseEleveBrainstormings->removeElement($reponseEleveBrainstorming);
            // set the owning side to null (unless already changed)
            if ($reponseEleveBrainstorming->getActivity() === $this) {
                $reponseEleveBrainstorming->setActivity(null);
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getQuestionAudio()
    {
        return $this->questionAudio;
    }

    /**
     * @param mixed $questionAudio
     */
    public function setQuestionAudio($questionAudio): void
    {
        $this->questionAudio = $questionAudio;
    }
}
