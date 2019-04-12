<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ActivityTypeRepository")
 */
class ActivityType
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
     * @ORM\OneToMany(targetEntity="App\Entity\Activity", mappedBy="type")
     */
    private $activity;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CSV", mappedBy="type")
     */
    private $cSVs;

    public function __construct()
    {
        $this->activity = new ArrayCollection();
        $this->cSVs = new ArrayCollection();
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
            $activity->setType($this);
        }

        return $this;
    }

    public function removeActivity(Activity $activity): self
    {
        if ($this->activity->contains($activity)) {
            $this->activity->removeElement($activity);
            // set the owning side to null (unless already changed)
            if ($activity->getType() === $this) {
                $activity->setType(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CSV[]
     */
    public function getCSVs(): Collection
    {
        return $this->cSVs;
    }

    public function addCSV(CSV $cSV): self
    {
        if (!$this->cSVs->contains($cSV)) {
            $this->cSVs[] = $cSV;
            $cSV->setType($this);
        }

        return $this;
    }

    public function removeCSV(CSV $cSV): self
    {
        if ($this->cSVs->contains($cSV)) {
            $this->cSVs->removeElement($cSV);
            // set the owning side to null (unless already changed)
            if ($cSV->getType() === $this) {
                $cSV->setType(null);
            }
        }

        return $this;
    }
}
