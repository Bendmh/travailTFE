<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReponseSondageRepository")
 */
class ReponseSondage
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\QuestionSondage", inversedBy="response")
     * @ORM\JoinColumn(nullable=false)
     */
    private $questionSondage;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $response;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="reponseSondages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestionSondage(): ?QuestionSondage
    {
        return $this->questionSondage;
    }

    public function setQuestionSondage(?QuestionSondage $questionSondage): self
    {
        $this->questionSondage = $questionSondage;

        return $this;
    }

    public function getResponse(): ?string
    {
        return $this->response;
    }

    public function setResponse(string $response): self
    {
        $this->response = $response;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
