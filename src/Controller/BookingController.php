<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\BookableRepository;
use App\Repository\BookingsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class BookingController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private BookingsRepository $bookingsRepository,
        private BookableRepository $bookableRepository,
    )
    {
    }

    #[Route('/', name: 'app_booking', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('booking/index.html.twig', [
            'controller_name' => 'BookingController',
        ]);
    }

    #[Route('/api/booking', name: 'app_booking_retrieve_all', methods: ['GET'])]
    public function retrieveAllBookings(): Response
    {
        $res = $this->bookableRepository->getAllBookableAndRelatedCategories();
        return $this->json($res, 200, [], [
            'groups' => ['bookable:read']
        ]);
    }


}
