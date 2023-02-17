<?php

namespace App\Controller;

use App\Repository\BookingsRepository;
use App\service\BookingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SketchController extends AbstractController
{

    public function __construct(
        private BookingService $bookingService,
        private BookingsRepository $bookingsRepository,
    )
    {
    }

    #[Route('/sketch', name: 'app_sketch')]
    public function index(): Response
    {
        return $this->render('sketch/index.html.twig', [
            'controller_name' => 'SketchController',
        ]);
    }

    public function getTodaysBookings(string $date): Response {

        $format = 'Y-m-d';
        $date = \DateTime::createFromFormat($format, $date);

        if (!$date) {
            throw new \Exception('Invalid date format');
        }

        return $this->json($this->bookingsRepository->getBookingsWithBookable($date));
    }

    #[Route('/sketch/{date}', name: 'app_sketch_gettodaysbookings')]
    public function getTodaysBookings2(string $date): Response {

        $format = 'Y-m-d';
        $date = \DateTime::createFromFormat($format, $date);

        if (!$date) {
            throw new \Exception('Invalid date format');
        }

        return $this->json($this->bookingService->retrieveSeatsStatusPerDate($date), 200, [], ['groups' => 'seatmapStatusDTO:read']);
    }
}
