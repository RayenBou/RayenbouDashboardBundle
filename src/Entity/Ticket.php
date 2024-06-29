<?php

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
use Rayenbou\DashboardBundle\Entity\TicketMessage;
use Rayenbou\DashboardBundle\State\PatchStateProcessor;
use Rayenbou\DashboardBundle\State\TicketStateProcessor;
use Rayenbou\DashboardBundle\Repository\TicketRepository;
use Rayenbou\DashboardBundle\State\TicketByTokenProvider;


#[ApiResource]
#[Patch(
    processor: PatchStateProcessor::class,
    security: "object.status == true",
    uriTemplate: '/tickets/{token}',
    uriVariables: ['token' => 'token']
)]
#[GetCollection]
#[Post(
    processor: TicketStateProcessor::class,
)]
#[Get(
    provider: TicketByTokenProvider::class,
    security: "object.email == user.getEmail()",
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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getClosedAt(): ?\DateTimeImmutable
    {
        return $this->closedAt;
    }

    public function setClosedAt(?\DateTimeImmutable $closedAt): static
    {
        $this->closedAt = $closedAt;

        return $this;
    }


    public function getDomain(): ?string
    {
        return $this->domain;
    }

    public function setDomain(string $domain): static
    {
        $this->domain = $domain;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, TicketMessage>
     */
    public function getTicketMessages(): Collection
    {
        return $this->ticketMessages;
    }

    public function addTicketMessage(TicketMessage $ticketMessage): static
    {
        if (!$this->ticketMessages->contains($ticketMessage)) {
            $this->ticketMessages->add($ticketMessage);
            $ticketMessage->setTicket($this);
        }

        return $this;
    }

    public function removeTicketMessage(TicketMessage $ticketMessage): static
    {
        if ($this->ticketMessages->removeElement($ticketMessage)) {
            // set the owning side to null (unless already changed)
            if ($ticketMessage->getTicket() === $this) {
                $ticketMessage->setTicket(null);
            }
        }

        return $this;
    }


    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {

        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of author
     *
     * @return ?string
     */
    public function getAuthor(): ?string
    {
        return $this->author;
    }

    /**
     * Set the value of author
     *
     * @param ?string $author
     *
     * @return self
     */
    public function setAuthor(?string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): static
    {
        $this->token = $token;

        return $this;
    }
}
