<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuestionSondageRepository")
 */
class QuestionSondage
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
    private $question;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $choix1;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $choix2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $choix3;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $choix4;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $choix5;

    private $reptest;

    /**
     * @return mixed
     */
    public function getReptest()
    {
        return $this->reptest;
    }

    /**
     * @param mixed $reptest
     */
    public function setReptest($reptest): void
    {
        $this->reptest = $reptest;
    }

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Activity", inversedBy="questionSondage", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $activity;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ReponseSondage", mappedBy="questionSondage", cascade={"persist", "remove"})
     */
    private $response;

    public function __construct()
    {
        $this->response = new ArrayCollection();
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

    public function getChoix1(): ?string
    {
        return $this->choix1;
    }

    public function setChoix1(string $choix1): self
    {
        $this->choix1 = $choix1;

        return $this;
    }

    public function getChoix2(): ?string
    {
        return $this->choix2;
    }

    public function setChoix2(string $choix2): self
    {
        $this->choix2 = $choix2;

        return $this;
    }

    public function getChoix3(): ?string
    {
        return $this->choix3;
    }

    public function setChoix3(?string $choix3): self
    {
        $this->choix3 = $choix3;

        return $this;
    }

    public function getChoix4(): ?string
    {
        return $this->choix4;
    }

    public function setChoix4(?string $choix4): self
    {
        $this->choix4 = $choix4;

        return $this;
    }

    public function getChoix5(): ?string
    {
        return $this->choix5;
    }

    public function setChoix5(?string $choix5): self
    {
        $this->choix5 = $choix5;

        return $this;
    }

    public function getActivity(): ?Activity
    {
        return $this->activity;
    }

    public function setActivity(Activity $activity): self
    {
        $this->activity = $activity;

        return $this;
    }

    /**
     * @return Collection|ReponseSondage[]
     */
    public function getResponse(): Collection
    {
        return $this->response;
    }

    public function addResponse(ReponseSondage $response): self
    {
        if (!$this->response->contains($response)) {
            $this->response[] = $response;
            $response->setQuestionSondage($this);
        }

        return $this;
    }

    public function removeResponse(ReponseSondage $response): self
    {
        if ($this->response->contains($response)) {
            $this->response->removeElement($response);
            // set the owning side to null (unless already changed)
            if ($response->getQuestionSondage() === $this) {
                $response->setQuestionSondage(null);
            }
        }

        return $this;
    }
}
