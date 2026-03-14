<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\WishlistApartmentRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: WishlistApartmentRepository::class)]
class WishlistApartment
{
    private const READ = ['wishlist_apartment:read'];

    #[ORM\Id]
    #[ORM\Column]
    #[Groups(self::READ)]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[Groups(self::READ)]
    #[ORM\JoinColumn(nullable: false)]
    #[ORM\OneToOne(inversedBy: 'wishlistApartment', cascade: ['persist', 'remove'])]
    private ?Customer $customer = null;

    #[Groups(self::READ)]
    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(inversedBy: 'wishlistApartments')]
    private ?Apartment $apartment = null;

    #[Groups(self::READ)]
    #[ORM\Column(nullable: true)]
    private ?bool $active = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): static
    {
        $this->customer = $customer;

        return $this;
    }

    public function getApartment(): ?Apartment
    {
        return $this->apartment;
    }

    public function setApartment(?Apartment $apartment): static
    {
        $this->apartment = $apartment;

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
