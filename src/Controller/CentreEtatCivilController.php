<?php

namespace App\Controller;

use App\Entity\CentreEtatCivil;
use App\Repository\CentreEtatCivilRepository;
use App\Repository\EnfantRepository;
use App\Service\Statistiques;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[Route("cec")]
class CentreEtatCivilController extends AbstractController
{
    #[Route('/', name: 'app_cec_index', methods: ['GET'])]
    public function index(CentreEtatCivilRepository $cecRepository, Statistiques $statistiques): Response
    {
        $user = $this->getUser();
        return $this->render('centre_etat_civil/index.html.twig', [
            'cecs' => $cecRepository->findAll(),
            'compteurUserJour' => $statistiques->getDailyCompteurUser($user),
            'compteurUser' => $statistiques->getCompteurUser($user),
            'totalActeJour' => $statistiques->getDailyCountActesNaissances(),
            'globalUserStats' => $statistiques->getUserStats('DESC'),
            'dailyUserStats' => $statistiques->getDailyUserStats('DESC'),
            'totalSaisie' => $statistiques->getCountActesNaissances(),
            'cecStats' => $statistiques->getNbActesByCec('DESC'),
            'stats' => $statistiques->getStats(),
            'recenseurStats' => $statistiques->getRecenseurStats('DESC'),
        ]);
    }

    #[Route('/{id}/enfants', name: 'app_cec_enfants', methods: ['GET'])]
    public function listEnfants(EnfantRepository $enfantRepository, Statistiques $statistiques,
            CentreEtatCivil $cec): Response
    {
        $user = $this->getUser();
        $enfants = $enfantRepository->findByCecOrderByMatricule($cec);
        //dd($enfants);

        return $this->render('centre_etat_civil/enfants.html.twig', [
            'cec' => $cec,
            'enfants' => $enfants,
            'compteurUserJour' => $statistiques->getDailyCompteurUser($user),
            'compteurUser' => $statistiques->getCompteurUser($user),
            'totalActeJour' => $statistiques->getDailyCountActesNaissances(),
            'globalUserStats' => $statistiques->getUserStats('DESC'),
            'dailyUserStats' => $statistiques->getDailyUserStats('DESC'),
            'totalSaisie' => $statistiques->getCountActesNaissances(),
            'cecStats' => $statistiques->getNbActesByCec('DESC'),
            'stats' => $statistiques->getStats(),
            'recenseurStats' => $statistiques->getRecenseurStats('DESC'),
        ]);
    }
}
