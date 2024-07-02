<?php

declare(strict_types=1);

namespace Rayenbou\DashboardBundle\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Rayenbou\DashboardBundle\Entity\Ticket;
use Rayenbou\DashboardBundle\Entity\TicketMessage;

class PatchStateProcessor implements ProcessorInterface
{
    /**
     * PatchStateProcessor constructor.
     *
     * @param EntityManagerInterface $entityManager the entity manager used to persist changes
     */
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    /**
     * Process the state of the given data during a PATCH operation.
     *
     * @param mixed                $data         the data being processed
     * @param Operation            $operation    the operation being performed
     * @param array<string,string> $uriVariables the variables extracted from the URI
     * @param array                $context      the context of the operation
     */
    public function process($data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        if ($data instanceof Ticket) {
            if ($data->getDescription()) {
                $ticketMessage = (new TicketMessage())
                    ->setDescription($data->getDescription())
                    ->setTicket($data)
                    ->setAuthor($data->getAuthor() ?? $data->getEmail() ?? 'Anonymous');
                $this->entityManager->persist($ticketMessage);
            }
            $this->entityManager->persist($data);
            $this->entityManager->flush();
        }
    }
}
