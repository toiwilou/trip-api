<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReservationsRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ReservationsRepository::class)]
class Reservations
{
    private const READ = [
        'reservations:read',
        'customer:read',
        'payment:read'
    ];

    #[ORM\Id]
    #[ORM\Column]
    #[Groups(self::READ)]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[Groups(self::READ)]
    #[ORM\OneToOne(inversedBy: 'reservations', cascade: ['persist', 'remove'])]
    private ?ReservationApartment $reservation_apartment = null;

    #[Groups(self::READ)]
    #[ORM\OneToOne(inversedBy: 'reservations', cascade: ['persist', 'remove'])]
    private ?ReservationVehicle $reservation_vehicle = null;

    #[ORM\OneToOne(mappedBy: 'reservation', cascade: ['persist', 'remove'])]
    private ?Payment $payment = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Customer $customer = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReservationApartment(): ?ReservationApartment
    {
        return $this->reservation_apartment;
    }

    public function setReservationApartment(?ReservationApartment $reservation_apartment): static
    {
        $this->reservation_apartment = $reservation_apartment;

        return $this;
    }

    public function getReservationVehicle(): ?ReservationVehicle
    {
        return $this->reservation_vehicle;
    }

    public function setReservationVehicle(?ReservationVehicle $reservation_vehicle): static
    {
        $this->reservation_vehicle = $reservation_vehicle;

        return $this;
    }

    public function getPayment(): ?Payment
    {
        return $this->payment;
    }

    public function setPayment(Payment $payment): static
    {
        // set the owning side of the relation if necessary
        if ($payment->getReservation() !== $this) {
            $payment->setReservation($this);
        }

        $this->payment = $payment;

        return $this;
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
}
