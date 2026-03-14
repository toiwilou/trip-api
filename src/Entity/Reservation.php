<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReservationRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    private const READ = [
        'reservation_apartment:read',
        'reservation_vehicle:read',
        'reservations:read',
        'reservation:read',
        'customer:read'
    ];

    #[ORM\Id]
    #[ORM\Column]
    #[Groups(self::READ)]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(self::READ)]
    private ?\DateTime $begin_date = null;

    #[ORM\Column]
    #[Groups(self::READ)]
    private ?\DateTime $end_date = null;

    #[Groups(self::READ)]
    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(inversedBy: 'reservations')]
    private ?Status $status = null;

    #[Groups(self::READ)]
    #[ORM\Column(nullable: true)]
    private ?bool $active = null;

    #[ORM\OneToOne(mappedBy: 'reservation', cascade: ['persist', 'remove'])]
    private ?ReservationApartment $reservationApartment = null;

    #[ORM\OneToOne(mappedBy: 'reservation', cascade: ['persist', 'remove'])]
    private ?ReservationVehicle $reservationVehicle = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBeginDate(): ?\DateTime
    {
        return $this->begin_date;
    }

    public function setBeginDate(\DateTime $begin_date): static
    {
        $this->begin_date = $begin_date;

        return $this;
    }

    public function getEndDate(): ?\DateTime
    {
        return $this->end_date;
    }

    public function setEndDate(\DateTime $end_date): static
    {
        $this->end_date = $end_date;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): static
    {
        $this->status = $status;

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

    public function getReservationApartment(): ?ReservationApartment
    {
        return $this->reservationApartment;
    }

    public function setReservationApartment(ReservationApartment $reservationApartment): static
    {
        // set the owning side of the relation if necessary
        if ($reservationApartment->getReservation() !== $this) {
            $reservationApartment->setReservation($this);
        }

        $this->reservationApartment = $reservationApartment;

        return $this;
    }

    public function getReservationVehicle(): ?ReservationVehicle
    {
        return $this->reservationVehicle;
    }

    public function setReservationVehicle(ReservationVehicle $reservationVehicle): static
    {
        // set the owning side of the relation if necessary
        if ($reservationVehicle->getReservation() !== $this) {
            $reservationVehicle->setReservation($this);
        }

        $this->reservationVehicle = $reservationVehicle;

        return $this;
    }
}
