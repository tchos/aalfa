<?php

namespace App\Controller;

use App\Entity\Equipe;
use App\Entity\Historique;
use App\Entity\Recenseur;
use App\Form\RecenseurType;
use App\Repository\RecenseurRepository;
use App\Service\Statistiques;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/recenseur')]
#[IsGranted('ROLE_ADMIN')]
class RecenseurController extends AbstractController
{
    #[Route('/', name: 'app_recenseur_list')]
    public function index(Statistiques $statistiques, RecenseurRepository $recenseurRepository): Response
    {
        //On recupère le user connecté
        $user = $this->getUser();

        return $this->render('recenseur/index.html.twig', [
            'recenseurs' => $recenseurRepository->findAll(),
            'compteurUserJour' => $statistiques->getDailyCompteurUser($user),
            'compteurUser' => $statistiques->getCompteurUser($user),
            'totalActeJour' => $statistiques->getDailyCountActesNaissances(),
            'globalUserStats' => $statistiques->getUserStats('DESC'),
            'dailyUserStats' => $statistiques->getDailyUserStats('DESC'),
        ]);
    }

    #[Route('/add', name: 'app_recenseur_new')]
    public function create(Request $request, EntityManagerInterface $entityManager,
            Statistiques $statistiques): Response
    {
        //User connecté
        $user = $this->getUser();
        //Pour historiser l'action effectuée par le user connecté
        $history = new Historique();
        //Le recenseur à créer
        $recenseur = new Recenseur();

        $form = $this->createForm(RecenseurType::class, $recenseur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $history->setTypeAction('CREATE')
                ->setAuteur($user->getFullname())
                ->setNature('RECENSEUR')
                ->setClef($form->get('nom')->getData())
                ->setDateAction(new \DateTimeImmutable('now'));
            ;

            $entityManager->persist($recenseur);
            $entityManager->persist($history);
            $entityManager->flush();

            // Alerte succès de la création d'une équipe
            $flash = "<strong>Succès !!!</strong> Nouvel agent de collecte enregistrée !!!";
            $this->addFlash('success',$flash);

            return $this->redirectToRoute('app_recenseur_new');
        }

        return $this->render('recenseur/new.html.twig', [
            'form' => $form->createView(),
            'recenseur' => $recenseur,
            'compteurUserJour' => $statistiques->getDailyCompteurUser($user),
            'compteurUser' => $statistiques->getCompteurUser($user),
            'totalActeJour' => $statistiques->getDailyCountActesNaissances(),
            'globalUserStats' => $statistiques->getUserStats('DESC'),
            'dailyUserStats' => $statistiques->getDailyUserStats('DESC'),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_recenseur_edit')]
    public function edit(Request $request, EntityManagerInterface $entityManager,
                           Statistiques $statistiques, Recenseur $recenseur): Response
    {
        //User connecté
        $user = $this->getUser();
        //Pour historiser l'action effectuée par le user connecté
        $history = new Historique();

        $form = $this->createForm(RecenseurType::class, $recenseur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $history->setTypeAction('UPDATE')
                ->setAuteur($user->getFullname())
                ->setNature('RECENSEUR')
                ->setClef($form->get('nom')->getData())
                ->setDateAction(new \DateTimeImmutable('now'));
            ;

            $entityManager->persist($recenseur);
            $entityManager->persist($history);
            $entityManager->flush();

            // Alerte succès de la création d'une équipe
            $flash = "<strong>Succès !!!</strong> Agent de collecte mis à jour !!!";
            $this->addFlash('success',$flash);

            return $this->redirectToRoute('app_recenseur_new');
        }

        return $this->render('recenseur/new.html.twig', [
            'form' => $form->createView(),
            'recenseur' => $recenseur,
            'compteurUserJour' => $statistiques->getDailyCompteurUser($user),
            'compteurUser' => $statistiques->getCompteurUser($user),
            'totalActeJour' => $statistiques->getDailyCountActesNaissances(),
            'globalUserStats' => $statistiques->getUserStats('DESC'),
            'dailyUserStats' => $statistiques->getDailyUserStats('DESC'),
        ]);
    }

    #[Route('/{id}/delete', name: 'app_recenseur_delete')]
    public function delete(Request $request, EntityManagerInterface $entityManager,
                           Statistiques $statistiques, Recenseur $recenseur): Response
    {
        //Capter le user connecté
        $user = $this->getUser();
        // Historiser les action du user connecté
        $history = new Historique();

        $history->setTypeAction('DELETE')
            ->setAuteur($user->getFullname())
            ->setNature('RECENSEUR')
            ->setClef($form->get('libelle')->getData())
            ->setDateAction(new \DateTimeImmutable('now'));
        ;

        $entityManager->persist($history);
        $entityManager->remove($recenseur);
        $entityManager->flush();

        // Alerte succès de la création d'une équipe
        $flash = "<strong>Succès !!!</strong> Agent de collecte ".$recenseur->getNom()." supprimé !!!";
        $this->addFlash('success',$flash);

        return $this->redirectToRoute('app_recenseur_list');
    }
}
