<?php

declare(strict_types=1);

namespace App\Controller;

use App\Commons\QueryParameters;
use App\Commons\RequestParameters;
use App\Entity\Bookings;
use App\Repository\UserRepository;
use App\Service\BookableService;
use App\Service\BookingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_TEAMLEAD')]
class PlanningController extends AbstractController
{
    public function __construct(
        private BookingService $bookingService,
        private BookableService $bookableService,
        private UserRepository $userRepository,
    ){}

    #[Route('/planning', name: 'app_planning', methods: ['GET'])]
    public function showUsersSelection(): Response
    {
        return $this->render('planning/user_selection.html.twig', [
            "allUsers" => $this->userRepository->findAll(),
        ]);
    }

    #[Route('/planning/{userId}', name: 'app_planning_showbookingslistforuser', methods: ['GET'])]
    public function showBookingsListForUser(int $userId): Response
    {
        return $this->render('planning/list_user_bookings.html.twig', [
            "all_users" => $this->userRepository->findAll(),
            "selected_user" => $this->userRepository->find($userId),
            'pager' => $this->bookingService->getAllBookingsPagedByUserID($userId),
        ]);
    }

    #[Route('/planning/booking/new/create', name: 'app_planning_createbookingbyuseidpage', methods: ['GET'])]
    public function createBookingByUseIdPage(Request $request): Response
    {
        return $this->render('planning/new/create_booking.html.twig', [
            'selectedUser' => $this->userRepository->find(
                $request->query->getInt(QueryParameters::$USER_ID)
            ),
            'allBookables' => $this->bookableService->getAllBookableAndRelatedCategories()
        ]);
    }

    #[Route('/planning/booking/new/confirm', name: 'app_planning_showconfirmbookingpage', methods: ['POST'])]
    public function showConfirmBookingPage(Request $request): Response
    {
        // TODO: Erase when symfony forms are implemented
        if (!$this->isCsrfTokenValid('newPlanningBooking', $request->request->get('_csrf_token'))) {
            throw new \Exception('Invalid CSRF token');
        }

        return $this->render('planning/new/confirm_booking.html.twig', [
            'selectedUser' => $this->userRepository->find(
                $request->request->getInt(RequestParameters::USER_ID)
            ),
            'bookable' => $this->bookableService->findById(
                $request->request->getInt(RequestParameters::BOOKABLE_ID)
            ),
        ]);
    }

    #[Route('/planning/booking/new/confirmation', name: 'app_planning_processnewbookingandshowconfirmation', methods: ['POST'])]
    public function processNewBookingAndShowConfirmation(Request $request): Response
    {
        // TODO: Erase when symfony forms are implemented
        if (!$this->isCsrfTokenValid('newPlanningBooking', $request->request->get('_csrf_token'))) {
            throw new \Exception('Invalid CSRF token');
        }

        return $this->render('planning/new/created_booking.html.twig', [
            'booking' => $this->bookingService->createNewBookingByUserId(
                $request->request->getInt(RequestParameters::USER_ID),
            )
        ]);
    }

    #[Route('/planning/booking/delete', name: 'app_planning_deletebooking', methods: ['POST'])]
    public function deleteBooking(Request $request): Response
    {
        // TODO: Erase when symfony forms are implemented
        if (!$this->isCsrfTokenValid('deleteBooking', $request->request->get('_csrf_token'))) {
            throw new \Exception('Invalid CSRF token');
        }

        $this->bookingService->deleteBooking(
            $request->request->getInt(RequestParameters::BOOKING_ID)
        );
        return $this->redirectToRoute('app_planning_showbookingslistforuser', [
            'userId' => $request->request->getInt(RequestParameters::USER_ID)]
        );
    }

    #[Route('/planning/booking/edit/{id}', name: 'app_planning_showeditbookingpage', methods: ['GET'])]
    public function showEditBookingPage(Bookings $booking): Response
    {
        return $this->render('planning/edit/edit_booking.html.twig',[
            'booking' => $booking,
            'allBookables' => $this->bookableService->getAllBookableAndRelatedCategories()
        ]);
    }

    #[Route('/planning/booking/edit/confirm', name: 'app_planning_showconfirmeditbookingpage', methods: ['POST'])]
    public function showConfirmEditBookingPage(Request $request): Response
    {
        // TODO: Erase when symfony forms are implemented
        if (!$this->isCsrfTokenValid('editBooking', $request->request->get('_csrf_token'))) {
            throw new \Exception('Invalid CSRF token');
        }

        return $this->render('planning/edit/confirm_edit_booking.html.twig', [
            'booking' => $this->bookingService->findById(
                $request->request->getInt(RequestParameters::BOOKING_ID)
            ),
            'bookable' => $this->bookableService->findById(
                $request->request->getInt(RequestParameters::BOOKABLE_ID)
            ),
        ]);
    }

    #[Route('/planning/booking/edit/confirmation', name: 'app_planning_processeditbookingandshowconfirmation', methods: ['POST'])]
    public function processEditBookingAndShowConfirmation(Request $request): Response
    {
        // TODO: Erase when symfony forms are implemented
        if (!$this->isCsrfTokenValid('confirmEditBooking', $request->request->get('_csrf_token'))) {
            throw new \Exception('Invalid CSRF token');
        }

        return $this->render('planning/edit/edited_booking.html.twig', [
            'booking' => $this->bookingService->editBooking(
                $request->request->getInt(RequestParameters::BOOKING_ID),
                $request->request->getInt(RequestParameters::BOOKABLE_ID),
                new \DateTime($request->request->get(RequestParameters::FROM_DATE)),
                new \DateTime($request->request->get(RequestParameters::TO_DATE)),
            ),
        ]);
    }
}