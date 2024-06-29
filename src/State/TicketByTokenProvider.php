<?php

namespace Rayenbou\DashboardBundle\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use Rayenbou\DashboardBundle\Repository\TicketRepository;

/**
 * Class TicketByTokenProvider
 * Provides the functionality to retrieve a ticket by its token.
 */
class TicketByTokenProvider implements ProviderInterface
{
    /**
     * TicketByTokenProvider constructor.
     *
     * @param TicketRepository $ticketRepository the ticket repository
     */
    public function __construct(private TicketRepository $ticketRepository)
    {
    }

    /**
     * Retrieves a ticket by its token.
     *
     * @param Operation $operation    the operation metadata
     * @param array     $uriVariables the URI variables
     * @param array     $context      the context
     *
     * @return object|array|null the retrieved ticket
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $ticket = $this->ticketRepository->findOneBy(['token' => $uriVariables['token']]);

        return $ticket;
    }
}
