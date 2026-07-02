<?php

namespace App\Entity;

use App\Repository\RecenseurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: RecenseurRepository::class)]
#[UniqueEntity(fields: 'matricule', message: 'Cet agent de collecte existe déjà !!!')]
class Recenseur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 64)]
    private ?string $nom = null;

    #[ORM\Column(length: 6)]
    private ?string $code = null;

    #[ORM\ManyToOne(inversedBy: 'recenseurs')]
    private ?Equipe $equipe = null;

    /**
     * @var Collection<int, Agent>
     */
    #[ORM\OneToMany(targetEntity: Agent::class, mappedBy: 'recenseur')]
    private Collection $agents;

    #[ORM\Column(length: 8)]
    #[Assert\Length(
        min: 8,
        max: 8,
        exactMessage: 'Le matricule doit être de {{ limit }} caractères !',
        minMessage: 'Le minimum est de {{ limit }} caractères !',
        maxMessage: 'Le max est de {{ limit }} caractères !'
    )]
    #[Assert\Regex(
        pattern: '/(^[0-9]{7}[A-Z]$)/',
        match: true,
        message: "Le matricule {{ value }} n'est pas un matricule valide."
    )]
    private ?string $matricule = null;

    public function __construct()
    {
        $this->agents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getEquipe(): ?Equipe
    {
        return $this->equipe;
    }

    public function setEquipe(?Equipe $equipe): static
    {
        $this->equipe = $equipe;

        return $this;
    }

    /**
     * @return Collection<int, Agent>
     */
    public function getAgents(): Collection
    {
        return $this->agents;
    }

    public function addAgent(Agent $agent): static
    {
        if (!$this->agents->contains($agent)) {
            $this->agents->add($agent);
            $agent->setRecenseur($this);
        }

        return $this;
    }

    public function removeAgent(Agent $agent): static
    {
        if ($this->agents->removeElement($agent)) {
            // set the owning side to null (unless already changed)
            if ($agent->getRecenseur() === $this) {
                $agent->setRecenseur(null);
            }
        }

        return $this;
    }

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(string $matricule): static
    {
        $this->matricule = $matricule;

        return $this;
    }

    public function __toString(): string
    {
        return $this->code;
    }
}
