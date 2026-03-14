<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CustomerRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CustomerRepository::class)]
class Customer
{
    private const CUSTOMERREAD = ['customer:read'];
    private const NO_NOTICEREAD = [
        'customer:read',
        'reservation_apartment:read',
        'reservation_vehicle:read',
        'reservations:read',
        'reservation:read'
    ];

    private const ALLREAD = [
        'customer:read',
        'notice:read',
        'reservation_apartment:read',
        'reservation_vehicle:read',
        'reservations:read',
        'reservation:read'
    ];

    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue]
    #[Groups(self::ALLREAD)]
    private ?int $id = null;

    #[Groups(self::ALLREAD)]
    #[ORM\JoinColumn(nullable: false)]
    #[ORM\OneToOne(inversedBy: 'customer', cascade: ['persist', 'remove'])]
    private ?User $user = null;

    #[Groups(self::NO_NOTICEREAD)]
    #[ORM\Column(length: 20, nullable: true)]
    private ?string $phone = null;

    #[Groups(self::CUSTOMERREAD)]
    #[ORM\OneToOne(mappedBy: 'customer', cascade: ['persist', 'remove'])]
    private ?WishlistApartment $wishlistApartment = null;

    #[Groups(self::CUSTOMERREAD)]
    #[ORM\OneToOne(mappedBy: 'customer', cascade: ['persist', 'remove'])]
    private ?WishlistVehicle $wishlistVehicle = null;

    /**
     * @var Collection<int, Notice>
     */
    #[Groups(self::CUSTOMERREAD)]
    #[ORM\OneToMany(targetEntity: Notice::class, mappedBy: 'customer')]
    private Collection $notices;

    /**
     * @var Collection<int, Reservations>
     */
    #[Groups(self::CUSTOMERREAD)]
    #[ORM\OneToMany(targetEntity: Reservations::class, mappedBy: 'customer')]
    private Collection $reservations;

    public function __construct()
    {
        $this->notices = new ArrayCollection();
        $this->reservations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getWishlistApartment(): ?WishlistApartment
    {
        return $this->wishlistApartment;
    }

    public function setWishlistApartment(?WishlistApartment $wishlistApartment): static
    {
        // unset the owning side of the relation if necessary
        if ($wishlistApartment === null && $this->wishlistApartment !== null) {
            $this->wishlistApartment->setCustomer(null);
        }

        // set the owning side of the relation if necessary
        if ($wishlistApartment !== null && $wishlistApartment->getCustomer() !== $this) {
            $wishlistApartment->setCustomer($this);
        }

        $this->wishlistApartment = $wishlistApartment;

        return $this;
    }

    public function getWishlistVehicle(): ?WishlistVehicle
    {
        return $this->wishlistVehicle;
    }

    public function setWishlistVehicle(WishlistVehicle $wishlistVehicle): static
    {
        // set the owning side of the relation if necessary
        if ($wishlistVehicle->getCustomer() !== $this) {
            $wishlistVehicle->setCustomer($this);
        }

        $this->wishlistVehicle = $wishlistVehicle;

        return $this;
    }

    /**
     * @return Collection<int, Notice>
     */
    public function getNotices(): Collection
    {
        return $this->notices;
    }

    public function addNotice(Notice $notice): static
    {
        if (!$this->notices->contains($notice)) {
            $this->notices->add($notice);
            $notice->setCustomer($this);
        }

        return $this;
    }

    public function removeNotice(Notice $notice): static
    {
        if ($this->notices->removeElement($notice)) {
            // set the owning side to null (unless already changed)
            if ($notice->getCustomer() === $this) {
                $notice->setCustomer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Reservations>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservations $reservation): static
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setCustomer($this);
        }

        return $this;
    }

    public function removeReservation(Reservations $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getCustomer() === $this) {
                $reservation->setCustomer(null);
            }
        }

        return $this;
    }
}
