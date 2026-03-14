<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\WishlistVehicleRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: WishlistVehicleRepository::class)]
class WishlistVehicle
{
    private const READ = ['wishlist_vehicle:read'];

    #[ORM\Id]
    #[ORM\Column]
    #[Groups(self::READ)]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[Groups(self::READ)]
    #[ORM\JoinColumn(nullable: false)]
    #[ORM\OneToOne(inversedBy: 'wishlistVehicle', cascade: ['persist', 'remove'])]
    private ?Customer $customer = null;

    #[Groups(self::READ)]
    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(inversedBy: 'wishlistVehicles')]
    private ?Vehicle $vehicle = null;

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

    public function setCustomer(Customer $customer): static
    {
        $this->customer = $customer;

        return $this;
    }

    public function getVehicle(): ?Vehicle
    {
        return $this->vehicle;
    }

    public function setVehicle(?Vehicle $vehicle): static
    {
        $this->vehicle = $vehicle;

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
