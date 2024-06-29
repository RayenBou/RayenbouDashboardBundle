<?php

namespace Rayenbou\DashboardBundle\State;



use ApiPlatform\Metadata\Operation;

use ApiPlatform\State\ProviderInterface;
use Rayenbou\DashboardBundle\Repository\TicketRepository;

class TicketByTokenProvider implements ProviderInterface
{

    public function __construct(private TicketRepository $ticketReponsitory)
    {
    }


    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $ticket = $this->ticketReponsitory->findOneBy(['token' => $uriVariables['token']]);

        return $ticket;
    }
}
