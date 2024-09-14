<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'Il existe déjà un autre utilisateur possédant cet email !')]
class Utilisateur implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

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

    #[ORM\Column(length: 255)]
    private ?string $fullname = null;

    #[ORM\Column(length: 32)]
    private ?string $telephone = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateDerniereConnexion = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isPasswordModified = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'utilisateursCrees')]
    private ?self $createdBy = null;

    /**
     * @var Collection<int, self>
     */
    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'createdBy')]
    private Collection $utilisateursCrees;

    #[ORM\Column(nullable: false)]
    private ?bool $enable_y_n = true;

    /**
     * @var Collection<int, Enfant>
     */
    #[ORM\OneToMany(targetEntity: Enfant::class, mappedBy: 'agent_saisie')]
    private Collection $enfants_saisis;

    public function __construct()
    {
        $this->utilisateursCrees = new ArrayCollection();
        $this->enfants_saisis = new ArrayCollection();
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
        // guarantee every user at least has ROLE_USER
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
     * @see PasswordAuthenticatedUserInterface
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

    public function getFullname(): ?string
    {
        return $this->fullname;
    }

    public function setFullname(string $fullname): static
    {
        $this->fullname = $fullname;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * CallBack appelé à chaque fois que l'on veut enregistrer un user pour
     * prendre automatiquement sa date de création du compte .
     */
    #[ORM\PrePersist]
    public function PrePersist()
    {
        if (empty($this->createdAt)) {
            $this->createdAt = new \DateTimeImmutable();
        }
        $this->dateDernierConnexion = new \DateTime();
    }

    /**
     * CallBack appelé à chaque fois que l'on veut mettre à jour un user pour
     * prendre automatiquement sa date de dernière visite du compte .
     */
    #[ORM\PreUpdate]
    public function  PreUpdate()
    {
        $this->dateDernierConnexion = new \DateTime();
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getDateDerniereConnexion(): ?\DateTimeInterface
    {
        return $this->dateDerniereConnexion;
    }

    public function setDateDerniereConnexion(?\DateTimeInterface $dateDerniereConnexion): static
    {
        $this->dateDerniereConnexion = $dateDerniereConnexion;

        return $this;
    }

    public function isPasswordModified(): ?bool
    {
        return $this->isPasswordModified;
    }

    public function setPasswordModified(?bool $isPasswordModified): static
    {
        $this->isPasswordModified = $isPasswordModified;

        return $this;
    }

    public function getCreatedBy(): ?self
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?self $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getUtilisateursCrees(): Collection
    {
        return $this->utilisateursCrees;
    }

    public function addUtilisateursCree(self $utilisateursCree): static
    {
        if (!$this->utilisateursCrees->contains($utilisateursCree)) {
            $this->utilisateursCrees->add($utilisateursCree);
            $utilisateursCree->setCreatedBy($this);
        }

        return $this;
    }

    public function removeUtilisateursCree(self $utilisateursCree): static
    {
        if ($this->utilisateursCrees->removeElement($utilisateursCree)) {
            // set the owning side to null (unless already changed)
            if ($utilisateursCree->getCreatedBy() === $this) {
                $utilisateursCree->setCreatedBy(null);
            }
        }

        return $this;
    }

    public function isEnableYN(): ?bool
    {
        return $this->enable_y_n;
    }

    public function setEnableYN(?bool $enable_y_n): static
    {
        $this->enable_y_n = $enable_y_n;

        return $this;
    }

    /**
     * @return Collection<int, Enfant>
     */
    public function getEnfantsSaisis(): Collection
    {
        return $this->enfants_saisis;
    }

    public function addEnfantsSaisi(Enfant $enfantsSaisi): static
    {
        if (!$this->enfants_saisis->contains($enfantsSaisi)) {
            $this->enfants_saisis->add($enfantsSaisi);
            $enfantsSaisi->setAgentSaisie($this);
        }

        return $this;
    }

    public function removeEnfantsSaisi(Enfant $enfantsSaisi): static
    {
        if ($this->enfants_saisis->removeElement($enfantsSaisi)) {
            // set the owning side to null (unless already changed)
            if ($enfantsSaisi->getAgentSaisie() === $this) {
                $enfantsSaisi->setAgentSaisie(null);
            }
        }

        return $this;
    }
}
