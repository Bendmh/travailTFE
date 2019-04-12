<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LessonsRepository")
 */
class Lessons
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
     * @ORM\ManyToMany(targetEntity="App\Entity\Category", mappedBy="category_lesson")
     */
    private $Lesson_category;

    public function __construct()
    {
        $this->Lesson_category = new ArrayCollection();
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
     * @return Collection|Category[]
     */
    public function getLessonCategory(): Collection
    {
        return $this->Lesson_category;
    }

    public function addLessonCategory(Category $lessonCategory): self
    {
        if (!$this->Lesson_category->contains($lessonCategory)) {
            $this->Lesson_category[] = $lessonCategory;
            $lessonCategory->addCategoryLesson($this);
        }

        return $this;
    }

    public function removeLessonCategory(Category $lessonCategory): self
    {
        if ($this->Lesson_category->contains($lessonCategory)) {
            $this->Lesson_category->removeElement($lessonCategory);
            $lessonCategory->removeCategoryLesson($this);
        }

        return $this;
    }
}
