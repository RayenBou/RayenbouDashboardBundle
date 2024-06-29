<?php

namespace Rayenbou\DashboardBundle\Entity;


use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use Rayenbou\DashboardBundle\Repository\TicketMessageRepository;




#[ORM\Entity(repositoryClass: TicketMessageRepository::class)]
// #[ApiResource()]
class TicketMessage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(length: 255)]
    private ?string $author = null;

    #[ORM\ManyToOne(inversedBy: 'ticketMessages')]
    #[ORM\JoinColumn(nullable: false)]


    private ?Ticket $ticket = null;


    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }



    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getTicket(): ?ticket
    {
        return $this->ticket;
    }

    public function setTicket(?ticket $ticket): static
    {

        $this->ticket = $ticket;

        return $this;
    }
}
