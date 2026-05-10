<?php

namespace App\Entity;

use App\Repository\EquipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EquipeRepository::class)]
#[UniqueEntity(fields: 'code', message: 'Cette équipe existe déjà !!!')]
class Equipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 32)]
    private ?string $libelle = null;

    #[ORM\Column(length: 5, unique: true)]
    private ?string $code = null;

    #[ORM\Column(length: 64)]
    private ?string $chef = null;

    /**
     * @var Collection<int, Recenseur>
     */
    #[ORM\OneToMany(targetEntity: Recenseur::class, mappedBy: 'equipe')]
    private Collection $recenseurs;

    #[ORM\Column(length: 64)]
    private ?string $coordonnateur = null;

    public function __construct()
    {
        $this->recenseurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

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

    public function getChef(): ?string
    {
        return $this->chef;
    }

    public function setChef(string $chef): static
    {
        $this->chef = $chef;

        return $this;
    }

    /**
     * @return Collection<int, Recenseur>
     */
    public function getRecenseurs(): Collection
    {
        return $this->recenseurs;
    }

    public function addRecenseur(Recenseur $recenseur): static
    {
        if (!$this->recenseurs->contains($recenseur)) {
            $this->recenseurs->add($recenseur);
            $recenseur->setEquipe($this);
        }

        return $this;
    }

    public function removeRecenseur(Recenseur $recenseur): static
    {
        if ($this->recenseurs->removeElement($recenseur)) {
            // set the owning side to null (unless already changed)
            if ($recenseur->getEquipe() === $this) {
                $recenseur->setEquipe(null);
            }
        }

        return $this;
    }

    public function getCoordonnateur(): ?string
    {
        return $this->coordonnateur;
    }

    public function setCoordonnateur(string $coordonnateur): static
    {
        $this->coordonnateur = $coordonnateur;

        return $this;
    }

    public function __toString(): string
    {
        return $this->code.' - '.$this->libelle;
    }
}
