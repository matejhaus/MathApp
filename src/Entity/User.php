<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lastName = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    /**
     * @var Collection<int, UserStatistics>
     */
    #[ORM\OneToMany(targetEntity: UserStatistics::class, mappedBy: 'user')]
    private Collection $userStatistics;

    /**
     * @var Collection<int, UserAttempts>
     */
    #[ORM\OneToMany(targetEntity: UserAttempts::class, mappedBy: 'user')]
    private Collection $userAttempts;

    /**
     * @var Collection<int, Grade>
     */
    #[ORM\OneToMany(targetEntity: Grade::class, mappedBy: 'user')]
    private Collection $grades;

    public function __construct()
    {
        $this->userStatistics = new ArrayCollection();
        $this->userAttempts = new ArrayCollection();
        $this->grades = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return string the hashed password for this user
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, UserStatistics>
     */
    public function getUserStatistics(): Collection
    {
        return $this->userStatistics;
    }

    public function getUserStatisticsCount(): int
    {
        return $this->userStatistics->count();
    }

    public function addUserStatistic(UserStatistics $userStatistic): static
    {
        if (!$this->userStatistics->contains($userStatistic)) {
            $this->userStatistics->add($userStatistic);
            $userStatistic->setUser($this);
        }

        return $this;
    }

    public function removeUserStatistic(UserStatistics $userStatistic): static
    {
        if ($this->userStatistics->removeElement($userStatistic)) {
            // set the owning side to null (unless already changed)
            if ($userStatistic->getUser() === $this) {
                $userStatistic->setUser(null);
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
            $userAttempt->setUser($this);
        }

        return $this;
    }

    public function removeUserAttempt(UserAttempts $userAttempt): static
    {
        if ($this->userAttempts->removeElement($userAttempt)) {
            // set the owning side to null (unless already changed)
            if ($userAttempt->getUser() === $this) {
                $userAttempt->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Grade>
     */
    public function getGrades(): Collection
    {
        return $this->grades;
    }

    public function addGrade(Grade $grade): static
    {
        if (!$this->grades->contains($grade)) {
            $this->grades->add($grade);
            $grade->setUser($this);
        }

        return $this;
    }

    public function removeGrade(Grade $grade): static
    {
        if ($this->grades->removeElement($grade)) {
            // set the owning side to null (unless already changed)
            if ($grade->getUser() === $this) {
                $grade->setUser(null);
            }
        }

        return $this;
    }
}
