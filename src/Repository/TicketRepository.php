<?php

namespace Rayenbou\DashboardBundle\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Rayenbou\DashboardBundle\Entity\Ticket;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Ticket>
 */
class TicketRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ticket::class);
    }
}
