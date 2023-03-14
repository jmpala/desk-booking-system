<?php

declare(strict_types=1);

namespace App\Controller;

use App\service\BookableService;
use App\service\BookingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookingController extends AbstractController
{
    public function __construct(
        private BookableService $bookableService,
        private BookingService $bookingService
    )
    {
    }

    #[Route('/booking/new', name: 'app_booking_new', methods: ['GET'])]
    public function showNewBookingPage(Request $request): Response
    {
        $bookableId = (int) $request->query->get('id');
        $todaysDate = $request->query->get('date') ?
            new \DateTime($request->query->get('date')) :
            new \DateTime();

        /* @var array<\App\Entity\Bookable> $allBookables */
        $allBookables = $this->bookableService->getAllBookableAndRelatedCategories();

        /* @var array<\App\Entity\Bookable> $selectedBookable */
        $selectedBookable = array_filter($allBookables, fn ($b) => $b->getId() === $bookableId);

        if (!empty($selectedBookable)) {
            $selectedBookable = $selectedBookable[array_key_first($selectedBookable)];
        } else {
            $selectedBookable = $allBookables[array_key_first($allBookables)];
        }

        $errors = $request->getSession()->getFlashBag()->get('error');

        return $this->render('booking/new/createBooking.html.twig',[
            'todaysDate' => $todaysDate,
            'selectedBookable' => $selectedBookable,
            'allBookables' => $allBookables,
            'errors' => $errors
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

    #[Route('/booking/new/confirmation', name: 'app_booking_process_and_show_confirmation', methods: ['POST'])]
    public function processNewBookingAndShowConfirmation(Request $request): Response
    {
        $bookableId = (int) $request->request->get('bookable');
        $fromDate = new \DateTime($request->request->get('fromDate'));
        $toDate = new \DateTime($request->request->get('toDate'));

        $createdBooking = $this->bookingService->createNewBooking($bookableId, $fromDate, $toDate);

        return $this->render('booking/new/createdBooking.html.twig', [
            'booking' => $createdBooking
        ]);
    }

    #[Route('/booking/all', name: 'app_booking_showallbookings', methods: ['GET'])]
    public function showAllBookings(Request $request): Response
    {
        $pageNum = (int) $request->query->get('page') ?: 1;
        $col = $request->query->get('col') ?: 'resource';
        $order = $request->query->get('ord') ?: 'asc';
        $past = $request->query->get('past') ?: 'false';
        $user = $this->getUser();

        $hasBookings = $this->bookingService->countAllBookingsByUserID($user->getId()) > 0;
        $hasOnlyPastBookings = false;

        $pagerFanta = $this->bookingService->getAllBookingsByID($user->getId(), $col, $order, $past);
        if ($hasBookings
            && $pagerFanta->getNbResults() === 0) {
            $past = 'true';
            $hasOnlyPastBookings = true;
            $pagerFanta = $this->bookingService->getAllBookingsByID($user->getId(), $col, $order, $past);
        }

        $pagerFanta->setMaxPerPage(10);
        $pagerFanta->setCurrentPage($pageNum);

        return $this->render('booking/allBookings.html.twig', [
            'pager' => $pagerFanta,
            'selectedCol' => $col,
            'selectedOder' => $order,
            'todaysDate' => new \DateTime((new \DateTime())->format('Y-m-d')),
            'pastBookings' => $past,
            'hasBookings' => $hasBookings,
            'hasOnlyPastBookings' => $hasOnlyPastBookings
        ]);
    }

    #[Route('/booking/delete', name: 'app_booking_delete', methods: ['POST'])]
    public function deleteBooking(Request $request): Response
    {
        if ($this->isCsrfTokenValid('deleteBooking', $request->request->get('_csrf_token')) === false) {
            throw new \Exception('Invalid CSRF token');
        }

        $bookingId = (int) $request->request->get('bookingId');
        $this->bookingService->deleteBooking($bookingId);
        return $this->redirectToRoute('app_booking_showallbookings');
    }
}
