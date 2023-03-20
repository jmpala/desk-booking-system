<?php

declare(strict_types=1);

namespace App\Controller;

use App\service\BookableService;
use App\utils\DateUtils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UnavailableDatesController extends AbstractController
{
    public function __construct(
        private BookableService $bookableService,
    ){}

    #[Route('/admin/unavailableDates/create', name: 'app_unavailabledates_showcreateunavailabledatespage', methods: ['GET'])]
    public function showCreateUnavailableDatesPage(Request $request): Response
    {
        $allBookables = $this->bookableService->getAllBookableAndRelatedCategories();

        $errors = $request->getSession()->getFlashBag()->get('error');

        return $this->render('admin/bookable/unavailableDates/new/createUnavailableDates.html.twig', [
            'allBookables' => $allBookables,
            'todaysDate' => DateUtils::getTodaysDate(),
            'errors' => $errors,
        ]);
    }

    #[Route('/admin/unavailableDates/confirm', name: 'app_unavailabledates_showconfirmcreateunavailabledatespage', methods: ['POST'])]
    public function showConfirmCreateUnavailableDatesPage(Request $request): Response
    {
        if (!$this->isCsrfTokenValid('newUnavailableDates', $request->request->get('_csrf_token'))) {
            throw new \Exception('Invalid CSRF token');
        }

        $bookableId = (int) $request->request->get('bookable');
        $bookable = $this->bookableService->findById($bookableId);
        $fromDate = $request->request->get('fromDate');
        $toDate = $request->request->get('toDate');
        $notes = $request->request->get('notes');

        return $this->render('admin/bookable/unavailableDates/new/confirmNewUnavailableDates.html.twig', [
            'bookable' => $bookable,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'notes' => $notes
        ]);
    }

    #[Route('/admin/unavailableDates/confirmation', name: 'app_unavailabledates_showconfirmationunavailabledatespage', methods: ['POST'])]
    public function showConfirmationUnavailableDatesPage(Request $request): Response
    {
        if (!$this->isCsrfTokenValid('newUnavailableDates', $request->request->get('_csrf_token'))) {
            throw new \Exception('Invalid CSRF token');
        }

        $bookableId = (int) $request->request->get('bookable');
        $fromDate = $request->request->get('fromDate');
        $toDate = $request->request->get('toDate');
        $notes = $request->request->get('notes');

        $unavailableDates = $this->bookableService->createUnavailableDates($bookableId, $fromDate, $toDate, $notes);

        return $this->render('admin/bookable/unavailableDates/new/confirmationUnavailableDates.html.twig', [
            'unavailableDates' => $unavailableDates
        ]);
    }
}