<?php

namespace App\Controller;

use App\service\BookableService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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


    #[Route('/api/booking/{id}/availability', name: 'app_booking_check_availability_by_date', methods: ['POST'])]
    public function checkAvailabilityByDate(Request $request, int $id): Response
    {
        $data = json_decode(
            $request->getContent(),
            true,
            512,
            JSON_THROW_ON_ERROR
        );
        $from = new \DateTime($data['from']);
        $to = new \DateTime($data['to']);

        // TODO: implement a DTO
        $isAvailable = $this->bookableService->checkAvailabilityByDate($id,$from, $to);

        return $this->json($isAvailable, 200, [], [
            'groups' => ['bookable:read']
        ]);
    }
}
