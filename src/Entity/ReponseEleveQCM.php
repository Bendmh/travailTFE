<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReponseEleveQCMRepository")
 */
class ReponseEleveQCM
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="reponseEleveQCMs")
     */
    private $userId;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Activity", inversedBy="reponseEleveQCMs")
     */
    private $activityId;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Questions", inversedBy="reponseEleveQCMs")
     */
    private $questionId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $reponse;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?User
    {
        return $this->userId;
    }

    public function setUserId(?User $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getActivityId(): ?Activity
    {
        return $this->activityId;
    }

    public function setActivityId(?Activity $activityId): self
    {
        $this->activityId = $activityId;

        return $this;
    }

    public function getQuestionId(): ?Questions
    {
        return $this->questionId;
    }

    public function setQuestionId(?Questions $questionId): self
    {
        $this->questionId = $questionId;

        return $this;
    }

    public function getReponse(): ?string
    {
        return $this->reponse;
    }

    public function setReponse(string $reponse): self
    {
        $this->reponse = $reponse;

        return $this;
    }
}
