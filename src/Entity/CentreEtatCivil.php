<?php

namespace App\Entity;

use App\Repository\CentreEtatCivilRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CentreEtatCivilRepository::class)]
class CentreEtatCivil
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 6)]
    private ?string $codeCec = null;

    #[ORM\Column(length: 64)]
    private ?string $libelleCec = null;

    #[ORM\Column(length: 64)]
    private ?string $arrondissement = null;

    #[ORM\Column(length: 64)]
    private ?string $departement = null;

    #[ORM\Column(length: 32)]
    private ?string $region = null;

    /**
     * @var Collection<int, Enfant>
     */
    #[ORM\OneToMany(targetEntity: Enfant::class, mappedBy: 'centreEtatCivil')]
    private Collection $enfants;

    public function __construct()
    {
        $this->enfants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeCec(): ?string
    {
        return $this->codeCec;
    }

    public function setCodeCec(string $codeCec): static
    {
        $this->codeCec = $codeCec;

        return $this;
    }

    public function getLibelleCec(): ?string
    {
        return $this->libelleCec;
    }

    public function setLibelleCec(string $libelleCec): static
    {
        $this->libelleCec = $libelleCec;

        return $this;
    }

    public function getArrondissement(): ?string
    {
        return $this->arrondissement;
    }

    public function setArrondissement(string $arrondissement): static
    {
        $this->arrondissement = $arrondissement;

        return $this;
    }

    public function getDepartement(): ?string
    {
        return $this->departement;
    }

    public function setDepartement(string $departement): static
    {
        $this->departement = $departement;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(string $region): static
    {
        $this->region = $region;

        return $this;
    }

    /**
     * @return Collection<int, Enfant>
     */
    public function getEnfants(): Collection
    {
        return $this->enfants;
    }

    public function addEnfant(Enfant $enfant): static
    {
        if (!$this->enfants->contains($enfant)) {
            $this->enfants->add($enfant);
            $enfant->setCentreEtatCivil($this);
        }

        return $this;
    }

    public function removeEnfant(Enfant $enfant): static
    {
        if ($this->enfants->removeElement($enfant)) {
            // set the owning side to null (unless already changed)
            if ($enfant->getCentreEtatCivil() === $this) {
                $enfant->setCentreEtatCivil(null);
            }
        }

        return $this;
    }

    public function __toString(): string {  return $this->codeCec ?? ''; }
}
