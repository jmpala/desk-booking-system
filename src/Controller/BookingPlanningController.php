<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Bookings;
use App\Entity\User;
use App\Form\BookingType;
use App\Form\DeleteBookingType;
use App\Form\Model\UserSelectionModel;
use App\Form\UserSelectionType;
use App\Service\BookingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_TEAMLEAD')]
class BookingPlanningController extends AbstractController
{
    public function __construct(
        private BookingService $bookingService,
    )
    {
    }

    #[Route('/planning', name: 'app_planning', methods: ['GET', 'POST'])]
    public function showUserSelection(Request $request): Response
    {
        $form = $this->createForm(UserSelectionType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $userModel = $form->getData();

            return $this->redirectToRoute('app_planning_showbookingslistforuser', [
                'id' => $userModel->user->getId()
            ]);
        }

        return $this->render('planning/user_selection.html.twig', [
            "user_selection_form" => $form->createView(),
        ]);
    }

    #[Route('/planning/{id}', name: 'app_planning_showbookingslistforuser', methods: ['GET', 'POST'])]
    public function showBookingsForUser(User $user, Request $request): Response
    {
        $userModel = new UserSelectionModel();
        $userModel->user = $user;
        $selectUserForm = $this->createForm(UserSelectionType::class, $userModel);
        $deleteForm = $this->createForm(DeleteBookingType::class);

        $selectUserForm->handleRequest($request);
        if ($selectUserForm->isSubmitted() && $selectUserForm->isValid()) {
            $userModel = $selectUserForm->getData();

            return $this->redirectToRoute('app_planning_showbookingslistforuser', [
                'id' => $userModel->user->getId()
            ]);
        }

        return $this->render('planning/list_user_bookings.html.twig', [
            'user_selection_form' => $selectUserForm->createView(),
            'delete_form' => $deleteForm->createView(),
            "selected_user" => $user,
            'pager' => $this->bookingService->getAllBookingsPagedByUserID($user->getId()),
        ]);
    }

    #[Route('/planning/{id}/create', name: 'app_bookingplanning_showcreateforuser', methods: ['GET', 'POST'])]
    public function showCreateForUser(User $user, Request $request): Response
    {
        $form = $this->createForm(BookingType::class, null, [
            'planning_mode' => true,
            'user' => $user,
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $booking = $form->getData();
            $booking->setUser($user);

            $this->bookingService->createNewBookingByUserId($booking, $user->getId());
            $this->addFlash('success', 'Booking created');
            return $this->redirectToRoute('app_planning_showbookingslistforuser', [
                'id' => $user->getId()
            ]);
        }

        return $this->render('planning/create.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    #[Route('/planning/{userId}/{id}/delete', name: 'app_planning_deletebooking', methods: ['POST'])]
    public function deleteForUser(
        Bookings $booking,
        ?int     $userId = -1,
    ): Response
    {
        $this->bookingService->deleteBooking($booking);
        $this->addFlash('success', 'Booking deleted');
        return $this->redirectToRoute('app_planning_showbookingslistforuser', [
            'id' => $userId
        ]);
    }

    #[Route('/planning/{userId}/{id}/edit', name: 'app_bookingplanning_showeditforuser', methods: ['GET', 'POST'])]
    public function showEditForUser(int $userId, Bookings $bookings, Request $request): Response
    {
        $form = $this->createForm(BookingType::class, $bookings, [
            'planning_mode' => true,
            'user' => $bookings->getUser(),
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $booking = $form->getData();

            $this->bookingService->editBooking($booking);
            $this->addFlash('success', 'Booking edited');
            return $this->redirectToRoute('app_planning_showbookingslistforuser', [
                'id' => $userId
            ]);
        }

        return $this->render('planning/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $bookings->getUser(),
        ]);
    }
}