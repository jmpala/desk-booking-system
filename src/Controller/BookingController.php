<?php

declare(strict_types=1);

namespace App\Controller;

use App\service\BookableService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookingController extends AbstractController
{
    public function __construct(
        private BookableService $bookableService,
    )
    {
    }

    #[Route('/booking/new', name: 'app_booking_new', methods: ['GET'])]
    public function showNewBookingPage(Request $request): Response
    {
        $bookableId = (int) $request->query->get('id');
        $todaysDate = new \DateTime($request->query->get('date')) ?? new \DateTime();

        /* @var array<\App\Entity\Bookable> $allBookables */
        $allBookables = $this->bookableService->getAllBookableAndRelatedCategories();

        /* @var array<\App\Entity\Bookable> $selectedBookable */
        $selectedBookable = array_filter($allBookables, fn ($b) => $b->getId() === $bookableId);

        if (!empty($selectedBookable)) {
            $selectedBookable = $selectedBookable[array_key_first($selectedBookable)];
        } else {
            $selectedBookable = $allBookables[array_key_first($allBookables)];
        }

        return $this->render('booking/new/createBooking.html.twig',[
            'todaysDate' => $todaysDate,
            'selectedBookable' => $selectedBookable,
            'allBookables' => $allBookables
        ]);
    }

    #[Route('/booking/confirm', name: 'app_booking_new_confirm', methods: ['POST'])]
    public function showConfirmBookingPage(Request $request): Response
    {
        $bookableId = (int) $request->request->get('bookable');
        $bookable = $this->bookableService->findById($bookableId);
        $fromDate = $request->request->get('fromDate');
        $toDate = $request->request->get('toDate');

        return $this->render('booking/new/confirmBooking.html.twig', [
            'bookable' => $bookable,
            'fromDate' => $fromDate,
            'toDate' => $toDate
        ]);
    }
}
