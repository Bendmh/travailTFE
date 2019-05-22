<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ActivityTypeRepository")
 * @Vich\Uploadable
 */
class ActivityType
{

    const QCM_ACTIVITY = 'QCM';
    const ASSOCIATION_ACTIVITY = 'association';
    const SONDAGE_ACTIVITY = 'sondage';

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
     * @var string|null
     * @ORM\column(type="string", length=255, nullable=true)
     */
    private $fileName;

    /**
     * @Vich\UploadableField(mapping="type_activity_image", fileNameProperty="fileName")
     * @var File|null
     */
    private $imageFile;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Activity", mappedBy="type")
     */
    private $activity;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CSV", mappedBy="type")
     */
    private $cSVs;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

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

    /**
     * @return null|string
     */
    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    /**
     * @param null|string $fileName
     */
    public function setFileName(?string $fileName): void
    {
        $this->fileName = $fileName;
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
     */
    public function setImageFile(?File $imageFile): void
    {
        $this->imageFile = $imageFile;
        if ($this->imageFile instanceof UploadedFile) {
            $this->updated_at = new \DateTime('now');
        }
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
}
