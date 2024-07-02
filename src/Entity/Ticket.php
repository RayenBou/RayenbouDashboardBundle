<?php

declare(strict_types=1);

namespace Rayenbou\DashboardBundle\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Rayenbou\DashboardBundle\State\PatchStateProcessor;
use Rayenbou\DashboardBundle\State\TicketStateProcessor;
use Rayenbou\DashboardBundle\Repository\TicketRepository;
use Rayenbou\DashboardBundle\State\TicketByTokenProvider;

#[ApiResource]
#[Patch(
    processor: PatchStateProcessor::class,
    security: 'object.status == true',
    uriTemplate: '/tickets/{token}',
    uriVariables: ['token' => 'token']
)]
#[GetCollection]
#[Post(
    processor: TicketStateProcessor::class,
)]
#[Get(
    provider: TicketByTokenProvider::class,
    security: 'object.email == user.getEmail()',
    securityMessage: 'Sorry, you are not allowed to access this resource',
    uriTemplate: '/tickets/{token}',
    uriVariables: ['token' => 'token']
)]
#[ORM\Entity(repositoryClass: TicketRepository::class)]
class Ticket
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $closedAt = null;

    #[ORM\Column(length: 255)]
    private ?string $domain = null;

    #[ORM\Column]
    public ?bool $status = true;

    private ?string $description = null;

    private ?string $author = null;

    /**
     * @var Collection<int, TicketMessage>
     */
    #[ORM\OneToMany(targetEntity: TicketMessage::class, mappedBy: 'ticket')]
    private Collection $ticketMessages;

    #[ORM\Column(length: 255)]
    public ?string $email = null;

    #[ORM\Column(length: 255)]
    public ?string $token = null;

    public function __construct()
    {
        $this->token = Uuid::v4();
        $this->createdAt = new \DateTimeImmutable();
        $this->ticketMessages = new ArrayCollection();
    }

    /**
     * Get the ID of the ticket.
     *
     * @return int|null the ID of the ticket
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the title of the ticket.
     *
     * @return string|null the title of the ticket
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Set the title of the ticket.
     *
     * @param string $title the title of the ticket
     *
     * @return static the updated Ticket object
     */
    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the creation date of the ticket.
     *
     * @return \DateTimeImmutable|null the creation date of the ticket
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Set the creation date of the ticket.
     *
     * @param \DateTimeImmutable $createdAt the creation date of the ticket
     *
     * @return static the updated Ticket object
     */
    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get the closed date of the ticket.
     *
     * @return \DateTimeImmutable|null the closed date of the ticket
     */
    public function getClosedAt(): ?\DateTimeImmutable
    {
        return $this->closedAt;
    }

    /**
     * Set the closed date of the ticket.
     *
     * @param \DateTimeImmutable|null $closedAt the closed date of the ticket
     *
     * @return static the updated Ticket object
     */
    public function setClosedAt(?\DateTimeImmutable $closedAt): static
    {
        $this->closedAt = $closedAt;

        return $this;
    }

    /**
     * Get the domain of the ticket.
     *
     * @return string|null the domain of the ticket
     */
    public function getDomain(): ?string
    {
        return $this->domain;
    }

    /**
     * Set the domain of the ticket.
     *
     * @param string $domain the domain of the ticket
     *
     * @return static the updated Ticket object
     */
    public function setDomain(string $domain): static
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * Get the status of the ticket.
     *
     * @return bool|null the status of the ticket
     */
    public function getStatus(): ?bool
    {
        return $this->status;
    }

    /**
     * Set the status of the ticket.
     *
     * @param bool $status the status of the ticket
     *
     * @return static the updated Ticket object
     */
    public function setStatus(bool $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get the collection of ticket messages associated with the ticket.
     *
     * @return Collection<int, TicketMessage> the collection of ticket messages
     */
    public function getTicketMessages(): Collection
    {
        return $this->ticketMessages;
    }

    /**
     * Add a ticket message to the collection of ticket messages.
     *
     * @param TicketMessage $ticketMessage the ticket message to add
     *
     * @return static the updated Ticket object
     */
    public function addTicketMessage(TicketMessage $ticketMessage): static
    {
        if (!$this->ticketMessages->contains($ticketMessage)) {
            $this->ticketMessages->add($ticketMessage);
            $ticketMessage->setTicket($this);
        }

        return $this;
    }

    /**
     * Remove a ticket message from the collection of ticket messages.
     *
     * @param TicketMessage $ticketMessage the ticket message to remove
     *
     * @return static the updated Ticket object
     */
    public function removeTicketMessage(TicketMessage $ticketMessage): static
    {
        if ($this->ticketMessages->removeElement($ticketMessage)) {
            if ($ticketMessage->getTicket() === $this) {
                $ticketMessage->setTicket(null);
            }
        }

        return $this;
    }

    /**
     * Set the description of the ticket.
     *
     * @param string|null $description the description of the ticket
     *
     * @return self the updated Ticket object
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the description of the ticket.
     *
     * @return string|null the description of the ticket
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Get the email associated with the ticket.
     *
     * @return string|null the email associated with the ticket
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Set the email associated with the ticket.
     *
     * @param string $email the email associated with the ticket
     *
     * @return static the updated Ticket object
     */
    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the author of the ticket.
     *
     * @return string|null the author of the ticket
     */
    public function getAuthor(): ?string
    {
        return $this->author;
    }

    /**
     * Set the author of the ticket.
     *
     * @param string|null $author the author of the ticket
     *
     * @return self the updated Ticket object
     */
    public function setAuthor(?string $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get the token of the ticket.
     *
     * @return string|null the token of the ticket
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * Set the token of the ticket.
     *
     * @param string $token the token of the ticket
     *
     * @return static the updated Ticket object
     */
    public function setToken(string $token): static
    {
        $this->token = $token;

        return $this;
    }
}
