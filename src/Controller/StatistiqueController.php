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
            'stats' => $statistiques->getStats(),
            'dailyUserStats' => $statistiques->getDailyUserStats('DESC'),
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
            'stats' => $statistiques->getStats(),
            'dailyUserStats' => $statistiques->getDailyUserStats('DESC'),
        ]);
    }
}
