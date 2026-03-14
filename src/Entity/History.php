<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\HistoryRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: HistoryRepository::class)]
class History
{
    private const READ = ['history:read'];

    #[ORM\Id]
    #[ORM\Column]
    #[Groups(self::READ)]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[Groups(self::READ)]
    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(inversedBy: 'histories')]
    private ?User $user = null;

    #[ORM\Column]
    #[Groups(self::READ)]
    private ?\DateTime $date = null;

    #[Groups(self::READ)]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $action = null;

    #[Groups(self::READ)]
    #[ORM\Column(nullable: true)]
    private ?bool $active = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function setAction(string $action): static
    {
        $this->action = $action;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(?bool $active): static
    {
        $this->active = $active;

        return $this;
    }
}
