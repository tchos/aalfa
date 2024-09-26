<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class Statistiques
{
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
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
}