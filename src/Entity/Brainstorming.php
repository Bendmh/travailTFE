<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BrainstormingRepository")
 */
class Brainstorming
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
    private $subject;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *     min = 1,
     *     max = 5,
     *     minMessage = "Il faut au moins {{limit}} réponse",
     *     maxMessage = "Impossible de dépasser {{limit}}"
     * )
     */
    private $possibilityAnswerNumber;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Activity", cascade={"persist", "remove"})
     */
    private $activity;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getPossibilityAnswerNumber(): ?int
    {
        return $this->possibilityAnswerNumber;
    }

    public function setPossibilityAnswerNumber(int $possibilityAnswerNumber): self
    {
        $this->possibilityAnswerNumber = $possibilityAnswerNumber;

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
}
