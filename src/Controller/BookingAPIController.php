<?php

namespace App\Controller;

use App\service\BookableService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookingAPIController extends AbstractController
{
    public function __construct(
        private BookableService $bookableService,
    )
    {
    }

    #[Route('/api/booking', name: 'app_booking_retrieve_all', methods: ['GET'])]
    public function retrieveAllBookings(): Response
    {
        $res = $this->bookableService->getAllBookableAndRelatedCategories();
        return $this->json($res, 200, [], [
            'groups' => ['bookable:read']
        ]);
    }
}
