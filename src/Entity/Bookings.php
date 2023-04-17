<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\BookingsRepository;
use App\Validator\SelectedDatesAreAvailable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[SelectedDatesAreAvailable]
#[ORM\Entity(repositoryClass: BookingsRepository::class)]
class Bookings
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['bookable:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'bookings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'bookings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Bookable $bookable = null;

    #[ORM\Column(length: 255)]
    private ?string $confirmation = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['bookable:read'])]
    private ?\DateTimeInterface $start_date = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['bookable:read'])]
    private ?\DateTimeInterface $end_date = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getBookable(): ?Bookable
    {
        return $this->bookable;
    }

    public function setBookable(?Bookable $bookable): self
    {
        $this->bookable = $bookable;

        return $this;
    }

    public function getConfirmation(): ?string
    {
        return $this->confirmation;
    }

    public function setConfirmation(string $confirmation): self
    {
        $this->confirmation = $confirmation;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->start_date;
    }

    public function setStartDate(\DateTimeInterface $start_date): self
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->end_date;
    }

    public function setEndDate(\DateTimeInterface $end_date): self
    {
        $this->end_date = $end_date;

        return $this;
    }

}
