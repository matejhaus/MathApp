<?php

namespace App\Entity;

use App\Repository\UserStatisticsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserStatisticsRepository::class)]
class UserStatistics
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userStatistics')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'userStatistics')]
    private ?Theme $theme = null;

    #[ORM\Column(nullable: true)]
    private ?int $correctAnswers = null;

    #[ORM\Column(nullable: true)]
    private ?int $incorrectAnswers = null;

    #[ORM\Column(nullable: true)]
    private ?int $totalAttempts = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $userLevel = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $lastAttemptDate = null;

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
        return $this->correctAnswers;
    }

    public function setCorrectAnswers(?int $correctAnswers): static
    {
        $this->correctAnswers = $correctAnswers;

        return $this;
    }

    public function getIncorrectAnswers(): ?int
    {
        return $this->incorrectAnswers;
    }

    public function setIncorrectAnswers(?int $incorrectAnswers): static
    {
        $this->incorrectAnswers = $incorrectAnswers;

        return $this;
    }

    public function getTotalAttempts(): ?int
    {
        return $this->totalAttempts;
    }

    public function setTotalAttempts(?int $totalAttempts): static
    {
        $this->totalAttempts = $totalAttempts;

        return $this;
    }

    public function getUserLevel(): ?string
    {
        return $this->userLevel;
    }

    public function setUserLevel(?string $userLevel): static
    {
        $this->userLevel = $userLevel;

        return $this;
    }

    public function getLastAttemptDate(): ?\DateTimeInterface
    {
        return $this->lastAttemptDate;
    }

    public function setLastAttemptDate(?\DateTimeInterface $lastAttemptDate): static
    {
        $this->lastAttemptDate = $lastAttemptDate;

        return $this;
    }
}
