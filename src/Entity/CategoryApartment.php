<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use App\Repository\CategoryApartmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CategoryApartmentRepository::class)]
class CategoryApartment
{
    private const CATEGORYREAD = ['category_apartment:read'];
    private const READ = [
        'category_apartment:read',
        'apartment:read'
    ];

    #[ORM\Id]
    #[ORM\Column]
    #[Groups(self::READ)]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[Groups(self::READ)]
    #[ORM\Column(length: 255, unique: true)]
    private ?string $name = null;

    #[Groups(self::READ)]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $advantage = null;

    #[Groups(self::READ)]
    #[ORM\Column(nullable: true)]
    private ?bool $active = null;

    /**
     * @var Collection<int, Apartment>
     */
    #[Groups(self::CATEGORYREAD)]
    #[ORM\OneToMany(targetEntity: Apartment::class, mappedBy: 'category')]
    private Collection $apartments;

    public function __construct()
    {
        $this->apartments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getAdvantage(): ?string
    {
        return $this->advantage;
    }

    public function setAdvantage(?string $advantage): static
    {
        $this->advantage = $advantage;

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
            $apartment->setCategory($this);
        }

        return $this;
    }

    public function removeApartment(Apartment $apartment): static
    {
        if ($this->apartments->removeElement($apartment)) {
            // set the owning side to null (unless already changed)
            if ($apartment->getCategory() === $this) {
                $apartment->setCategory(null);
            }
        }

        return $this;
    }
}
