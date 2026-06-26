<?php

namespace App\Controller;

use App\Entity\CentreEtatCivil;
use App\Entity\Enfant;
use App\Entity\Historique;
use App\Form\EnfantDetailsType;
use App\Form\EnfantType;
use App\Repository\EnfantRepository;
use App\Service\GestionSaisieAgentService;
use App\Service\Statistiques;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\FormError;

#[Route('/enfant')]
class EnfantController extends AbstractController
{
    #[Route('/', name: 'app_enfant_index', methods: ['GET'])]
    public function index(EnfantRepository $enfantRepository): Response
    {
        return $this->render('enfant/index.html.twig', [
            'enfants' => $enfantRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_enfant_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $enfant = new Enfant();
        $form = $this->createForm(EnfantType::class, $enfant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($enfant);
            $entityManager->flush();

            return $this->redirectToRoute('app_enfant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('enfant/new.html.twig', [
            'enfant' => $enfant,
            'form' => $form->createView(),
            'hasErrors' => $form->isSubmitted() && !$form->isValid(),
        ]);
    }

    #[Route('/{id}', name: 'app_enfant_show', methods: ['GET'])]
    public function show(Enfant $enfant, Statistiques $statistiques): Response
    {
        //User connecté
        $user = $this->getUser();

        return $this->render('enfant/details.html.twig', [
            'enfant' => $enfant,
            'idAgent' => $enfant->getAgent()->getId(),
            'agtCollecte' => $enfant->getAgent()->getRecenseur()->getNom(),
            'matriculeAgent' => $enfant->getAgent()->getMatricule(),
            'compteurUserJour' => $statistiques->getDailyCompteurUser($user),
            'compteurUser' => $statistiques->getCompteurUser($user),
            'totalActeJour' => $statistiques->getDailyCountActesNaissances(),
            'globalUserStats' => $statistiques->getUserStats('DESC'),
            'dailyUserStats' => $statistiques->getDailyUserStats('DESC'),
            'totalSaisie' => $statistiques->getCountActesNaissances(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_enfant_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Enfant $enfant, EntityManagerInterface $entityManager,
        Statistiques $statistiques, GestionSaisieAgentService $gestionSaisie): Response
    {
        $agent = $enfant->getAgent();
        $historique = new Historique();
        $form = $this->createForm(EnfantType::class, $enfant);
        $form->handleRequest($request);

        //user connecté
        $user = $this->getUser();

        if ($agent->isSaisieTerminee()) {
            throw $this->createAccessDeniedException(
                'La saisie de cet agent est déjà terminée.'
            );

            $this->addFlash(
                'success',
                'Les '.$agent->getNbEnftCollecte().
                ' enfants ont été renseignés. La saisie a été automatiquement clôturée.'
            );
        }
        else if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer les infos saisi dans le formulaire
            $data = $form->getData();
            $leCec = $form->get('code_cec')->getData();
            $codeCec = explode('-', $leCec)[0];

            $cec = $entityManager
                ->getRepository(CentreEtatCivil::class)
                ->findOneBy([
                    'codeCec' => $codeCec
                ]);

            // on vérifie si le cec saisie existe en BD
            if (!$cec) {
                $form->get('code_cec')
                    ->addError(new FormError("Ce code CEC n'existe pas."));
            } else {
                $enfant->setCentreEtatCivil($cec);
                $enfant->setEnfantReconnuYN(true);
                $enfant->setAgentSaisie($this->getUser());
                // Pour verrouiller automatiquement la saisie dès qu'on a saisi le nobre d'enfants collectés.
                $gestionSaisie->mettreAJourEtatSaisie($agent);

                $historique->setTypeAction('UPDATE')
                    ->setAuteur($user->getFullname())
                    ->setNature('ENFANT')
                    ->setClef($enfant->getMatricule() .'-'.$form->get('nom_enfant')->getData())
                    ->setDateAction(new \DateTimeImmutable('now'));
                ;

                // Persistence des données dans la BD.
                $entityManager->persist($historique);
                $entityManager->persist($enfant);
                $entityManager->flush();

                return $this->redirectToRoute(
                    'app_agent_show',['id' => $enfant->getAgent()->getId(),],
                    Response::HTTP_SEE_OTHER
                );
            }
        }

        return $this->render('enfant/update.html.twig', [
            'enfant' => $enfant,
            'form' => $form->createView(),
            'idAgent' => $enfant->getAgent()->getId(),
            'matriculeAgent' => $enfant->getAgent()->getMatricule(),
            'compteurUserJour' => $statistiques->getDailyCompteurUser($user),
            'compteurUser' => $statistiques->getCompteurUser($user),
            'totalActeJour' => $statistiques->getDailyCountActesNaissances(),
            'globalUserStats' => $statistiques->getUserStats('DESC'),
            'dailyUserStats' => $statistiques->getDailyUserStats('DESC'),
            'totalSaisie' => $statistiques->getCountActesNaissances(),
        ]);
    }

    #[Route('/{id}', name: 'app_enfant_delete', methods: ['POST'])]
    public function delete(Request $request, Enfant $enfant, EntityManagerInterface $entityManager,
        GestionSaisieAgentService $gestionSaisie): Response
    {
        if ($this->isCsrfTokenValid('delete'.$enfant->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($enfant);
            $gestionSaisie->mettreAJourEtatSaisie($agent);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_enfant_index', [], Response::HTTP_SEE_OTHER);
    }
}
