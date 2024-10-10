<?php

namespace App\Entity;

use App\Repository\ThemeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ThemeRepository::class)]
class Theme
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $content = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    /**
     * @var Collection<int, Example>
     */
    #[ORM\OneToMany(targetEntity: Example::class, mappedBy: 'theme')]
    private Collection $examples;

    /**
     * @var Collection<int, UserStatistics>
     */
    #[ORM\OneToMany(targetEntity: UserStatistics::class, mappedBy: 'theme')]
    private Collection $userStatistics;

    /**
     * @var Collection<int, UserAttempts>
     */
    #[ORM\OneToMany(targetEntity: UserAttempts::class, mappedBy: 'theme')]
    private Collection $userAttempts;

    public function __construct()
    {
        $this->examples = new ArrayCollection();
        $this->userStatistics = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
        $this->userAttempts = new ArrayCollection();
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, Example>
     */
    public function getExamples(): Collection
    {
        return $this->examples;
    }

    public function addExample(Example $example): static
    {
        if (!$this->examples->contains($example)) {
            $this->examples->add($example);
            $example->setTheme($this);
        }

        return $this;
    }

    public function removeExample(Example $example): static
    {
        if ($this->examples->removeElement($example)) {
            if ($example->getTheme() === $this) {
                $example->setTheme(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserStatistics>
     */
    public function getUserStatistics(): Collection
    {
        return $this->userStatistics;
    }

    public function addUserStatistic(UserStatistics $userStatistic): static
    {
        if (!$this->userStatistics->contains($userStatistic)) {
            $this->userStatistics->add($userStatistic);
            $userStatistic->setTheme($this);
        }

        return $this;
    }

    public function removeUserStatistic(UserStatistics $userStatistic): static
    {
        if ($this->userStatistics->removeElement($userStatistic)) {
            if ($userStatistic->getTheme() === $this) {
                $userStatistic->setTheme(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserAttempts>
     */
    public function getUserAttempts(): Collection
    {
        return $this->userAttempts;
    }

    public function addUserAttempt(UserAttempts $userAttempt): static
    {
        if (!$this->userAttempts->contains($userAttempt)) {
            $this->userAttempts->add($userAttempt);
            $userAttempt->setTheme($this);
        }

        return $this;
    }

    public function removeUserAttempt(UserAttempts $userAttempt): static
    {
        if ($this->userAttempts->removeElement($userAttempt)) {
            if ($userAttempt->getTheme() === $this) {
                $userAttempt->setTheme(null);
            }
        }

        return $this;
    }
}
