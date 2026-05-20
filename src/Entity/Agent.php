<?php

namespace App\Entity;

use App\Repository\AgentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AgentRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity(
    fields: ['matricule'],
    message: 'Il existe déjà un autre agent avec ce matricule'
)]
class Agent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 8)]
    #[Assert\Length(
        min: 8,
        max: 8,
        minMessage: 'Le minimum est de {{ limit }} caractères !',
        maxMessage: 'Le max est de {{ limit }} caractères !'
    )]
    #[Assert\Regex(
        pattern: '/(^[A-Z][0-9]{7}$)|(^[0-9]{7}[A-Z]$)/',
        match: true,
        message: "Le matricule {{ value }} n'est pas un matricule valide."
    )]
    private ?string $matricule = null;

    #[ORM\Column(length: 64)]
    private ?string $nomAgt = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateNaisAgt = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Assert\GreaterThan(propertyPath:"dateNaisAgt")]
    private ?\DateTimeInterface $dateEmbAgt = null;

    #[ORM\Column]
    private ?int $nb_enft_paye = null;

    /**
     * @var Collection<int, Enfant>
     */
    #[ORM\OneToMany(targetEntity: Enfant::class, mappedBy: 'agent')]
    private Collection $enfants;

    #[ORM\Column(length: 32, nullable: true)]
    private ?string $telephone = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createAt = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Assert\Date(message: "Mauvais format de la date de collecte !")]
    #[Assert\GreaterThan("2026-06-01", message: "La collecte ne peut être antérieure à juin 2026 !")]
    #[Assert\LessThan('today', message: "La collecte doit être antérieure à ce jour !")]
    private ?\DateTimeInterface $date_collecte = null;

    #[ORM\ManyToOne(inversedBy: 'agents')]
    private ?Recenseur $recenseur = null;

    /**
     * CallBack appelé à chaque fois que l'on veut enregistrer un agent pour
     * prendre automatiquement la date de saisie des infos sur l'agent .
     */
    #[ORM\PrePersist]
    public function PrePersist()
    {
        if (empty($this->createdAt)) {
            $this->createdAt = new \DateTimeImmutable();
        }
    }

    /**
     * CallBack appelé à chaque fois que l'on veut mettre à jour un agent pour
     * prendre automatiquement la date de saisie des infos sur l'agent .
     */
    #[ORM\PreUpdate]
    public function  PreUpdate()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function __construct()
    {
        $this->enfants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNomAgt(): ?string
    {
        return $this->nomAgt;
    }

    public function setNomAgt(string $nomAgt): static
    {
        $this->nomAgt = $nomAgt;
        return $this;
    }

    public function getDateNaisAgt(): ?\DateTimeInterface
    {
        return $this->dateNaisAgt;
    }

    public function setDateNaisAgt(?\DateTimeInterface $dateNaisAgt): static
    {
        $this->dateNaisAgt = $dateNaisAgt;
        return $this;
    }

    public function getDateEmbAgt(): ?\DateTimeInterface
    {
        return $this->dateEmbAgt;
    }

    public function setDateEmbAgt(?\DateTimeInterface $dateEmbAgt): static
    {
        $this->dateEmbAgt = $dateEmbAgt;
        return $this;
    }

    public function getNbEnftPaye(): ?int
    {
        return $this->nb_enft_paye;
    }

    public function setNbEnftPaye(int $nb_enft_paye): static
    {
        $this->nb_enft_paye = $nb_enft_paye;
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
            $enfant->setAgent($this);
        }

        return $this;
    }

    public function removeEnfant(Enfant $enfant): static
    {
        if ($this->enfants->removeElement($enfant)) {
            // set the owning side to null (unless already changed)
            if ($enfant->getAgent() === $this) {
                $enfant->setAgent(null);
            }
        }

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): static
    {
        $this->telephone = $telephone;
        return $this;
    }

    public function getCreateAt(): ?\DateTimeImmutable
    {
        return $this->createAt;
    }

    public function setCreateAt(?\DateTimeImmutable $createAt): static
    {
        $this->createAt = $createAt;
        return $this;
    }

    public function getDateCollecte(): ?\DateTimeInterface
    {
        return $this->date_collecte;
    }

    public function setDateCollecte(?\DateTimeInterface $date_collecte): static
    {
        $this->date_collecte = $date_collecte;
        return $this;
    }

    public function getRecenseur(): ?Recenseur
    {
        return $this->recenseur;
    }

    public function setRecenseur(?Recenseur $recenseur): static
    {
        $this->recenseur = $recenseur;
        return $this;
    }

    public function __toString(): string {  return $this->nomAgt; }

}
