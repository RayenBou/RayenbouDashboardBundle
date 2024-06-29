<?php

namespace Rayenbou\DashboardBundle\State;

use ApiPlatform\Metadata\Operation;
use Doctrine\ORM\EntityManagerInterface;

use ApiPlatform\State\ProcessorInterface;
use Rayenbou\DashboardBundle\Entity\Ticket;
use Rayenbou\DashboardBundle\Entity\TicketMessage;

class TicketStateProcessor implements ProcessorInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        if ($data instanceof Ticket) {

            $this->entityManager->persist($data);

            if ($data->getDescription()) {
                $ticketMessage = new TicketMessage();
                $ticketMessage->setDescription($data->getDescription());
                $ticketMessage->setTicket($data);
                $ticketMessage->setAuthor($data->getAuthor() ?? $data->getEmail());
                $this->entityManager->persist($ticketMessage);
                // Logique spécifique liée à la description
            }
            $this->entityManager->flush();
        }
    }
}
