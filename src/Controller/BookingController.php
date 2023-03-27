<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Bookings;
use App\Service\BookableService;
use App\Service\BookingService;
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
        if (!$this->isCsrfTokenValid('newBooking', $request->request->get('_csrf_token'))) {
            throw new \Exception('Invalid CSRF token');
        }

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
        if (!$this->isCsrfTokenValid('newBookingConfirmation', $request->request->get('_csrf_token'))) {
            throw new \Exception('Invalid CSRF token');
        }

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
        $userId = $this->getUser()->getId();
        $hasOngoingBookings = $this->bookingService->countAllNonPastBookingsByUserID($userId) >= 1;

        $pageNum = $request->query->getInt('page', 1);
        $col = $request->query->getAlpha('col', 'resource');
        $order = $request->query->getAlpha('ord', 'asc');
        $past = $hasOngoingBookings ? $request->query->get('past', 'false') : 'true';

        $pagerfanta = $this->bookingService->getAllBookingsPagedByUserID($pageNum, $col, $order, $past, $userId);

        if ($pagerfanta->getCurrentPage() < $pageNum) {
            return $this->redirectToRoute('app_booking_showallbookings', [
                'page' => $pagerfanta->getCurrentPage(),
                'col' => $col,
                'ord' => $order,
                'past' => $past,
            ]);
        }

        return $this->render('booking/allBookings.html.twig', [
            'pager' => $pagerfanta,
            'selectedCol' => $col,
            'selectedOder' => $order,
            'todaysDate' => new \DateTime((new \DateTime())->format('Y-m-d')),
            'pastBookings' => $past,
            'hasBookingsMade' => $this->bookingService->countAllBookingsByUserID($userId) > 0,
            'hasOngoingBookings' => $hasOngoingBookings
        ]);
    }

    #[Route('/booking/delete', name: 'app_booking_delete', methods: ['POST'])]
    public function deleteBooking(Request $request): Response
    {
        if (!$this->isCsrfTokenValid('deleteBooking', $request->request->get('_csrf_token'))) {
            throw new \Exception('Invalid CSRF token');
        }

        $bookingId = (int) $request->request->get('bookingId');
        $this->bookingService->deleteBooking($bookingId);
        return $this->redirectToRoute('app_booking_showallbookings');
    }


    #[Route('/booking/edit/{id}', name: 'app_booking_edit', methods: ['GET'])]
    public function showEditBookingPage(Request $request, Bookings $booking): Response
    {
        $bookableId = $booking->getBookable()->getId();
        $todaysDate = $request->query->get('date') ?
            new \DateTime($request->query->get('date')) :
            new \DateTime();

        /* @var array<\App\Entity\Bookable> $allBookables */
        $allBookables = $this->bookableService->getAllBookableAndRelatedCategories();

        $selectedBookable = array_filter($allBookables, fn ($b) => $b->getId() === $bookableId);

        if (!empty($selectedBookable)) {
            $selectedBookable = $selectedBookable[array_key_first($selectedBookable)];
        } else {
            $selectedBookable = $allBookables[array_key_first($allBookables)];
        }

        $errors = $request->getSession()->getFlashBag()->get('error');

        return $this->render('booking/edit/editBooking.html.twig',[
            'booking' => $booking,
            'todaysDate' => $todaysDate,
            'selectedBookable' => $selectedBookable,
            'allBookables' => $allBookables,
            'errors' => $errors
        ]);
    }

    #[Route('/booking/edit/confirm', name: 'app_booking_showconfirmeditbookingpage', methods: ['POST'])]
    public function showConfirmEditBookingPage(Request $request): Response
    {
        if (!$this->isCsrfTokenValid('editBooking', $request->request->get('_csrf_token'))) {
            throw new \Exception('Invalid CSRF token');
        }

        $bookingId = (int) $request->request->get('bookingId');
        $booking = $this->bookingService->findById($bookingId);
        $bookableId = (int) $request->request->get('bookable');
        $bookable = $this->bookableService->findById($bookableId);
        $fromDate = $request->request->get('fromDate');
        $toDate = $request->request->get('toDate');

        return $this->render('booking/edit/confirmEditBooking.html.twig', [
            'booking' => $booking,
            'bookable' => $bookable,
            'fromDate' => $fromDate,
            'toDate' => $toDate
        ]);
    }

    #[Route('/booking/edit/confirmation', name: 'app_booking_processeditbookingandshowconfirmation', methods: ['POST'])]
    public function processEditBookingAndShowConfirmation(Request $request): Response
    {
        if (!$this->isCsrfTokenValid('confirmEditBooking', $request->request->get('_csrf_token'))) {
            throw new \Exception('Invalid CSRF token');
        }

        $bookingId = (int) $request->request->get('bookingId');
        $bookableId = (int) $request->request->get('bookable');
        $fromDate = new \DateTime($request->request->get('fromDate'));
        $toDate = new \DateTime($request->request->get('toDate'));

        $editerBooking = $this->bookingService->editBooking($bookingId, $bookableId, $fromDate, $toDate);

        return $this->render('booking/edit/editedBooking.html.twig', [
            'booking' => $editerBooking
        ]);
    }
}
