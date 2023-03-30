<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\BookableService;
use App\Service\BookingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookingAPIController extends AbstractController
{
    public function __construct(
        private BookableService $bookableService,
        private BookingService $bookingService,
    )
    {
    }

    #[Route('/api/booking/overview/{date}', name: 'app_booking_retrieve_seatstatus_by_date', methods: ['GET'])]
    public function retrieveSeatStatusByDate(): Response {
        return $this->json(
            $this->bookingService->retrieveSeatStatusByDate(),
            200,
            [],
            ['groups' => 'seatmapStatusDTO:read']);
    }


    #[Route('/api/booking', name: 'app_booking_retrieve_all', methods: ['GET'])]
    public function retrieveAllBookings(): Response
    {
        return $this->json(
            $this->bookableService->getAllBookableAndRelatedCategories(),
            200,
            [],
            ['groups' => ['bookable:read']]
        );
    }


    #[Route('/api/booking/{id}/availability', name: 'app_booking_check_availability_by_date', methods: ['POST'])]
    public function checkAvailabilityByDate(): Response
    {
        return $this->json(
            $this->bookableService->checkBookableAvailabilityByDate(),
            200,
            [],
            ['groups' => ['bookable:read']]
        );
    }
}
