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
        return $this->render('booking/new/create_booking.html.twig',[
            'allBookables' => $this->bookableService->getAllBookableAndRelatedCategories(),
        ]);
    }

    #[Route('/booking/confirm', name: 'app_booking_new_confirm', methods: ['POST'])]
    public function showConfirmBookingPage(Request $request): Response
    {
        // TODO: Erase when symfony forms are implemented
        if (!$this->isCsrfTokenValid('newBooking', $request->request->get('_csrf_token'))) {
            throw new \Exception('Invalid CSRF token');
        }

        return $this->render('booking/new/confirm_booking.html.twig', [
            'bookable' => $this->bookableService->findById(
                $request->request->getInt('bookable')
            ),
        ]);
    }

    #[Route('/booking/new/confirmation', name: 'app_booking_process_and_show_confirmation', methods: ['POST'])]
    public function processNewBookingAndShowConfirmation(Request $request): Response
    {
        // TODO: Erase when symfony forms are implemented
        if (!$this->isCsrfTokenValid('newBookingConfirmation', $request->request->get('_csrf_token'))) {
            throw new \Exception('Invalid CSRF token');
        }

        return $this->render('booking/new/created_booking.html.twig', [
            'booking' => $this->bookingService->createNewBooking(
                (int) $request->request->get('bookable'),
                new \DateTime($request->request->get('fromDate')),
                new \DateTime($request->request->get('toDate'))
            )
        ]);
    }

    #[Route('/booking/all', name: 'app_booking_showallbookings', methods: ['GET'])]
    public function showAllBookings(): Response
    {
        $userId = $this->getUser()->getId();
        return $this->render('booking/all_bookings.html.twig', [
            'pager' => $this->bookingService->getAllBookingsPagedByUserID($userId),
            'hasBookingsMade' => $this->bookingService->countAllBookingsByUserID($userId) >= 1,
            'hasOngoingBookings' => $this->bookingService->countAllNonPastBookingsByUserID($userId) >= 1
        ]);
    }

    #[Route('/booking/delete', name: 'app_booking_delete', methods: ['POST'])]
    public function deleteBooking(Request $request): Response
    {
        // TODO: Erase when symfony forms are implemented
        if (!$this->isCsrfTokenValid('deleteBooking', $request->request->get('_csrf_token'))) {
            throw new \Exception('Invalid CSRF token');
        }

        $this->bookingService->deleteBooking(
            (int) $request->request->get('bookingId')
        );
        return $this->redirectToRoute('app_booking_showallbookings');
    }


    #[Route('/booking/edit/{id}', name: 'app_booking_edit', methods: ['GET'])]
    public function showEditBookingPage(Bookings $booking): Response
    {
        return $this->render('booking/edit/edit_booking.html.twig',[
            'booking' => $this->bookingService->findById($booking->getId()),
            'allBookables' => $this->bookableService->getAllBookableAndRelatedCategories(),
        ]);
    }

    #[Route('/booking/edit/confirm', name: 'app_booking_showconfirmeditbookingpage', methods: ['POST'])]
    public function showConfirmEditBookingPage(Request $request): Response
    {
        // TODO: Erase when symfony forms are implemented
        if (!$this->isCsrfTokenValid('editBooking', $request->request->get('_csrf_token'))) {
            throw new \Exception('Invalid CSRF token');
        }

        return $this->render('booking/edit/confirm_edit_booking.html.twig', [
            'bookable' => $this->bookableService->findById(
                $request->request->getInt('bookable')
            ),
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

        return $this->render('booking/edit/edited_booking.html.twig', [
            'booking' => $editerBooking
        ]);
    }
}
