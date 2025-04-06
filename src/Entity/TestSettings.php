<?php

namespace App\Entity;

use App\Repository\TestSettingsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TestSettingsRepository::class)]
class TestSettings
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $timeLimitInMinutes = null;

    #[ORM\Column(nullable: true)]
    private ?int $numberOfQuestions = null;

    #[ORM\Column]
    private ?bool $randomOrder = null;

    #[ORM\Column]
    private ?bool $showCorrectAnswersAfter = null;

    #[ORM\Column]
    private ?bool $isPracticeMode = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $accessCode = null;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $grade1Percentage = null;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $grade2Percentage = null;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $grade3Percentage = null;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $grade4Percentage = null;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $grade5Percentage = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTimeLimitInMinutes(): ?int
    {
        return $this->timeLimitInMinutes;
    }

    public function setTimeLimitInMinutes(?int $timeLimitInMinutes): static
    {
        $this->timeLimitInMinutes = $timeLimitInMinutes;

        return $this;
    }

    public function getNumberOfQuestions(): ?int
    {
        return $this->numberOfQuestions;
    }

    public function setNumberOfQuestions(?int $numberOfQuestions): static
    {
        $this->numberOfQuestions = $numberOfQuestions;

        return $this;
    }

    public function isRandomOrder(): ?bool
    {
        return $this->randomOrder;
    }

    public function setRandomOrder(bool $randomOrder): static
    {
        $this->randomOrder = $randomOrder;

        return $this;
    }

    public function isShowCorrectAnswersAfter(): ?bool
    {
        return $this->showCorrectAnswersAfter;
    }

    public function setShowCorrectAnswersAfter(bool $showCorrectAnswersAfter): static
    {
        $this->showCorrectAnswersAfter = $showCorrectAnswersAfter;

        return $this;
    }

    public function getIsPracticeMode(): ?bool
    {
        return $this->isPracticeMode;
    }

    public function setIsPracticeMode(?bool $isPracticeMode): self
    {
        $this->isPracticeMode = $isPracticeMode;
        return $this;
    }

    public function getAccessCode(): ?string
    {
        return $this->accessCode;
    }

    public function setAccessCode(?string $accessCode): static
    {
        $this->accessCode = $accessCode;

        return $this;
    }

    public function getGrade1Percentage(): ?float
    {
        return $this->grade1Percentage;
    }

    public function setGrade1Percentage(?float $grade1): self
    {
        $this->grade1Percentage = $grade1;
        return $this;
    }

    public function getGrade2Percentage(): ?float
    {
        return $this->grade2Percentage;
    }

    public function setGrade2Percentage(?float $grade2): self
    {
        $this->grade2Percentage = $grade2;
        return $this;
    }

    public function getGrade3Percentage(): ?float
    {
        return $this->grade3Percentage;
    }

    public function setGrade3Percentage(?float $grade3): self
    {
        $this->grade3Percentage = $grade3;
        return $this;
    }

    public function getGrade4Percentage(): ?float
    {
        return $this->grade4Percentage;
    }

    public function setGrade4Percentage(?float $grade4): self
    {
        $this->grade4Percentage = $grade4;
        return $this;
    }

    public function getGrade5Percentage(): ?float
    {
        return $this->grade5Percentage;
    }

    public function setGrade5Percentage(?float $grade5): self
    {
        $this->grade5Percentage = $grade5;
        return $this;
    }
}
