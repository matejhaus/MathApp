<?php

namespace App\Entity;

use App\Repository\UserAttemptsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserAttemptsRepository::class)]
class UserAttempts
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userAttempts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'userAttempts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Theme $theme = null;

    #[ORM\Column]
    private ?int $correct_answers = null;

    #[ORM\Column]
    private ?int $incorrect_answers = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getTheme(): ?Theme
    {
        return $this->theme;
    }

    public function setTheme(?Theme $theme): static
    {
        $this->theme = $theme;

        return $this;
    }

    public function getCorrectAnswers(): ?int
    {
        return $this->correct_answers;
    }

    public function setCorrectAnswers(int $correct_answers): static
    {
        $this->correct_answers = $correct_answers;

        return $this;
    }

    public function getIncorrectAnswers(): ?int
    {
        return $this->incorrect_answers;
    }

    public function setIncorrectAnswers(int $incorrect_answers): static
    {
        $this->incorrect_answers = $incorrect_answers;

        return $this;
    }
}
