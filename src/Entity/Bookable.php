<?php

namespace App\Entity;

use App\Repository\BookableRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: BookableRepository::class)]
class Bookable
{
    use TimestampableEntity;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['bookable:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['bookable:read'])]
    private ?string $code = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['bookable:read'])]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(['bookable:read'])]
    private ?int $pos_x = null;

    #[ORM\Column]
    #[Groups(['bookable:read'])]
    private ?int $pos_y = null;

    #[ORM\Column]
    #[Groups(['bookable:read'])]
    private ?int $width = null;

    #[ORM\Column]
    #[Groups(['bookable:read'])]
    private ?int $height = null;

    #[ORM\OneToMany(mappedBy: 'bookable_id', targetEntity: Bookings::class)]
    private Collection $bookings;

    #[ORM\OneToMany(mappedBy: 'bookable_id', targetEntity: UnavailableDates::class)]
    private Collection $unavailableDates;

    #[ORM\ManyToOne(inversedBy: 'bookables')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category_id = null;

    public function __construct()
    {
        $this->bookings = new ArrayCollection();
        $this->unavailableDates = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPosX(): ?int
    {
        return $this->pos_x;
    }

    public function setPosX(int $pos_x): self
    {
        $this->pos_x = $pos_x;

        return $this;
    }

    public function getPosY(): ?int
    {
        return $this->pos_y;
    }

    public function setPosY(int $pos_y): self
    {
        $this->pos_y = $pos_y;

        return $this;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(int $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(int $height): self
    {
        $this->height = $height;

        return $this;
    }

    /**
     * @return Collection<int, Bookings>
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBooking(Bookings $booking): self
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings->add($booking);
            $booking->setBookableId($this);
        }

        return $this;
    }

    public function removeBooking(Bookings $booking): self
    {
        if ($this->bookings->removeElement($booking)) {
            // set the owning side to null (unless already changed)
            if ($booking->getBookableId() === $this) {
                $booking->setBookableId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UnavailableDates>
     */
    public function getUnavailableDates(): Collection
    {
        return $this->unavailableDates;
    }

    public function addUnavailableDate(UnavailableDates $unavailableDate): self
    {
        if (!$this->unavailableDates->contains($unavailableDate)) {
            $this->unavailableDates->add($unavailableDate);
            $unavailableDate->setBookableId($this);
        }

        return $this;
    }

    public function removeUnavailableDate(UnavailableDates $unavailableDate): self
    {
        if ($this->unavailableDates->removeElement($unavailableDate)) {
            // set the owning side to null (unless already changed)
            if ($unavailableDate->getBookableId() === $this) {
                $unavailableDate->setBookableId(null);
            }
        }

        return $this;
    }

    public function getCategoryId(): ?Category
    {
        return $this->category_id;
    }

    public function setCategoryId(?Category $category_id): self
    {
        $this->category_id = $category_id;

        return $this;
    }
}
