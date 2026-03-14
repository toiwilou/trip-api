<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\VehicleRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: VehicleRepository::class)]
class Vehicle
{
    private const NO_CATEGORY = [
        'vehicle:read',
        'wishlist:read'
    ];

    private const READ = [
        'vehicle:read',
        'category_vehicle:read',
        'wishlist:read'
    ];

    #[ORM\Id]
    #[ORM\Column]
    #[Groups(self::READ)]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[Groups(self::NO_CATEGORY)]
    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(inversedBy: 'vehicles')]
    private ?CategoryVehicle $category = null;

    #[Groups(self::READ)]
    #[ORM\Column(length: 255)]
    private ?string $brand = null;

    #[Groups(self::READ)]
    #[ORM\Column(length: 255)]
    private ?string $model = null;

    #[ORM\Column]
    #[Groups(self::READ)]
    private ?int $year = null;

    #[Groups(self::READ)]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $color = null;

    #[Groups(self::READ)]
    #[ORM\Column(length: 255)]
    private ?string $mileage = null;

    #[ORM\Column]
    #[Groups(self::READ)]
    private ?float $price = null;

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

    #[Groups(self::READ)]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    /**
     * @var Collection<int, ReservationVehicle>
     */
    #[ORM\ManyToMany(targetEntity: ReservationVehicle::class, mappedBy: 'vehicles')]
    private Collection $reservationVehicles;

    /**
     * @var Collection<int, WishlistVehicle>
     */
    #[ORM\OneToMany(targetEntity: WishlistVehicle::class, mappedBy: 'vehicle')]
    private Collection $wishlistVehicles;

    #[Groups(self::READ)]
    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[Groups(self::READ)]
    #[ORM\ManyToOne(inversedBy: 'vehicles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?City $city = null;

    public function __construct()
    {
        $this->reservationVehicles = new ArrayCollection();
        $this->wishlistVehicles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategory(): ?CategoryVehicle
    {
        return $this->category;
    }

    public function setCategory(?CategoryVehicle $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): static
    {
        $this->brand = $brand;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): static
    {
        $this->model = $model;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): static
    {
        $this->year = $year;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getMileage(): ?string
    {
        return $this->mileage;
    }

    public function setMileage(string $mileage): static
    {
        $this->mileage = $mileage;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, ReservationVehicle>
     */
    public function getReservationVehicles(): Collection
    {
        return $this->reservationVehicles;
    }

    public function addReservationVehicle(ReservationVehicle $reservationVehicle): static
    {
        if (!$this->reservationVehicles->contains($reservationVehicle)) {
            $this->reservationVehicles->add($reservationVehicle);
            $reservationVehicle->addVehicle($this);
        }

        return $this;
    }

    public function removeReservationVehicle(ReservationVehicle $reservationVehicle): static
    {
        if ($this->reservationVehicles->removeElement($reservationVehicle)) {
            $reservationVehicle->removeVehicle($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, WishlistVehicle>
     */
    public function getWishlistVehicles(): Collection
    {
        return $this->wishlistVehicles;
    }

    public function addWishlistVehicle(WishlistVehicle $wishlistVehicle): static
    {
        if (!$this->wishlistVehicles->contains($wishlistVehicle)) {
            $this->wishlistVehicles->add($wishlistVehicle);
            $wishlistVehicle->setVehicle($this);
        }

        return $this;
    }

    public function removeWishlistVehicle(WishlistVehicle $wishlistVehicle): static
    {
        if ($this->wishlistVehicles->removeElement($wishlistVehicle)) {
            // set the owning side to null (unless already changed)
            if ($wishlistVehicle->getVehicle() === $this) {
                $wishlistVehicle->setVehicle(null);
            }
        }

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
