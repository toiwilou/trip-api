<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ApartmentRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ApartmentRepository::class)]
class Apartment
{    
    private const NO_CATEGORY = [
        'apartment:read',
        'wishlist:read'
    ];

    private const READ = [
        'apartment:read',
        'category_apartment:read',
        'wishlist:read'
    ];

    #[ORM\Id]
    #[ORM\Column]
    #[Groups(self::READ)]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[Groups(self::NO_CATEGORY)]
    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(inversedBy: 'apartments')]
    private ?CategoryApartment $category = null;

    #[Groups(self::READ)]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Groups(self::READ)]
    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[Groups(self::READ)]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(self::READ)]
    private ?int $rooms = null;

    #[ORM\Column]
    #[Groups(self::READ)]
    private ?int $bath_rooms = null;

    #[ORM\Column]
    #[Groups(self::READ)]
    private ?int $area = null;

    #[ORM\Column]
    #[Groups(self::READ)]
    private ?float $price = null;

    #[Groups(self::READ)]
    #[ORM\Column(length: 255)]
    private ?string $owner = null;

    #[Groups(self::READ)]
    #[ORM\Column(nullable: true)]
    private ?bool $available = null;

    #[Groups(self::READ)]
    #[ORM\Column(length: 255)]
    private ?string $principal_picture = null;

    #[Groups(self::READ)]
    #[ORM\Column(nullable: true)]
    private ?array $pictures = null;

    #[Groups(self::READ)]
    #[ORM\Column(nullable: true)]
    private ?array $videos = null;

    #[Groups(self::READ)]
    #[ORM\Column(nullable: true)]
    private ?bool $active = null;

    /**
     * @var Collection<int, ReservationApartment>
     */
    #[ORM\ManyToMany(targetEntity: ReservationApartment::class, mappedBy: 'apartments')]
    private Collection $reservationApartments;

    /**
     * @var Collection<int, WishlistApartment>
     */
    #[ORM\OneToMany(targetEntity: WishlistApartment::class, mappedBy: 'apartment')]
    private Collection $wishlistApartments;

    #[Groups(self::READ)]
    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(inversedBy: 'apartments')]
    private ?City $city = null;

    public function __construct()
    {
        $this->reservationApartments = new ArrayCollection();
        $this->wishlistApartments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategory(): ?CategoryApartment
    {
        return $this->category;
    }

    public function setCategory(?CategoryApartment $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getRooms(): ?int
    {
        return $this->rooms;
    }

    public function setRooms(int $rooms): static
    {
        $this->rooms = $rooms;

        return $this;
    }

    public function getBathRooms(): ?int
    {
        return $this->bath_rooms;
    }

    public function setBathRooms(int $bath_rooms): static
    {
        $this->bath_rooms = $bath_rooms;

        return $this;
    }

    public function getArea(): ?int
    {
        return $this->area;
    }

    public function setArea(int $area): static
    {
        $this->area = $area;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getOwner(): ?string
    {
        return $this->owner;
    }

    public function setOwner(string $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    public function isAvailable(): ?bool
    {
        return $this->available;
    }

    public function setAvailable(?bool $available): static
    {
        $this->available = $available;

        return $this;
    }

    public function getPrincipalPicture(): ?string
    {
        return $this->principal_picture;
    }

    public function setPrincipalPicture(string $principal_picture): static
    {
        $this->principal_picture = $principal_picture;

        return $this;
    }

    public function getPictures(): ?array
    {
        return $this->pictures;
    }

    public function setPictures(?array $pictures): static
    {
        $this->pictures = $pictures;

        return $this;
    }

    public function getVideos(): ?array
    {
        return $this->videos;
    }

    public function setVideos(?array $videos): static
    {
        $this->videos = $videos;

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

    /**
     * @return Collection<int, ReservationApartment>
     */
    public function getReservationApartments(): Collection
    {
        return $this->reservationApartments;
    }

    public function addReservationApartment(ReservationApartment $reservationApartment): static
    {
        if (!$this->reservationApartments->contains($reservationApartment)) {
            $this->reservationApartments->add($reservationApartment);
            $reservationApartment->addApartment($this);
        }

        return $this;
    }

    public function removeReservationApartment(ReservationApartment $reservationApartment): static
    {
        if ($this->reservationApartments->removeElement($reservationApartment)) {
            $reservationApartment->removeApartment($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, WishlistApartment>
     */
    public function getWishlistApartments(): Collection
    {
        return $this->wishlistApartments;
    }

    public function addWishlistApartment(WishlistApartment $wishlistApartment): static
    {
        if (!$this->wishlistApartments->contains($wishlistApartment)) {
            $this->wishlistApartments->add($wishlistApartment);
            $wishlistApartment->setApartment($this);
        }

        return $this;
    }

    public function removeWishlistApartment(WishlistApartment $wishlistApartment): static
    {
        if ($this->wishlistApartments->removeElement($wishlistApartment)) {
            // set the owning side to null (unless already changed)
            if ($wishlistApartment->getApartment() === $this) {
                $wishlistApartment->setApartment(null);
            }
        }

        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): static
    {
        $this->city = $city;

        return $this;
    }
}
