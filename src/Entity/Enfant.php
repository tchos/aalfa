<?php

namespace App\Entity;

use App\Repository\EnfantRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: EnfantRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity(fields: ['matricule','nom_enfant'], message: 'Une personne ne peut avoir 2 enfants ayant le même nom !')]
class Enfant
{
    #[Assert\Callback]
    public function valider_age(ExecutionContextInterface $context, $payload): void
    {
        //si aucune date de naissance, on ne renvoie rien
        if(!$this->date_naissance)
            return;

        $today = new \DateTime();
        $age = $today->diff($this->date_naissance)->y;

        // Si plus de 21 ans et non handicapé, on rejette
        if($age >= 21 && !$this->handicapeYN){
            $context->buildViolation(
                "Impossible d'enregistrer un enfant déjà majeur"
            )->atPath('date_naissance')->addViolation();
        }
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'enfants')]
    private ?Agent $agent = null;

    #[ORM\Column(length: 8)]
    private ?string $matricule = null;

    #[ORM\Column]
    private ?int $rang = null;

    #[ORM\Column]
    private ?int $ordre = null;

    #[ORM\Column(length: 64)]
    private ?string $nom_enfant = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_naissance = null;

    #[ORM\Column(length: 128, nullable: true)]
    private ?string $cec = null;

    #[ORM\Column(length: 32, nullable: true)]
    #[Assert\Length(
        min: 4,
        minMessage: "Le minimum est de {{ limit }} caractères !"
    )]
    private ?string $numero_acte = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_acte_naissance = null;

    #[ORM\Column(length: 64, nullable: true)]
    #[Assert\Length(
        min: 3,
        minMessage: "Le minimum est de {{ limit }} caractères !"
    )]
    private ?string $nom_conjoint = null;

    #[ORM\Column]
    private ?bool $enfant_reconnu_y_n = null;

    #[ORM\ManyToOne(inversedBy: 'enfants_saisis')]
    private ?Utilisateur $agent_saisie = null;

    #[ORM\Column(nullable: true)]
    private ?int $region_cec = null;

    #[ORM\Column(length: 6, nullable: true)]
    private ?string $codeArrondissement = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?bool $handicapeYN = false;

    /**
     * CallBack appelé à chaque fois que l'on veut enregistrer un enfant pour
     * prendre automatiquement la date de saisie des infos sur l'enfant .
     */
    #[ORM\PrePersist]
    public function PrePersist()
    {
        if (empty($this->createdAt)) {
            $this->createdAt = new \DateTimeImmutable();
        }
    }

    /**
     * CallBack appelé à chaque fois que l'on veut mettre à jour un enfant pour
     * prendre automatiquement la date de saisie des infos sur l'enfant .
     */
    #[ORM\PreUpdate]
    public function  PreUpdate()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAgent(): ?Agent
    {
        return $this->agent;
    }

    public function setAgent(?Agent $agent): static
    {
        $this->agent = $agent;

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

    public function getRang(): ?int
    {
        return $this->rang;
    }

    public function setRang(int $rang): static
    {
        $this->rang = $rang;

        return $this;
    }

    public function getOrdre(): ?int
    {
        return $this->ordre;
    }

    public function setOrdre(int $ordre): static
    {
        $this->ordre = $ordre;

        return $this;
    }

    public function getNomEnfant(): ?string
    {
        return $this->nom_enfant;
    }

    public function setNomEnfant(string $nom_enfant): static
    {
        $this->nom_enfant = $nom_enfant;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->date_naissance;
    }

    public function setDateNaissance(?\DateTimeInterface $date_naissance): static
    {
        $this->date_naissance = $date_naissance;

        return $this;
    }

    public function getCec(): ?string
    {
        return $this->cec;
    }

    public function setCec(?string $cec): static
    {
        $this->cec = $cec;

        return $this;
    }

    public function getNumeroActe(): ?string
    {
        return $this->numero_acte;
    }

    public function setNumeroActe(?string $numero_acte): static
    {
        $this->numero_acte = $numero_acte;

        return $this;
    }

    public function getDateActeNaissance(): ?\DateTimeInterface
    {
        return $this->date_acte_naissance;
    }

    public function setDateActeNaissance(?\DateTimeInterface $date_acte_naissance): static
    {
        $this->date_acte_naissance = $date_acte_naissance;

        return $this;
    }

    public function getNomConjoint(): ?string
    {
        return $this->nom_conjoint;
    }

    public function setNomConjoint(?string $nom_conjoint): static
    {
        $this->nom_conjoint = $nom_conjoint;

        return $this;
    }

    public function isEnfantReconnuYN(): ?bool
    {
        return $this->enfant_reconnu_y_n;
    }

    public function setEnfantReconnuYN(bool $enfant_reconnu_y_n): static
    {
        $this->enfant_reconnu_y_n = $enfant_reconnu_y_n;

        return $this;
    }

    public function getAgentSaisie(): ?Utilisateur
    {
        return $this->agent_saisie;
    }

    public function setAgentSaisie(?Utilisateur $agent_saisie): static
    {
        $this->agent_saisie = $agent_saisie;

        return $this;
    }

    public function getRegionCec(): ?int
    {
        return $this->region_cec;
    }

    public function setRegionCec(?int $region_cec): static
    {
        $this->region_cec = $region_cec;

        return $this;
    }

    public function getCodeArrondissement(): ?string
    {
        return $this->codeArrondissement;
    }

    public function setCodeArrondissement(?string $codeArrondissement): static
    {
        $this->codeArrondissement = $codeArrondissement;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function isHandicapeYN(): ?bool
    {
        return $this->handicapeYN;
    }

    public function setHandicapeYN(bool $handicapeYN): static
    {
        $this->handicapeYN = $handicapeYN;

        return $this;
    }

}
