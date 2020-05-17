<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;


/**
 * @ORM\Entity(repositoryClass="App\Repository\ExerciseRepository")
 */
class Exercise
{
    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $question;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="float",nullable=true)
     */
    private $mark;

    /**
     * @ORM\Column(type="json")
     */
    private $solution = [];

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Course", inversedBy="exercises")
     * @ORM\JoinColumn(nullable=false,onDelete="CASCADE")
     */
    private $course;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getMark(): ?float
    {
        return $this->mark;
    }

    public function setMark(float $mark): self
    {
        $this->mark = $mark;

        return $this;
    }

    public function getSolution(): ?array
    {
        return $this->solution;
    }

    public function setSolution(array $solution): self
    {
        $this->solution = $solution;

        return $this;
    }

    public function getCourse(): ?Course
    {
        return $this->course;
    }

    public function setCourse(?Course $course): self
    {
        $this->course = $course;

        return $this;
    }
}
