<?php

namespace App\Service;

use App\Entity\Agent;
use Doctrine\ORM\EntityManagerInterface;

class GestionSaisieAgentService
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    /**
     * Recalcule automatiquement l'état de la saisie d'un agent.
     */
    public function mettreAJourEtatSaisie(Agent $agent): void
    {
        $nbRenseignes = $agent->getNbEnfantsRenseignes();

        if (
            $agent->getNbEnftCollecte() !== null
            && $nbRenseignes >= $agent->getNbEnftCollecte()
        ) {
            $agent->setSaisieTerminee(true);
            $agent->setDateValidation(new \DateTime());
        } else {
            $agent->setSaisieTerminee(false);
            $agent->setDateValidation(null);
        }

        $this->entityManager->persist($agent);
    }
}