<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use App\Repository\ReservationApartmentRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ReservationApartmentRepository::class)]
class ReservationApartment
{
    private const READ = [
        'reservation_apartment:read',
        'reservations:read',
        'customer:read'
    ];

    #[ORM\Id]
    #[ORM\Column]
    #[Groups(self::READ)]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    /**
     * @var Collection<int, Apartment>
     */
    #[Groups(self::READ)]
    #[ORM\ManyToMany(targetEntity: Apartment::class, inversedBy: 'reservationApartments')]
    private Collection $apartments;

    #[Groups(self::READ)]
    #[ORM\JoinColumn(nullable: false)]
    #[ORM\OneToOne(inversedBy: 'reservationApartment', cascade: ['persist', 'remove'])]
    private ?Reservation $reservation = null;

    #[ORM\OneToOne(mappedBy: 'reservation_apartment', cascade: ['persist', 'remove'])]
    private ?Reservations $reservations = null;

    public function __construct()
    {
        $this->apartments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Apartment>
     */
    public function getApartments(): Collection
    {
        return $this->apartments;
    }

    public function addApartment(Apartment $apartment): static
    {
        if (!$this->apartments->contains($apartment)) {
            $this->apartments->add($apartment);
        }

        return $this;
    }

    public function removeApartment(Apartment $apartment): static
    {
        $this->apartments->removeElement($apartment);

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
            $this->reservations->setReservationApartment(null);
        }

        // set the owning side of the relation if necessary
        if ($reservations !== null && $reservations->getReservationApartment() !== $this) {
            $reservations->setReservationApartment($this);
        }

        $this->reservations = $reservations;

        return $this;
    }
}
