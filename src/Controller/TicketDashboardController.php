<?php

declare(strict_types=1);

namespace Rayenbou\DashboardBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Rayenbou\DashboardBundle\Entity\Ticket;
use Rayenbou\DashboardBundle\Entity\ApiUser;
use Rayenbou\DashboardBundle\Form\TicketType;
use Symfony\Component\HttpFoundation\Request;
use Rayenbou\DashboardBundle\Form\ApiUserType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Rayenbou\DashboardBundle\Entity\TicketMessage;
use Rayenbou\DashboardBundle\Repository\TicketRepository;
use Rayenbou\DashboardBundle\Repository\ApiUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/dashboard/ticket')]
class TicketDashboardController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    #[Route('/', name: 'rayenbou_dashboard_index', methods: ['GET', 'POST'])]
    public function index(TicketRepository $ticketRepository, ApiUserRepository $apiUserRepository, Request $request, UserPasswordHasherInterface $hasher): Response
    {
        $apiUser = new ApiUser();
        $apiUser->setPassword(bin2hex(random_bytes(32)));
        $form = $this->createForm(ApiUserType::class, $apiUser)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $apiUser->setPassword($hasher->hashPassword($apiUser, $apiUser->getPassword()));
            $this->entityManager->persist($apiUser);
            $this->entityManager->flush();

            return $this->redirectToRoute('rayenbou_dashboard_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('@RayenbouDashboard/dashboard/index.html.twig', [
            'tickets' => $ticketRepository->findAll(),
            'form' => $form,
            'apiUser' => $apiUserRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'rayenbou_dashboard_show', methods: ['GET', 'POST'])]
    public function show(Ticket $ticket, Request $request): Response
    {
        $message = new TicketMessage();
        $form = $this->createForm(TicketType::class, $message, ['title' => false])
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ticket->addTicketMessage($message);
            $this->entityManager->persist($ticket);
            $this->entityManager->persist($message);
            $this->entityManager->flush();

            return $this->redirectToRoute('rayenbou_dashboard_show', [
                'id' => $ticket->getId(),
            ]);
        }
        return $this->render('@RayenbouDashboard/dashboard/show.html.twig', [
            'form' => $form,
            'ticket' => $ticket,
        ]);
    }

    #[Route('/change-status/{id}', name: 'rayenbou_dashboard_change_status', methods: ['POST'])]
    public function changeStatus(Ticket $ticket): Response
    {
        $ticket->setStatus(!$ticket->getStatus());

        if ($ticket->getStatus()) {
            $ticket->setClosedAt(null);
        } else {
            $ticket->setClosedAt(new \DateTimeImmutable());
        }

        $this->entityManager->persist($ticket);
        $this->entityManager->flush();

        return $this->redirectToRoute('rayenbou_dashboard_show', ['id' => $ticket->getId()]);
    }

    #[Route('/{id}', name: 'rayenbou_dashboard_delete', methods: ['POST'])]
    public function delete(Request $request, Ticket $ticket): Response
    {
        if ($this->isCsrfTokenValid('delete' . $ticket->getId(), (string) $request->getPayload()->get('_token'))) {
            $this->entityManager->remove($ticket);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('rayenbou_dashboard_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/user-api/{id}', name: 'rayenbou_dashboard_user_delete', methods: ['POST'])]
    public function deleteUser(Request $request, ApiUser $apiUser): Response
    {
        if ($this->isCsrfTokenValid('delete' . $apiUser->getId(), (string) $request->getPayload()->get('_token'))) {
            $this->entityManager->remove($apiUser);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('rayenbou_dashboard_index', [], Response::HTTP_SEE_OTHER);
    }
}
