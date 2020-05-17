<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserCourseRepository")
 */
class UserCourse
{

    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="registredInCourses")
     * @ORM\JoinColumn(nullable=false,onDelete="CASCADE")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Course", inversedBy="users")
     * @ORM\JoinColumn(nullable=false,onDelete="CASCADE")
     */
    private $course;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $rate;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $score;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $results = [];

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCourse(): ?Course
    {
        return $this->course;
    }

    public function setCourse(?Course $course): self
    {
        $this->course = $course;

        return $this;
    }

    public function getRate(): ?float
    {
        return $this->rate;
    }

    public function setRate(?float $rate): self
    {
        $this->rate = $rate;

        return $this;
    }

    public function getScore(): ?float
    {
        return $this->score;
    }

    public function setScore(?float $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getResults(): ?array
    {
        return $this->results;
    }

    public function setResults(?array $results): self
    {
        $this->results = $results;

        return $this;
    }
}
