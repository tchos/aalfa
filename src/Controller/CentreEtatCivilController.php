<?php

namespace App\Controller;

use App\Entity\CentreEtatCivil;
use App\Entity\Historique;
use App\Form\CentreEtatCivilType;
use App\Repository\CentreEtatCivilRepository;
use App\Repository\EnfantRepository;
use App\Service\Statistiques;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[IsGranted("ROLE_ADMIN")]
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

    #[Route('/new', name: 'app_cec_new')]
    public function new(CentreEtatCivilRepository $cecRepository, Statistiques $statistiques,
            Request $request): Response
    {
        $centreEtatCivil = new CentreEtatCivil();
        $user = $this->getUser();
        //Pour historiser l'action du user
        $history = new Historique();

        $form = $this->createForm(CentreEtatCivilType::class, $centreEtatCivil);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $history->setTypeAction('CREATE')
                ->setAuteur($user->getFullname())
                ->setNature('CEC')
                ->setClef($form->get('code_cec')->getData())
                ->setDateAction(new \DateTimeImmutable('now'));
            ;

            $entityManager->persist($centreEtatCivil);
            $entityManager->flush();

            return $this->redirectToRoute('app_cec_index');
        }

        return $this->render('centre_etat_civil/new.html.twig', [
            'form' => $form->createView(),
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
