<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;


/**
 * @ORM\Entity(repositoryClass="App\Repository\CourseRepository")
 */
class Course
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
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $imgUrl;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $category;

    /**
     * @ORM\Column(type="float")
     */
    private $timeNeeded;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Exercise", mappedBy="course")
     */
    private $exercises;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="createdCourses")
     * @ORM\JoinColumn(nullable=false,onDelete="CASCADE")
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserCourse", mappedBy="course")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="course")
     */
    private $comments;


    public function __construct()
    {
        $this->exercises = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImgUrl(): ?string
    {
        if ($this->imgUrl)
        {
            return '/uploads/course_img/'.$this->imgUrl;
        }
        return null;
    }

    public function setImgUrl(string $imgUrl): self
    {
        $this->imgUrl = $imgUrl;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getTimeNeeded(): ?float
    {
        return $this->timeNeeded;
    }

    public function setTimeNeeded(float $timeNeeded): self
    {
        $this->timeNeeded = $timeNeeded;

        return $this;
    }

    /**
     * @return Collection|Exercise[]
     */
    public function getExercises(): Collection
    {
        return $this->exercises;
    }

    public function addExercise(Exercise $exercise): self
    {
        if (!$this->exercises->contains($exercise)) {
            $this->exercises[] = $exercise;
            $exercise->setCourse($this);
        }

        return $this;
    }

    public function removeExercise(Exercise $exercise): self
    {
        if ($this->exercises->contains($exercise)) {
            $this->exercises->removeElement($exercise);
            // set the owning side to null (unless already changed)
            if ($exercise->getCourse() === $this) {
                $exercise->setCourse(null);
            }
        }

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection|UserCourse[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(UserCourse $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setCourse($this);
        }

        return $this;
    }

    public function removeUser(UserCourse $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getCourse() === $this) {
                $user->setCourse(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setCourse($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getCourse() === $this) {
                $comment->setCourse(null);
            }
        }

        return $this;
    }


}
