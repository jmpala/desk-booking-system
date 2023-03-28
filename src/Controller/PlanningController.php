<?php

declare(strict_types=1);

namespace App\Controller;

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
    public function showPlanningPage(Request $request): Response
    {
        $userId = $request->query->getInt('userid', -1);
        $isUserSelected = $userId !== -1;
        $allUsers = $this->userRepository->findAll();

        if (!$isUserSelected) {
            return $this->render('planning/planning.html.twig', [
                "isUserSelected" => $isUserSelected,
                "allUsers" => $allUsers
            ]);
        }

        $selectedUser = $this->userRepository->find($userId);
        $hasOngoingBookings = $this->bookingService->countAllNonPastBookingsByUserID($userId) >= 1;

        $pageNum = $request->query->getInt('page', 1);
        $col = $request->query->getAlpha('col', 'resource');
        $order = $request->query->getAlpha('ord', 'asc');
        $past = $hasOngoingBookings ? $request->query->get('past', 'false') : 'true';

        $pagerFanta = $this->bookingService->getAllBookingsPagedByUserID($pageNum, $col, $order, $past, $userId);

        return $this->render('planning/planning.html.twig', [
            "isUserSelected" => $isUserSelected,
            "allUsers" => $allUsers,
            "userid" => $userId,
            "selectedUser" => $selectedUser,
            'pager' => $pagerFanta,
            'selectedCol' => $col,
            'selectedOder' => $order,
            'todaysDate' => new \DateTime((new \DateTime())->format('Y-m-d')),
            'pastBookings' => $past,
            'hasBookingsMade' => $this->bookingService->countAllBookingsByUserID($userId) > 0,
            'hasOngoingBookings' => $hasOngoingBookings
        ]);
    }

    #[Route('/planning/booking/new/create', name: 'app_planning_createbookingbyuseidpage', methods: ['GET'])]
    public function createBookingByUseIdPage(Request $request): Response
    {
        $userid = (int) $request->query->get('userid') ?: null;

        $selectedUser = $this->userRepository->find($userid);

        /* @var array<\App\Entity\Bookable> $allBookables */
        $allBookables = $this->bookableService->getAllBookableAndRelatedCategories();

        /* @var array<\App\Entity\Bookable> $selectedBookable */
        $selectedBookable = $allBookables[array_key_first($allBookables)];

        $errors = $request->getSession()->getFlashBag()->get('error');

        return $this->render('planning/new/create_booking.html.twig', [
            'userid' => $userid,
            'selectedUser' => $selectedUser,
            'todaysDate' => new \DateTime((new \DateTime())->format('Y-m-d')),
            'selectedBookable' => $selectedBookable,
            'allBookables' => $allBookables,
            'errors' => $errors
        ]);
    }

    #[Route('/planning/booking/new/confirm', name: 'app_planning_showconfirmbookingpage', methods: ['POST'])]
    public function showConfirmBookingPage(Request $request): Response
    {
        if (!$this->isCsrfTokenValid('newPlanningBooking', $request->request->get('_csrf_token'))) {
            throw new \Exception('Invalid CSRF token');
        }

        $userid = (int) $request->request->get('userid');
        $selectedUser = $this->userRepository->find($userid);
        $bookableId = (int) $request->request->get('bookable');
        $bookable = $this->bookableService->findById($bookableId);
        $fromDate = $request->request->get('fromDate');
        $toDate = $request->request->get('toDate');

        return $this->render('planning/new/confirm_booking.html.twig', [
            'selectedUser' => $selectedUser,
            'userid' => $userid,
            'bookable' => $bookable,
            'fromDate' => $fromDate,
            'toDate' => $toDate
        ]);
    }

    #[Route('/planning/booking/new/confirmation', name: 'app_planning_processnewbookingandshowconfirmation', methods: ['POST'])]
    public function processNewBookingAndShowConfirmation(Request $request): Response
    {
        if (!$this->isCsrfTokenValid('newPlanningBooking', $request->request->get('_csrf_token'))) {
            throw new \Exception('Invalid CSRF token');
        }

        $userid = (int) $request->request->get('userid');
        $selectedUser = $this->userRepository->find($userid);
        $bookableId = (int) $request->request->get('bookable');
        $fromDate = new \DateTime($request->request->get('fromDate'));
        $toDate = new \DateTime($request->request->get('toDate'));

        $createdBooking = $this->bookingService->createNewBookingByUserId($bookableId, $fromDate, $toDate, $userid);

        return $this->render('planning/new/created_booking.html.twig', [
            'booking' => $createdBooking,
            'selectedUser' => $selectedUser,
        ]);
    }

    #[Route('/planning/booking/delete', name: 'app_planning_deletebooking', methods: ['POST'])]
    public function deleteBooking(Request $request): Response
    {
        if (!$this->isCsrfTokenValid('deleteBooking', $request->request->get('_csrf_token'))) {
            throw new \Exception('Invalid CSRF token');
        }
        $userid = (int) $request->request->get('userid');

        $bookingId = (int) $request->request->get('bookingId');
        $this->bookingService->deleteBooking($bookingId);
        return $this->redirectToRoute('app_planning', ['userid' => $userid]);
    }

    #[Route('/planning/booking/edit/{id}', name: 'app_planning_showeditbookingpage', methods: ['GET'])]
    public function showEditBookingPage(Request $request, Bookings $booking): Response
    {
        $userid = (int) $request->query->get('userid');
        $selectedUser = $this->userRepository->find($userid);
        $bookableId = $booking->getBookable()->getId();
        $todaysDate = $request->query->get('date') ?
            new \DateTime($request->query->get('date')) :
            new \DateTime();

        /* @var array<\App\Entity\Bookable> $allBookables */
        $allBookables = $this->bookableService->getAllBookableAndRelatedCategories();

        $selectedBookable = array_filter($allBookables, static fn ($b) => $b->getId() === $bookableId);

        if (!empty($selectedBookable)) {
            $selectedBookable = $selectedBookable[array_key_first($selectedBookable)];
        } else {
            $selectedBookable = $allBookables[array_key_first($allBookables)];
        }

        $errors = $request->getSession()->getFlashBag()->get('error');

        return $this->render('planning/edit/edit_booking.html.twig',[
            'selectedUser' => $selectedUser,
            'booking' => $booking,
            'todaysDate' => $todaysDate,
            'selectedBookable' => $selectedBookable,
            'allBookables' => $allBookables,
            'errors' => $errors
        ]);
    }

    #[Route('/planning/booking/edit/confirm', name: 'app_planning_showconfirmeditbookingpage', methods: ['POST'])]
    public function showConfirmEditBookingPage(Request $request): Response
    {
        if (!$this->isCsrfTokenValid('editBooking', $request->request->get('_csrf_token'))) {
            throw new \Exception('Invalid CSRF token');
        }

        $userid = (int) $request->request->get('userid');
        $selectedUser = $this->userRepository->find($userid);
        $bookingId = (int) $request->request->get('bookingId');
        $booking = $this->bookingService->findById($bookingId);
        $bookableId = (int) $request->request->get('bookable');
        $bookable = $this->bookableService->findById($bookableId);
        $fromDate = $request->request->get('fromDate');
        $toDate = $request->request->get('toDate');

        return $this->render('planning/edit/confirm_edit_booking.html.twig', [
            'selectedUser' => $selectedUser,
            'booking' => $booking,
            'bookable' => $bookable,
            'fromDate' => $fromDate,
            'toDate' => $toDate
        ]);
    }

    #[Route('/planning/booking/edit/confirmation', name: 'app_planning_processeditbookingandshowconfirmation', methods: ['POST'])]
    public function processEditBookingAndShowConfirmation(Request $request): Response
    {
        if (!$this->isCsrfTokenValid('confirmEditBooking', $request->request->get('_csrf_token'))) {
            throw new \Exception('Invalid CSRF token');
        }

        $userid = (int) $request->request->get('userid');
        $selectedUser = $this->userRepository->find($userid);
        $bookingId = (int) $request->request->get('bookingId');
        $bookableId = (int) $request->request->get('bookable');
        $fromDate = new \DateTime($request->request->get('fromDate'));
        $toDate = new \DateTime($request->request->get('toDate'));

        $editerBooking = $this->bookingService->editBooking($bookingId, $bookableId, $fromDate, $toDate);

        return $this->render('planning/edit/edited_booking.html.twig', [
            'selectedUser' => $selectedUser,
            'booking' => $editerBooking
        ]);
    }
}