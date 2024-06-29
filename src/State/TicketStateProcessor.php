<?php

namespace Rayenbou\DashboardBundle\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Rayenbou\DashboardBundle\Entity\Ticket;
use Rayenbou\DashboardBundle\Entity\TicketMessage;

/**
 * Class TicketStateProcessor
 * This class is responsible for processing the state of a Ticket entity.
 */
class TicketStateProcessor implements ProcessorInterface
{
    /**
     * TicketStateProcessor constructor.
     *
     * @param EntityManagerInterface $entityManager the entity manager used to persist the data
     */
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    /**
     * Process the state of the data.
     *
     * @param mixed     $data         the data to process
     * @param Operation $operation    the operation being performed
     * @param array     $uriVariables the URI variables
     * @param array     $context      the context
     */
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
            }
            $this->entityManager->flush();
        }
    }
}
