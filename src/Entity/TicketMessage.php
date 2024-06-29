<?php

namespace Rayenbou\DashboardBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Rayenbou\DashboardBundle\Repository\TicketMessageRepository;

#[ORM\Entity(repositoryClass: TicketMessageRepository::class)]
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

    /**
     * Initializes a new instance of the TicketMessage class.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    /**
     * Gets the ID of the ticket message.
     *
     * @return int|null the ID of the ticket message
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Gets the description of the ticket message.
     *
     * @return string|null the description of the ticket message
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Sets the description of the ticket message.
     *
     * @param string $description the description of the ticket message
     *
     * @return static the current instance of the TicketMessage class
     */
    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Gets the creation date of the ticket message.
     *
     * @return \DateTimeImmutable|null the creation date of the ticket message
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Gets the author of the ticket message.
     *
     * @return string|null the author of the ticket message
     */
    public function getAuthor(): ?string
    {
        return $this->author;
    }

    /**
     * Sets the author of the ticket message.
     *
     * @param string $author the author of the ticket message
     *
     * @return static the current instance of the TicketMessage class
     */
    public function setAuthor(string $author): static
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Gets the ticket associated with the message.
     *
     * @return Ticket|null the ticket associated with the message
     */
    public function getTicket(): ?Ticket
    {
        return $this->ticket;
    }

    /**
     * Sets the ticket associated with the message.
     *
     * @param Ticket|null $ticket the ticket associated with the message
     *
     * @return static the current instance of the TicketMessage class
     */
    public function setTicket(?Ticket $ticket): static
    {
        $this->ticket = $ticket;

        return $this;
    }
}
