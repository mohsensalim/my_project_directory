<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Username = null;

    #[ORM\Column(length: 191)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'User', orphanRemoval: true)]
    private Collection $tasks;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->Username;
    }

    public function setUsername(string $Username): static
    {
        $this->Username = $Username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles()
    {
        return ["ROLE_USER"];
    }


    public function getSalt()
    {
        return null;

        }

        public function eraseCredentials()
        {
            return null;
        }

        public function getImage(): ?string
        {
            return $this->image;
        }

        public function setImage(?string $image): static
        {
            $this->image = $image;

            return $this;
        }

        /**
         * @return Collection<int, Task>
         */
        public function getTasks(): Collection
        {
            return $this->tasks;
        }

        public function addTask(Task $task): static
        {
            if (!$this->tasks->contains($task)) {
                $this->tasks->add($task);
                $task->setUser($this);
            }

            return $this;
        }

        public function removeTask(Task $task): static
        {
            if ($this->tasks->removeElement($task)) {
                // set the owning side to null (unless already changed)
                if ($task->getUser() === $this) {
                    $task->setUser(null);
                }
            }

            return $this;
        }
}
