<?php

namespace App\Controller;

use App\Entity\Historique;
use App\Entity\Utilisateur;
use App\Form\RegistrationFormType;
use App\Form\UpdatePasswordType;
use App\Form\UpdateProfilType;
use App\Repository\UtilisateurRepository;
use App\Service\PasswordGenerator;
use App\Service\Statistiques;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class RegistrationController extends AbstractController
{
    #[IsGranted("ROLE_ADMIN")]
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher,
                             EntityManagerInterface $entityManager, Statistiques $statistiques): Response
    {
        $userConnecte = $this->getUser();

        $user = new Utilisateur();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encodage du password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            // Pour forcer le user a modifié son password
            $user->setPasswordModified(false);

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
            'compteurUserJour' => $statistiques->getDailyCompteurUser($userConnecte),
            'compteurUser' => $statistiques->getCompteurUser($userConnecte),
            'totalActeJour' => $statistiques->getDailyCountActesNaissances(),
            'globalUserStats' => $statistiques->getUserStats('DESC'),
            'dailyUserStats' => $statistiques->getDailyUserStats('DESC'),
            'totalSaisie' => $statistiques->getCountActesNaissances(),
            'stats' => $statistiques->getStats(),
        ]);
    }

    #[IsGranted("ROLE_ADMIN")]
    #[Route('/users', name: 'app_users')]
    public function list(UtilisateurRepository $utilisateurRepository, Statistiques $statistiques): Response
    {
        $user = $this->getUser();
        $users = $utilisateurRepository->findAll();

        return $this->render('registration/list.html.twig',[
            'users' => $users,
            'compteurUserJour' => $statistiques->getDailyCompteurUser($user),
            'compteurUser' => $statistiques->getCompteurUser($user),
            'totalActeJour' => $statistiques->getDailyCountActesNaissances(),
            'globalUserStats' => $statistiques->getUserStats('DESC'),
            'dailyUserStats' => $statistiques->getDailyUserStats('DESC'),
            'totalSaisie' => $statistiques->getCountActesNaissances(),
            'stats' => $statistiques->getStats(),
        ]);
    }

    /* Desactiver un user */
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/user/delete/{id}', name: 'app_user_delete')]
    public function delete(EntityManagerInterface $manager, Request $request, Utilisateur $user): Response
    {
        // pour l'historique de l'action
        $history = new Historique();

        if($user->isEnableYN() === false){
            $user->setEnableYN(true);
            $history->setTypeAction("ACTIVER");
        } else {
            $user->setEnableYN(false);
            $history->setTypeAction("DESACTIVER");
        }

        $history->setAuteur($this->getUser()->getFullname())
            ->setNature("COMPTE_USER")
            ->setClef($user->getFullname())
            ->setDateAction(new \DateTimeImmutable())
        ;
        // Persistence de l'entité Organismes
        $manager->persist($user);
        $manager->persist($history);
        $manager->flush();

        // Alerte succès de la mise à jour des informations sur un organisme
        $this->addFlash("danger", "Utilisateur supprimé avec succès !");

        return $this->redirectToRoute('app_users');
    }

    // Modification du profil
    #[IsGranted('ROLE_USER')]
    #[Route('/user/{id}/edit', name: 'app_user_edit')]
    public function edit(EntityManagerInterface $manager, Request $request, Utilisateur $user,
        Statistiques $statistiques): Response
    {
        // pour l'historisation de l'action
        $history = new Historique();

        // constructeur de formulaire de saisie des actes de décès
        $form = $this->createForm(UpdateProfilType::class, $user);

        // handlerequest() permet de parcourir la requête et d'extraire les informations du formulaire
        $form->handleRequest($request);

        /**
         * Ayant extrait les infos saisies dans le formulaire,
         * on vérifie que le formulaire a été soumis et qu'il est valide
         */
        if($form->isSubmitted() && $form->isValid())
        {
            $history->setTypeAction("UPDATE")
                ->setAuteur($this->getUser()->getFullname())
                ->setNature("COMPTE_USER")
                ->setClef($form->get('fullname')->getData())
                ->setDateAction(new \DateTimeImmutable())
            ;
            // Persistence de l'entité Organismes
            $manager->persist($user);
            $manager->persist($history);
            $manager->flush();

            // Alerte succès de la mise à jour des informations sur un organisme
            $this->addFlash("warning", "Utilisateur modifié avec succès !");

            if ($this->getUser()->getFullname() !== $user->getFullname()) {
                return $this->redirectToRoute('app_users');
            }
            return $this->redirectToRoute('app_logout');
        }

        return $this->render('registration/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
            'compteurUserJour' => $statistiques->getDailyCompteurUser($user),
            'compteurUser' => $statistiques->getCompteurUser($user),
            'totalActeJour' => $statistiques->getDailyCountActesNaissances(),
            'globalUserStats' => $statistiques->getUserStats('DESC'),
            'dailyUserStats' => $statistiques->getDailyUserStats('DESC'),
            'totalSaisie' => $statistiques->getCountActesNaissances(),
        ]);
    }

    #[IsGranted('ROLE_USER')]
    //Mise à jour du mot de passe
    #[Route('/user/changepassword', name: 'app_user_password')]
    public function changePassword(UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $manager,
                                   Request $request, Statistiques $statistiques): Response
    {
        $user = $this->getUser();
        $history = new Historique();

        $form = $this->createForm(UpdatePasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $old_password = $form->get('old_password')->getData();

            // Si l'ancien mot de passe est le bon
            if($userPasswordHasher->isPasswordValid($user, $old_password))
            {
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );

                // Le user a changé son password
                $user->setPasswordModified(true);

                // On enregistre en BD l'action et celui qui l'a exécuté.
                $history->setTypeAction("UPDATE")
                    ->setAuteur($this->getUser()->getFullname())
                    ->setNature("PASSWORD")
                    ->setClef($form->get('fullname')->getData())
                    ->setDateAction(new \DateTimeImmutable())
                ;

                $manager->persist($user);
                $manager->persist($history);
                $manager->flush();

                // Notification du mot de passe modifié
                $this->addFlash("success", "Mot de passe modifié avec succès !!!");

                // Redirection vers la page de connexion
                return $this->redirectToRoute('app_logout');

            }else {
                // Notification du mot de passe modifié
                $this->addFlash("danger", "Votre ancien mot de passe n'est pas valide !!!");
            }
        }

        return $this->render('registration/change_pwd.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
            'compteurUserJour' => $statistiques->getDailyCompteurUser($user),
            'compteurUser' => $statistiques->getCompteurUser($user),
            'totalActeJour' => $statistiques->getDailyCountActesNaissances(),
            'globalUserStats' => $statistiques->getUserStats('DESC'),
            'dailyUserStats' => $statistiques->getDailyUserStats('DESC'),
            'totalSaisie' => $statistiques->getCountActesNaissances(),
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/user/reset-password/{id}', name: 'app_user_reset_password')]
    public function resetPassword(Utilisateur $user, UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $manager, PasswordGenerator $generator): Response
    {
        // Historisation
        $history = new Historique();

        // Génération du mot de passe
        $plainPassword = $generator->generate();

        // Réinitialisation du mot de passe
        $user->setPassword(
            $passwordHasher->hashPassword(
                $user,
                $plainPassword
            )
        );

        // L'utilisateur devra modifier son mot de passe
        $user->setPasswordModified(false);

        $history
            ->setTypeAction("RESET_PASSWORD")
            ->setAuteur($this->getUser()->getFullname())
            ->setNature("COMPTE_USER")
            ->setClef($user->getFullname())
            ->setDateAction(new \DateTimeImmutable());

        $manager->persist($user);
        $manager->persist($history);
        $manager->flush();

        $this->addFlash(
            'success',
            "Le mot de passe temporaire de {$user->getFullname()} est : <strong>{$plainPassword}</strong>"
        );

        return $this->redirectToRoute('app_users');
    }
}
