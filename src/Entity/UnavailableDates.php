<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UnavailableDatesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UnavailableDatesRepository::class)]
class UnavailableDates
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['bookable:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'unavailableDates')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Bookable $bookable = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['bookable:read'])]
    private ?\DateTimeInterface $start_date = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['bookable:read'])]
    private ?\DateTimeInterface $end_date = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['bookable:read'])]
    private ?string $notes = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(string $notes): self
    {
        $this->notes = $notes;

        return $this;
    }
}
