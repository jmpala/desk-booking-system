<?php

declare(strict_types=1);


namespace App\dto;

use App\Entity\Bookable;
use App\Entity\Bookings;
use App\Entity\UnavailableDates;
use DateTime;
use Symfony\Component\Serializer\Annotation\Groups;

class BookableInformationDTO
{
    #[Groups(['seatmapStatusDTO:read'])]
    private ?int $shapeX = null;

    #[Groups(['seatmapStatusDTO:read'])]
    private ?int $shapeY = null;

    #[Groups(['seatmapStatusDTO:read'])]
    private ?int $shapeWidth = null;

    #[Groups(['seatmapStatusDTO:read'])]
    private ?int $shapeHeight = null;

    #[Groups(['seatmapStatusDTO:read'])]
    private ?int $bookableId = null;

    #[Groups(['seatmapStatusDTO:read'])]
    private ?string $bookableCode = null;

    #[Groups(['seatmapStatusDTO:read'])]
    private ?string $bookableDescription = null;

    #[Groups(['seatmapStatusDTO:read'])]
    private ?string $bookableCategory = null;

    #[Groups(['seatmapStatusDTO:read'])]
    private ?string $userName = null;

    #[Groups(['seatmapStatusDTO:read'])]
    private bool $isBooked = false;

    #[Groups(['seatmapStatusDTO:read'])]
    private bool $isBookedByLoggedUser = false;

    #[Groups(['seatmapStatusDTO:read'])]
    private ?int $bookingId = null;

    #[Groups(['seatmapStatusDTO:read'])]
    private ?string $bookingConfirmationCode = null;

    #[Groups(['seatmapStatusDTO:read'])]
    private ?DateTime $bookingStartDate = null;

    #[Groups(['seatmapStatusDTO:read'])]
    private ?DateTime $bookingEndDate = null;

    #[Groups(['seatmapStatusDTO:read'])]
    private ?DateTime $bookingCreatedAt = null;

    #[Groups(['seatmapStatusDTO:read'])]
    private ?bool $isDisabled = false;

    #[Groups(['seatmapStatusDTO:read'])]
    private ?DateTime $disabledFromDate = null;

    #[Groups(['seatmapStatusDTO:read'])]
    private ?DateTime $disabledToDate = null;

    #[Groups(['seatmapStatusDTO:read'])]
    private ?string $disabledNotes = null;


    public function __construct(
        Bookable $bookable,
    )
    {
        $this->populateWithBookableEntity($bookable);
    }

    public function populateWithBookingEntity(Bookings $entity): static {
        $this->isBooked = true;
        $this->bookingId = $entity->getId();
        $this->bookingConfirmationCode = $entity->getConfirmation();
        $this->bookingStartDate = DateTime::createFromInterface($entity->getStartDate());
        $this->bookingEndDate = DateTime::createFromInterface($entity->getEndDate());
        $this->userName = $entity->getUser()->getUserIdentifier();
        return $this;
    }

    public function populateWithBookableEntity(Bookable $bookable): static {
        $this->bookableId = $bookable->getId();
        $this->bookableCode = $bookable->getCode();
        $this->bookableDescription = $bookable->getDescription();
        $this->bookableCategory = $bookable->getCategory()->getCode();
        $this->shapeX = $bookable->getPosX();
        $this->shapeY = $bookable->getPosY();
        $this->shapeWidth = $bookable->getWidth();
        $this->shapeHeight = $bookable->getHeight();
        return $this;
    }

    public function populateWithUnavailableDatesEntity(UnavailableDates $unavailableDates): static {
        $this->isDisabled = true;
        $this->disabledFromDate = DateTime::createFromInterface($unavailableDates->getStartDate());
        $this->disabledToDate = DateTime::createFromInterface($unavailableDates->getEndDate());
        $this->disabledNotes = $unavailableDates->getNotes();
        return $this;
    }

    /**
     * @return int|null
     */
    public function getShapeX(): ?int
    {
        return $this->shapeX;
    }

    /**
     * @param int|null $shapeX
     */
    public function setShapeX(?int $shapeX): void
    {
        $this->shapeX = $shapeX;
    }

    /**
     * @return int|null
     */
    public function getShapeY(): ?int
    {
        return $this->shapeY;
    }

    /**
     * @param int|null $shapeY
     */
    public function setShapeY(?int $shapeY): void
    {
        $this->shapeY = $shapeY;
    }

    /**
     * @return int|null
     */
    public function getShapeWidth(): ?int
    {
        return $this->shapeWidth;
    }

    /**
     * @param int|null $shapeWidth
     */
    public function setShapeWidth(?int $shapeWidth): void
    {
        $this->shapeWidth = $shapeWidth;
    }

    /**
     * @return int|null
     */
    public function getShapeHeight(): ?int
    {
        return $this->shapeHeight;
    }

    /**
     * @param int|null $shapeHeight
     */
    public function setShapeHeight(?int $shapeHeight): void
    {
        $this->shapeHeight = $shapeHeight;
    }

    /**
     * @return int|null
     */
    public function getBookableId(): ?int
    {
        return $this->bookableId;
    }

    /**
     * @param int|null $bookableId
     */
    public function setBookableId(?int $bookableId): void
    {
        $this->bookableId = $bookableId;
    }

    /**
     * @return string|null
     */
    public function getBookableCode(): ?string
    {
        return $this->bookableCode;
    }

    /**
     * @param string|null $bookableCode
     */
    public function setBookableCode(?string $bookableCode): void
    {
        $this->bookableCode = $bookableCode;
    }

    /**
     * @return string|null
     */
    public function getBookableDescription(): ?string
    {
        return $this->bookableDescription;
    }

    /**
     * @param string|null $bookableDescription
     */
    public function setBookableDescription(?string $bookableDescription): void
    {
        $this->bookableDescription = $bookableDescription;
    }

    /**
     * @return string|null
     */
    public function getBookableCategory(): ?string
    {
        return $this->bookableCategory;
    }

    /**
     * @param string|null $bookableCategory
     */
    public function setBookableCategory(?string $bookableCategory): void
    {
        $this->bookableCategory = $bookableCategory;
    }

    /**
     * @return string|null
     */
    public function getUserName(): ?string
    {
        return $this->userName;
    }

    /**
     * @param string|null $userName
     */
    public function setUserName(?string $userName): void
    {
        $this->userName = $userName;
    }

    /**
     * @return bool
     */
    public function isBooked(): bool
    {
        return $this->isBooked;
    }

    /**
     * @param bool $isBooked
     */
    public function setIsBooked(bool $isBooked): void
    {
        $this->isBooked = $isBooked;
    }

    /**
     * @return int|null
     */
    public function getBookingId(): ?int
    {
        return $this->bookingId;
    }

    /**
     * @param int|null $bookingId
     */
    public function setBookingId(?int $bookingId): void
    {
        $this->bookingId = $bookingId;
    }

    /**
     * @return string|null
     */
    public function getBookingConfirmationCode(): ?string
    {
        return $this->bookingConfirmationCode;
    }

    /**
     * @param string|null $bookingConfirmationCode
     */
    public function setBookingConfirmationCode(?string $bookingConfirmationCode): void
    {
        $this->bookingConfirmationCode = $bookingConfirmationCode;
    }

    /**
     * @return \DateTime|null
     */
    public function getBookingStartDate(): ?DateTime
    {
        return $this->bookingStartDate;
    }

    /**
     * @param \DateTime|null $bookingStartDate
     */
    public function setBookingStartDate(?DateTime $bookingStartDate): void
    {
        $this->bookingStartDate = $bookingStartDate;
    }

    /**
     * @return \DateTime|null
     */
    public function getBookingEndDate(): ?DateTime
    {
        return $this->bookingEndDate;
    }

    /**
     * @param \DateTime|null $bookingEndDate
     */
    public function setBookingEndDate(?DateTime $bookingEndDate): void
    {
        $this->bookingEndDate = $bookingEndDate;
    }

    /**
     * @return \DateTime|null
     */
    public function getBookingCreatedAt(): ?DateTime
    {
        return $this->bookingCreatedAt;
    }

    /**
     * @param \DateTime|null $bookingCreatedAt
     */
    public function setBookingCreatedAt(?DateTime $bookingCreatedAt): void
    {
        $this->bookingCreatedAt = $bookingCreatedAt;
    }

    /**
     * @return bool|null
     */
    public function getIsDisabled(): ?bool
    {
        return $this->isDisabled;
    }

    /**
     * @param bool|null $isDisabled
     */
    public function setIsDisabled(?bool $isDisabled): void
    {
        $this->isDisabled = $isDisabled;
    }

    /**
     * @return \DateTime|null
     */
    public function getDisabledFromDate(): ?DateTime
    {
        return $this->disabledFromDate;
    }

    /**
     * @param \DateTime|null $disabledFromDate
     */
    public function setDisabledFromDate(?DateTime $disabledFromDate): void
    {
        $this->disabledFromDate = $disabledFromDate;
    }

    /**
     * @return \DateTime|null
     */
    public function getDisabledToDate(): ?DateTime
    {
        return $this->disabledToDate;
    }

    /**
     * @param \DateTime|null $disabledToDate
     */
    public function setDisabledToDate(?DateTime $disabledToDate): void
    {
        $this->disabledToDate = $disabledToDate;
    }

    /**
     * @return string|null
     */
    public function getDisabledNotes(): ?string
    {
        return $this->disabledNotes;
    }

    /**
     * @param string|null $disabledNotes
     */
    public function setDisabledNotes(?string $disabledNotes): void
    {
        $this->disabledNotes = $disabledNotes;
    }

    /**
     * @return bool
     */
    public function isBookedByLoggedUser(): bool
    {
        return $this->isBookedByLoggedUser;
    }

    /**
     * @param bool $isBookedByLoggedUser
     */
    public function setIsBookedByLoggedUser(bool $isBookedByLoggedUser): void
    {
        $this->isBookedByLoggedUser = $isBookedByLoggedUser;
    }

}