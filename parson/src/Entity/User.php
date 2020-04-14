<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{

    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fullName;

    /**
     * @ORM\Column(type="boolean")
     */
    private $sexe;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $imgUrl;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Course", mappedBy="author")
     */
    private $createdCourses;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Course", inversedBy="users")
     */
    private $registredInCourses;

    public function __construct()
    {
        $this->createdCourses = new ArrayCollection();
        $this->registredInCourses = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getSexe(): ?bool
    {
        return $this->sexe;
    }

    public function setSexe(bool $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getImgUrl(): ?string
    {
        return $this->imgUrl;
    }

    public function setImgUrl(?string $imgUrl): self
    {
        $this->imgUrl = $imgUrl;

        return $this;
    }

    /**
     * @return Collection|Course[]
     */
    public function getCreatedCourses(): Collection
    {
        return $this->createdCourses;
    }

    public function addCreatedCourse(Course $createdCourse): self
    {
        if (!$this->createdCourses->contains($createdCourse)) {
            $this->createdCourses[] = $createdCourse;
            $createdCourse->setAuthor($this);
        }

        return $this;
    }

    public function removeCreatedCourse(Course $createdCourse): self
    {
        if ($this->createdCourses->contains($createdCourse)) {
            $this->createdCourses->removeElement($createdCourse);
            // set the owning side to null (unless already changed)
            if ($createdCourse->getAuthor() === $this) {
                $createdCourse->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Course[]
     */
    public function getRegistredInCourses(): Collection
    {
        return $this->registredInCourses;
    }

    public function addRegistredInCourse(Course $registredInCourse): self
    {
        if (!$this->registredInCourses->contains($registredInCourse)) {
            $this->registredInCourses[] = $registredInCourse;
        }

        return $this;
    }

    public function removeRegistredInCourse(Course $registredInCourse): self
    {
        if ($this->registredInCourses->contains($registredInCourse)) {
            $this->registredInCourses->removeElement($registredInCourse);
        }

        return $this;
    }
}
