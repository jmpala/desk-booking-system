<?php

declare(strict_types=1);


namespace App\dto;

use DateTime;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 *
 */
class SeatmapStatusDTO
{

    public function __construct(
        #[Groups(['seatmapStatusDTO:read'])]
        private DateTime $date,

        /** @var ?Array<\App\dto\BookableInformationDTO> */
        #[Groups(['seatmapStatusDTO:read'])]
        private array $bookables = [],
    )
    {
    }

    /**
     * @return DateTime
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }

    /**
     * @param DateTime $date
     */
    public function setDate(DateTime $date): void
    {
        $this->date = $date;
    }

    /**
     * @return array
     */
    public function getBookables(): array
    {
        return $this->bookables;
    }

    /**
     * @param array $bookables
     */
    public function setBookables(array $bookables): void
    {
        $this->bookables = $bookables;
    }

    public function addBookable(BookableInformationDTO $bookableInformationDTO): void
    {
        $this->bookables[] = $bookableInformationDTO;
    }
}