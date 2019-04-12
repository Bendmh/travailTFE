<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuestionsGroupesRepository")
 */
class QuestionsGroupes
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
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Activity", inversedBy="questionsGroupes")
     */
    private $activity;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\QuestionsReponses", mappedBy="question", cascade={"persist", "remove"})
     */
    private $questionsReponses;

    public function __construct()
    {
        $this->questionsReponses = new ArrayCollection();
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
     * @return Collection|QuestionsReponses[]
     */
    public function getQuestionsReponses(): Collection
    {
        return $this->questionsReponses;
    }

    public function addQuestionsReponse(QuestionsReponses $questionsReponse): self
    {
        if (!$this->questionsReponses->contains($questionsReponse)) {
            $this->questionsReponses[] = $questionsReponse;
            $questionsReponse->setQuestion($this);
        }

        return $this;
    }

    public function removeQuestionsReponse(QuestionsReponses $questionsReponse): self
    {
        if ($this->questionsReponses->contains($questionsReponse)) {
            $this->questionsReponses->removeElement($questionsReponse);
            // set the owning side to null (unless already changed)
            if ($questionsReponse->getQuestion() === $this) {
                $questionsReponse->setQuestion(null);
            }
        }

        return $this;
    }
}
