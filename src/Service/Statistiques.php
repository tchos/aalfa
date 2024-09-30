<?php

namespace App\Service;

use App\Entity\Agent;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;

class Statistiques
{
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function getStats()
    {
        $nbUsers = $this->getUserCount();
        $nbEquipes = $this->getEquipeCount();
        $nbActeNaissance = $this->getCountActesNaissances();
        $nbAgentRecense = $this->getAgentRecenseCount();

        return compact('nbUsers', 'nbEquipes', 'nbActeNaissance', 'nbAgentRecense');
    }

    /**
     * Retourne les statistiques de saisies de l'agent de saisie du jour
     * sur les actes de naissance.
     *
     * @return int
     */
    public function getDailyCompteurUser($user)
    {
        return $this->manager->createQuery(
            "SELECT COUNT(e.numero_acte) as compteurDuJour
            FROM App\Entity\Enfant e
            JOIN e.agent_saisie u
            WHERE CURRENT_DATE() <= e.createdAt AND u = :user AND e.numero_acte != ''"
        )
            ->setParameter('user', $user)
            ->getSingleScalarResult();
    }

    /**
     * Retourne les statistiques globales de saisies de l'agent de saisie
     * sur les actes de naissance.
     *
     * @return int
     */
    public function getCompteurUser($user)
    {
        return $this->manager->createQuery(
            "SELECT COUNT(e.numero_acte) as compteurUser
            FROM App\Entity\Enfant e
            JOIN e.agent_saisie u
            WHERE u = :user AND e.numero_acte != ''"
        )
            ->setParameter('user', $user)
            ->getSingleScalarResult();
    }

    /**
     * Nombre d'acte de naissances saisis.
     *
     * @return int
     */
    public function getCountActesNaissances()
    {
        return $this->manager->createQuery(
            "SELECT COUNT(e.numero_acte) AS nb_acte_nais
             FROM App\Entity\Enfant e
             WHERE e.numero_acte != ''"
        )
            ->getSingleScalarResult();
    }

    /**
     * Nombre d'acte de naissances saisis sur la journee.
     *
     * @return int
     */
    public function getDailyCountActesNaissances()
    {
        return $this->manager->createQuery(
            "SELECT COUNT(e.numero_acte) AS nb_acte_nais
             FROM App\Entity\Enfant e
             WHERE e.numero_acte != '' AND CURRENT_DATE() <= e.createdAt"
        )
            ->getSingleScalarResult();
    }

    /**
     * Retourne les statistiques de saisies par agents de saisie.
     *
     * @return Utilisateur
     */
    public function getUserStats($direction)
    {
        return $this->manager->createQuery(
            "SELECT u.fullname AS fullname, COUNT(DISTINCT e.numero_acte) AS nb_enfant
            FROM App\Entity\Utilisateur u
            JOIN u.enfants_saisis e
            WHERE e.numero_acte != ''
            GROUP BY fullname
            ORDER BY nb_enfant ".$direction
        )
            ->getResult();
    }

    /**
     * Retourne les statistiques journalières de saisies par agents de saisie .
     *
     * @return Utilisateur
     */
    public function getDailyUserStats($direction)
    {
        return $this->manager->createQuery(
            "SELECT u.fullname AS fullname, COUNT(DISTINCT e.numero_acte) AS nb_enfant
            FROM App\Entity\Utilisateur u
            JOIN u.enfants_saisis e
            WHERE e.numero_acte != '' AND CURRENT_DATE() <= e.createdAt
            GROUP BY fullname
            ORDER BY nb_enfant ".$direction
        )
            ->getResult();
    }

    /**
     * Retourne les statistiques globales de saisies par équipe de mission .
     *
     * @return Agent
     */
    public function getTeamStats($direction)
    {
        return $this->manager->createQuery(
            "SELECT a.equipe AS equipe, COUNT(DISTINCT e.numero_acte) AS nb_enfant
            FROM App\Entity\Agent a
            JOIN a.enfants e
            WHERE e.numero_acte != ''
            GROUP BY equipe
            ORDER BY nb_enfant ".$direction
        )
            ->getResult();
    }

    /**
     * Nombres de users inscrits
     *
     * @return int
     */
    public function getUserCount()
    {
        return $this->manager->createQuery("SELECT COUNT(u) FROM App\Entity\Utilisateur u")->getSingleScalarResult();
    }

    /**
     * Nombres d'équipes inscrites
     *
     * @return int
     */
    public function getEquipeCount()
    {
        return $this->manager->createQuery(
            "SELECT COUNT(DISTINCT a.equipe) AS nb_equipe 
                    FROM App\Entity\Agent a
                    WHERE a.equipe != ''"
        )
            ->getSingleScalarResult();
    }

    /**
     * Nombres d'agents recenses
     *
     * @return int
     */
    public function getAgentRecenseCount()
    {
        return $this->manager->createQuery(
            "SELECT COUNT(DISTINCT a.matricule) AS nb_agent_recense 
                    FROM App\Entity\Agent a
                    WHERE a.telephone != ''"
        )
            ->getSingleScalarResult();
    }
}