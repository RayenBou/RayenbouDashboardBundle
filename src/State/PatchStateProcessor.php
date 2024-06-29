<?php

namespace Rayenbou\DashboardBundle\State;

use ApiPlatform\Metadata\Operation;

use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\State\ProcessorInterface;
use Rayenbou\DashboardBundle\Entity\Ticket;
use Rayenbou\DashboardBundle\Entity\TicketMessage;
use Rayenbou\DashboardBundle\Repository\TicketRepository;

class PatchStateProcessor implements ProcessorInterface
{

    public function __construct(private EntityManagerInterface $entityManager, private TicketRepository $ticketRepository)
    {
    }

    public function process($data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        if ($data instanceof Ticket) {


            if ($data->getDescription()) {
                $ticketMessage = new TicketMessage();
                $ticketMessage->setDescription($data->getDescription());
                $ticketMessage->setTicket($data);
                $ticketMessage->setAuthor($data->getAuthor() ?? $data->getEmail());
                $this->entityManager->persist($ticketMessage);
            }
            $this->entityManager->persist($data);
            $this->entityManager->flush();
        }
    }
}
