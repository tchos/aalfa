<?php

namespace App\Controller;

use App\Entity\Equipe;
use App\Entity\Historique;
use App\Form\EquipeType;
use App\Repository\EquipeRepository;
use App\Service\Statistiques;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/equipe')]
#[IsGranted('ROLE_ADMIN')]
class EquipeController extends AbstractController
{
    #[Route('/', name: 'app_equipe_list')]
    public function index(Statistiques $statistiques, EquipeRepository $equipeRepository): Response
    {
        //On recupère le user connecté
        $user = $this->getUser();

        return $this->render('equipe/index.html.twig', [
            'equipes' => $equipeRepository->findAll(),
            'compteurUserJour' => $statistiques->getDailyCompteurUser($user),
            'compteurUser' => $statistiques->getCompteurUser($user),
            'totalActeJour' => $statistiques->getDailyCountActesNaissances(),
            'globalUserStats' => $statistiques->getUserStats('DESC'),
            'dailyUserStats' => $statistiques->getDailyUserStats('DESC'),
        ]);
    }

    #[Route('/add', name: 'app_equipe_new')]
    public function create(Request $request, EntityManagerInterface $entityManager, Statistiques $statistiques): Response
    {
        //Capter le user connecté
        $user = $this->getUser();
        // Historiser les action du user connecté
        $history = new Historique();

        $equipe = new Equipe();
        $form = $this->createForm(EquipeType::class, $equipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $history->setTypeAction('CREATE')
                ->setAuteur($user->getFullname())
                ->setNature('EQUIPE')
                ->setClef($form->get('libelle')->getData())
                ->setDateAction(new \DateTimeImmutable('now'));
                ;

            $entityManager->persist($equipe);
            $entityManager->persist($history);
            $entityManager->flush();

            // Alerte succès de la création d'une équipe
            $flash = "<strong>Succès !!!</strong> Nouvelle équipe enregistrée !!!";
            $this->addFlash('success',$flash);

            return $this->redirectToRoute('app_equipe_new');
        }

        return $this->render('equipe/new.html.twig', [
            'form' => $form->createView(),
            'equipe' => $equipe,
            'compteurUserJour' => $statistiques->getDailyCompteurUser($user),
            'compteurUser' => $statistiques->getCompteurUser($user),
            'totalActeJour' => $statistiques->getDailyCountActesNaissances(),
            'globalUserStats' => $statistiques->getUserStats('DESC'),
            'dailyUserStats' => $statistiques->getDailyUserStats('DESC'),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_equipe_edit')]
    public function edit(Request $request, EntityManagerInterface $entityManager,
                         Statistiques $statistiques, Equipe $equipe): Response
    {
        //Capter le user connecté
        $user = $this->getUser();
        // Historiser les action du user connecté
        $history = new Historique();

        $form = $this->createForm(EquipeType::class, $equipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $history->setTypeAction('UPDATE')
                ->setAuteur($user->getFullname())
                ->setNature('EQUIPE')
                ->setClef($form->get('libelle')->getData())
                ->setDateAction(new \DateTimeImmutable('now'));
            ;

            $entityManager->persist($equipe);
            $entityManager->persist($history);
            $entityManager->flush();

            // Alerte succès de la création d'une équipe
            $flash = "<strong>Succès !!!</strong> Nouvelle équipe enregistrée !!!";
            $this->addFlash('success',$flash);

            return $this->redirectToRoute('app_equipe_list');
        }

        return $this->render('equipe/edit.html.twig', [
            'form' => $form->createView(),
            'equipe' => $equipe,
            'compteurUserJour' => $statistiques->getDailyCompteurUser($user),
            'compteurUser' => $statistiques->getCompteurUser($user),
            'totalActeJour' => $statistiques->getDailyCountActesNaissances(),
            'globalUserStats' => $statistiques->getUserStats('DESC'),
            'dailyUserStats' => $statistiques->getDailyUserStats('DESC'),
        ]);
    }

    #[Route('/{id}/delete', name: 'app_equipe_delete')]
    public function delete(Request $request, EntityManagerInterface $entityManager,
                         Statistiques $statistiques, Equipe $equipe): Response
    {
        //Capter le user connecté
        $user = $this->getUser();
        // Historiser les action du user connecté
        $history = new Historique();

        $history->setTypeAction('DELETE')
            ->setAuteur($user->getFullname())
            ->setNature('EQUIPE')
            ->setClef($equipe->getLibelle())
            ->setDateAction(new \DateTimeImmutable('now'));
        ;

        $entityManager->persist($history);
        $entityManager->remove($equipe);
        $entityManager->flush();

        // Alerte succès de la création d'une équipe
        $flash = "<strong>Succès !!!</strong> Equipe ".$equipe->getLibelle()." supprimée !!!";
        $this->addFlash('success',$flash);

        return $this->redirectToRoute('app_equipe_list');
    }
}
