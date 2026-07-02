<?php

namespace App\Controller;

use App\Service\Statistiques;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class StatistiqueController extends AbstractController
{
    #[Route('/dailystats', name: 'app_statistique_daily')]
    public function dailyStats(Statistiques $statistiques): Response
    {
        $user = $this->getUser();

        return $this->render('statistique/dailystats.html.twig', [
            'compteurUserJour' => $statistiques->getDailyCompteurUser($user),
            'compteurUser' => $statistiques->getCompteurUser($user),
            'totalActeJour' => $statistiques->getDailyCountActesNaissances(),
            'globalUserStats' => $statistiques->getUserStats('DESC'),
            'dailyUserStats' => $statistiques->getDailyUserStats('DESC'),
            'totalSaisie' => $statistiques->getCountActesNaissances(),
            'stats' => $statistiques->getStats(),
        ]);
    }

    #[Route('/globalstats', name: 'app_statistique_global')]
    public function globalStats(Statistiques $statistiques): Response
    {
        $user = $this->getUser();

        return $this->render('statistique/globalstats.html.twig', [
            'compteurUserJour' => $statistiques->getDailyCompteurUser($user),
            'compteurUser' => $statistiques->getCompteurUser($user),
            'totalActeJour' => $statistiques->getDailyCountActesNaissances(),
            'globalUserStats' => $statistiques->getUserStats('DESC'),
            'dailyUserStats' => $statistiques->getDailyUserStats('DESC'),
            'totalSaisie' => $statistiques->getCountActesNaissances(),
            'stats' => $statistiques->getStats(),
        ]);
    }

    #[Route('/teamstats', name: 'app_statistique_team')]
    public function teamStats(Statistiques $statistiques): Response
    {
        $user = $this->getUser();

        return $this->render('statistique/teamstats.html.twig', [
            'compteurUserJour' => $statistiques->getDailyCompteurUser($user),
            'compteurUser' => $statistiques->getCompteurUser($user),
            'totalActeJour' => $statistiques->getDailyCountActesNaissances(),
            'globalUserStats' => $statistiques->getUserStats('DESC'),
            'dailyUserStats' => $statistiques->getDailyUserStats('DESC'),
            'totalSaisie' => $statistiques->getCountActesNaissances(),
            'stats' => $statistiques->getStats(),
            'teamStats' => $statistiques->getTeamStats('DESC'),
        ]);
    }

    #[Route('/recenseurstats', name: 'app_statistique_recenseur')]
    public function recenseurStats(Statistiques $statistiques): Response
    {
        $user = $this->getUser();

        return $this->render('statistique/recenseurstats.html.twig', [
            'compteurUserJour' => $statistiques->getDailyCompteurUser($user),
            'compteurUser' => $statistiques->getCompteurUser($user),
            'totalActeJour' => $statistiques->getDailyCountActesNaissances(),
            'globalUserStats' => $statistiques->getUserStats('DESC'),
            'dailyUserStats' => $statistiques->getDailyUserStats('DESC'),
            'totalSaisie' => $statistiques->getCountActesNaissances(),
            'stats' => $statistiques->getStats(),
            'recenseurStats' => $statistiques->getRecenseurStats('DESC'),
        ]);
    }

    #[Route('/nbactesbycec', name: 'app_statistique_cec')]
    public function cecStats(Statistiques $statistiques): Response
    {
        $user = $this->getUser();

        return $this->render('statistique/cec.html.twig', [
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

    #[Route('/minstats', name: 'app_statistique_ministere')]
    public function minStats(Statistiques $statistiques): Response
    {
        $user = $this->getUser();

        return $this->render('statistique/minstats.html.twig', [
            'compteurUserJour' => $statistiques->getDailyCompteurUser($user),
            'compteurUser' => $statistiques->getCompteurUser($user),
            'totalActeJour' => $statistiques->getDailyCountActesNaissances(),
            'globalUserStats' => $statistiques->getUserStats('DESC'),
            'dailyUserStats' => $statistiques->getDailyUserStats('DESC'),
            'totalSaisie' => $statistiques->getCountActesNaissances(),
            'minstats' => $statistiques->getMinistereStats('DESC'),
            'stats' => $statistiques->getStats(),
            'recenseurStats' => $statistiques->getRecenseurStats('DESC'),
        ]);
    }
}
