<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
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
     * @ORM\ManyToMany(targetEntity="App\Entity\Lessons", inversedBy="Lesson_category")
     */
    private $category_lesson;

    public function __construct()
    {
        $this->category_lesson = new ArrayCollection();
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
     * @return Collection|Lessons[]
     */
    public function getCategoryLesson(): Collection
    {
        return $this->category_lesson;
    }

    public function addCategoryLesson(Lessons $categoryLesson): self
    {
        if (!$this->category_lesson->contains($categoryLesson)) {
            $this->category_lesson[] = $categoryLesson;
        }

        return $this;
    }

    public function removeCategoryLesson(Lessons $categoryLesson): self
    {
        if ($this->category_lesson->contains($categoryLesson)) {
            $this->category_lesson->removeElement($categoryLesson);
        }

        return $this;
    }
}
