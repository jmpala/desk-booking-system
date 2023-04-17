<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Bookings;
use App\Form\BookingType;
use App\Form\DeleteBookingType;
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
        private BookingService $bookingService,
    ) {
    }

    #[Route('/booking/new', name: 'app_booking_new', methods: [
        'GET',
        'POST',
    ])]
    public function showCreate(Request $request): Response
    {
        $form = $this->createForm(BookingType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->bookingService->createNewBooking($form->getData());
            $this->addFlash(
                'success',
                'Booking created!',
            );
            return $this->redirectToRoute('app_booking_showallbookings');
        }

        return $this->render(
            'booking/create.html.twig',
            [
                'form' => $form->createView(),
            ],
        );
    }

    #[Route('/booking', name: 'app_booking_showallbookings', methods: ['GET'])]
    public function showAll(): Response
    {
        $deleteForm = $this->createForm(DeleteBookingType::class);

        return $this->render(
            'booking/list.html.twig',
            [
                'pager' => $this->bookingService->getAllBookingsPagedByUserID(
                    $this->getUser()
                        ->getId(),
                ),
                'deleteForm' => $deleteForm->createView(),
            ],
        );
    }

    #[Route('/booking/{id}/delete', name: 'app_booking_delete', methods: ['POST'])]
    public function delete(
        Bookings $booking,
        Request $request,
    ): Response {
        $form = $this->createForm(DeleteBookingType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->bookingService->editBooking($booking);
            $this->addFlash(
                'success',
                'Booking deleted!',
            );
            return $this->redirectToRoute('app_booking_showallbookings');
        }

        $this->addFlash(
            'danger',
            'Booking could not be deleted!',
        );
        return $this->redirectToRoute('app_booking_showallbookings');
    }

    #[Route('/booking/{id}/edit', name: 'app_booking_edit', methods: [
        'GET',
        'POST',
    ])]
    public function showEdit(
        Bookings $booking,
        Request $request,
    ): Response {
        $form = $this->createForm(
            BookingType::class,
            $booking,
        );

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->bookingService->editBooking($booking);
            $this->addFlash(
                'success',
                'Booking updated!',
            );
            return $this->redirectToRoute('app_booking_showallbookings');
        }

        return $this->render(
            'booking/edit.html.twig',
            [
                'form' => $form->createView(),
            ],
        );
    }

    #[Route('/booking/{id}', name: 'app_booking_details', methods: ['GET'])]
    public function showDetails(Bookings $booking): Response
    {
        return $this->render(
            'booking/details.html.twig',
            [
                'booking' => $this->bookingService->findById($booking->getId()),
            ],
        );
    }
}
