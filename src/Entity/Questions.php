<?php

namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuestionsRepository")
 * @Vich\Uploadable
 */
class Questions
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string|null
     * @ORM\column(type="string", length=255, nullable=true)
     */
    private $fileName;

    /**
     * @Vich\UploadableField(mapping="questions_image", fileNameProperty="fileName")
     * @var File|null
     * @Assert\File(
     *     mimeTypes = { "image/png", "image/jpeg" },
     *     mimeTypesMessage = "Choisissez une image png ou jpeg"
     * )
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $question;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $bonneReponse1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $bonneReponse2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $bonneReponse3;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mauvaiseReponse1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mauvaiseReponse2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mauvaiseReponse3;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\Column(type="string", length=5)
     */
    private $points;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Activity", inversedBy="question")
     */
    private $activity;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ReponseEleveQCM", mappedBy="questionId")
     */
    private $reponseEleveQCMs;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $uniqueReponse;

    public function __construct()
    {
        $this->reponseEleveQCMs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): self
    {
        $this->question = $question;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBonneReponse1()
    {
        return $this->bonneReponse1;
    }

    /**
     * @param mixed $bonneReponse1
     */
    public function setBonneReponse1($bonneReponse1): void
    {
        $this->bonneReponse1 = $bonneReponse1;
    }
    /**
     * @return mixed
     */
    public function getBonneReponse2()
    {
        return $this->bonneReponse2;
    }

    /**
     * @param mixed $bonneReponse2
     */
    public function setBonneReponse2($bonneReponse2): void
    {
        $this->bonneReponse2 = $bonneReponse2;
    }
    /**
     * @return mixed
     */
    public function getBonneReponse3()
    {
        return $this->bonneReponse3;
    }

    /**
     * @param mixed $bonneReponse3
     */
    public function setBonneReponse3($bonneReponse3): void
    {
        $this->bonneReponse3 = $bonneReponse3;
    }

    /**
     * @return mixed
     */
    public function getMauvaiseReponse1()
    {
        return $this->mauvaiseReponse1;
    }

    /**
     * @param mixed $mauvaiseReponse2
     */
    public function setMauvaiseReponse1($mauvaiseReponse1): void
    {
        $this->mauvaiseReponse1 = $mauvaiseReponse1;
    }
    /**
     * @return mixed
     */
    public function getMauvaiseReponse2()
    {
        return $this->mauvaiseReponse2;
    }

    /**
     * @param mixed $mauvaiseReponse2
     */
    public function setMauvaiseReponse2($mauvaiseReponse2): void
    {
        $this->mauvaiseReponse2 = $mauvaiseReponse2;
    }
    /**
     * @return mixed
     */
    public function getMauvaiseReponse3()
    {
        return $this->mauvaiseReponse3;
    }

    /**
     * @param mixed $mauvaiseReponse3
     */
    public function setMauvaiseReponse3($mauvaiseReponse3): void
    {
        $this->mauvaiseReponse3 = $mauvaiseReponse3;
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
    public function setFileName(?string $fileName): Questions
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
    public function setImageFile(?File $imageFile): Questions
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

    public function getPoints(): ?string
    {
        return $this->points;
    }

    public function setPoints(string $points): self
    {
        $this->points = $points;

        return $this;
    }

    public function getActivity(): ?Activity
    {
        return $this->activity;
    }

    public function setActivity(?Activity $activity): self
    {
        $this->activity = $activity;

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
            $reponseEleveQCM->setQuestionId($this);
        }

        return $this;
    }

    public function removeReponseEleveQCM(ReponseEleveQCM $reponseEleveQCM): self
    {
        if ($this->reponseEleveQCMs->contains($reponseEleveQCM)) {
            $this->reponseEleveQCMs->removeElement($reponseEleveQCM);
            // set the owning side to null (unless already changed)
            if ($reponseEleveQCM->getQuestionId() === $this) {
                $reponseEleveQCM->setQuestionId(null);
            }
        }

        return $this;
    }

    public function getUniqueReponse(): ?bool
    {
        return $this->uniqueReponse;
    }

    public function setUniqueReponse(?bool $uniqueReponse): self
    {
        $this->uniqueReponse = $uniqueReponse;

        return $this;
    }

}
