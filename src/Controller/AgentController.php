<?php

namespace App\Controller;

use App\Entity\Agent;
use App\Form\AgentType;
use App\Form\SearchAgentType;
use App\Repository\AgentRepository;
use App\Service\Statistiques;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/agent')]
#[IsGranted('ROLE_USER')]
class AgentController extends AbstractController
{
    #[Route('/', name: 'app_agent_index')]
    public function index(AgentRepository $agentRepository, Request $request, Statistiques $statistiques): Response
    {
        $agt = null;
        $user = $this->getUser();
        $form = $this->createForm(SearchAgentType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer le matricule saisi dans le formulaire
            $data = $form->getData();
            $mat = $data['matricule'];

            $agt = $agentRepository->findWithEnfants($mat);
            if (!$agt) {
                $this->addFlash('danger',
                    '<strong>Erreur !!!</strong> Il n\'existe aucun agent public avec le matricule <strong>'.$mat.'</strong> 
                        dans la base de données des agents publics ayant des enfants.'
                );
            }
            //dd($agt);
        }

        return $this->render('agent/index.html.twig', [
            'form' => $form->createView(),
            'agent' => $agt,
            'compteurUserJour' => $statistiques->getDailyCompteurUser($user),
            'compteurUser' => $statistiques->getCompteurUser($user),
            'totalActeJour' => $statistiques->getDailyCountActesNaissances(),
            'globalUserStats' => $statistiques->getUserStats('DESC'),
            'dailyUserStats' => $statistiques->getDailyUserStats('DESC'),
        ]);
    }

    #[Route('/new', name: 'app_agent_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $agent = new Agent();
        $form = $this->createForm(AgentType::class, $agent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($agent);
            $entityManager->flush();

            return $this->redirectToRoute('app_agent_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('agent/new.html.twig', [
            'agent' => $agent,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_agent_show', methods: ['GET'])]
    public function show(Agent $agent, AgentRepository $agentRepository, Request $request, Statistiques $statistiques): Response
    {
        $user = $this->getUser();
        $agt = $agentRepository->findWithEnfantsId($agent);

        $form = $this->createForm(SearchAgentType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer le matricule saisi dans le formulaire
            $data = $form->getData();
            $mat = $data['matricule'];

            $agt = $agentRepository->findWithEnfants($mat);
            if (!$agt) {
                $this->addFlash('danger',
                    '<strong>Erreur !!!</strong> Il n\'existe aucun agent public avec le matricule <strong>'.$mat.'</strong> 
                        dans la base de données des agents publics ayant des enfants.'
                );
            }
            //dd($agt);
        }

        return $this->render('agent/index.html.twig', [
            'agent' => $agt,
            'form' => $form,
            'compteurUserJour' => $statistiques->getDailyCompteurUser($user),
            'compteurUser' => $statistiques->getCompteurUser($user),
            'totalActeJour' => $statistiques->getDailyCountActesNaissances(),
            'globalUserStats' => $statistiques->getUserStats('DESC'),
            'dailyUserStats' => $statistiques->getDailyUserStats('DESC'),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_agent_edit', methods: ['POST','GET'])]
    public function edit(Request $request, Agent $agent, EntityManagerInterface $entityManager, Statistiques $statistiques): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(AgentType::class, $agent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_agent_show', [
                'id' => $agent->getId()
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('agent/edit.html.twig', [
            'agent' => $agent,
            'form' => $form,
            'compteurUserJour' => $statistiques->getDailyCompteurUser($user),
            'compteurUser' => $statistiques->getCompteurUser($user),
            'totalActeJour' => $statistiques->getDailyCountActesNaissances(),
            'globalUserStats' => $statistiques->getUserStats('DESC'),
            'dailyUserStats' => $statistiques->getDailyUserStats('DESC'),
        ]);
    }

    #[Route('/{id}', name: 'app_agent_delete', methods: ['POST'])]
    public function delete(Request $request, Agent $agent, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$agent->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($agent);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_agent_index', [], Response::HTTP_SEE_OTHER);
    }
}
