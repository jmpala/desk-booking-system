<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\UserRepository;
use App\service\BookingService;
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
        private UserRepository $userRepository,
    ){}

    #[Route('/planning', name: 'app_planning', methods: ['GET'])]
    public function showPlanningPage(Request $request): Response
    {
        $pageNum = (int) $request->query->get('page') ?: 1;
        $col = $request->query->get('col') ?: 'resource';
        $order = $request->query->get('ord') ?: 'asc';
        $past = $request->query->get('past') ?: 'false';
        $userid = (int) $request->query->get('userid') ?: null;

        $allUsers = $this->userRepository->findAll();

        if ($userid === null) {
            return $this->render('planning/planning.html.twig', [
                "isUserSelected" => false,
                "allUsers" => $allUsers
            ]);
        }

        $selectedUser = $this->userRepository->find($userid);

        $hasBookings = $this->bookingService->countAllBookingsByUserID($userid) > 0;
        $hasOnlyPastBookings = false;

        $pagerFanta = $this->bookingService->getAllBookingsByID($userid, $col, $order, $past);
        if ($hasBookings
            && $pagerFanta->getNbResults() === 0) {
            $past = 'true';
            $hasOnlyPastBookings = true;
            $pagerFanta = $this->bookingService->getAllBookingsByID($userid, $col, $order, $past);
        }

        $pagerFanta->setMaxPerPage(10);

        if ($pageNum > $pagerFanta->getNbPages()) {
            $pageNum = min($pageNum, $pagerFanta->getNbPages());
            return $this->redirect($request->getBaseUrl() . $request->getPathInfo() . '?userid=' . $userid . '&page=' . $pageNum . '&col=' . $col . '&ord=' . $order);
        }

        $pagerFanta->setCurrentPage($pageNum);

        return $this->render('planning/planning.html.twig', [
            "isUserSelected" => true,
            "allUsers" => $allUsers,
            "userid" => $userid,
            "selectedUser" => $selectedUser,
            'pager' => $pagerFanta,
            'selectedCol' => $col,
            'selectedOder' => $order,
            'todaysDate' => new \DateTime((new \DateTime())->format('Y-m-d')),
            'pastBookings' => $past,
            'hasBookings' => $hasBookings,
            'hasOnlyPastBookings' => $hasOnlyPastBookings
        ]);
    }


}