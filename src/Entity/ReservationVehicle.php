<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use App\Repository\ReservationVehicleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ReservationVehicleRepository::class)]
class ReservationVehicle
{
    private const READ = [
        'reservation_vehicle:read',
        'reservations:read',
        'customer:read'
    ];

    #[ORM\Id]
    #[ORM\Column]
    #[Groups(self::READ)]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    /**
     * @var Collection<int, Vehicle>
     */
    #[Groups(self::READ)]
    #[ORM\ManyToMany(targetEntity: Vehicle::class, inversedBy: 'reservationVehicles')]
    private Collection $vehicles;

    #[Groups(self::READ)]
    #[ORM\JoinColumn(nullable: false)]
    #[ORM\OneToOne(inversedBy: 'reservationVehicle', cascade: ['persist', 'remove'])]
    private ?Reservation $reservation = null;

    #[ORM\OneToOne(mappedBy: 'reservation_vehicle', cascade: ['persist', 'remove'])]
    private ?Reservations $reservations = null;

    public function __construct()
    {
        $this->vehicles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Vehicle>
     */
    public function getVehicles(): Collection
    {
        return $this->vehicles;
    }

    public function addVehicle(Vehicle $vehicle): static
    {
        if (!$this->vehicles->contains($vehicle)) {
            $this->vehicles->add($vehicle);
        }

        return $this;
    }

    public function removeVehicle(Vehicle $vehicle): static
    {
        $this->vehicles->removeElement($vehicle);

        return $this;
    }

    public function getReservation(): ?Reservation
    {
        return $this->reservation;
    }

    public function setReservation(Reservation $reservation): static
    {
        $this->reservation = $reservation;

        return $this;
    }

    public function getReservations(): ?Reservations
    {
        return $this->reservations;
    }

    public function setReservations(?Reservations $reservations): static
    {
        // unset the owning side of the relation if necessary
        if ($reservations === null && $this->reservations !== null) {
            $this->reservations->setReservationVehicle(null);
        }

        // set the owning side of the relation if necessary
        if ($reservations !== null && $reservations->getReservationVehicle() !== $this) {
            $reservations->setReservationVehicle($this);
        }

        $this->reservations = $reservations;

        return $this;
    }
}
