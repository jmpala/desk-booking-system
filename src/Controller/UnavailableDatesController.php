<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\UnavailableDates;
use App\Service\BookableService;
use App\Service\UnavailableDatesService;
use App\utils\DateUtils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class UnavailableDatesController extends AbstractController
{

    public function __construct(
        private BookableService $bookableService,
        private UnavailableDatesService $unavailableDatesService,
    ){}

    #[Route('/admin/unavailableDates/new/create', name: 'app_unavailabledates_showcreateunavailabledatespage', methods: ['GET'])]
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

    #[Route('/admin/unavailableDates/new/confirm', name: 'app_unavailabledates_showconfirmcreateunavailabledatespage', methods: ['POST'])]
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

    #[Route('/admin/unavailableDates/new/confirmation', name: 'app_unavailabledates_showconfirmationunavailabledatespage', methods: ['POST'])]
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

    #[Route('/admin/unavailableDates/delete', name: 'app_unavailabledates_deleteunavailabledateperiod', methods: ['POST'])]
    public function deleteUnavailableDatePeriod(Request $request): Response
    {
        if (!$this->isCsrfTokenValid('deleteUnavailableDate', $request->request->get('_csrf_token'))) {
            throw new \Exception('Invalid CSRF token');
        }

        $unavailableDateId = (int) $request->request->get('unavailableId');
        $this->bookableService->deleteUnavailableDate($unavailableDateId);

        return $this->redirectToRoute('app_admin_showbookablemanagerpage');
    }

    #[Route('/admin/unavailableDates/{id}/edit', name: 'app_unavailabledates_showeditunavailabledatespage', methods: ['GET'])]
    public function showEditUnavailableDatesPage(UnavailableDates $unavailableDates, Request $request): Response
    {
        $errors = $request->getSession()->getFlashBag()->get('error');

        return $this->render('admin/bookable/unavailableDates/edit/editUnavailableDates.html.twig', [
            'unavailableDate' => $unavailableDates,
            'todaysDate' => DateUtils::getTodaysDate(),
            'errors' => $errors,
        ]);
    }

    #[Route('/admin/unavailableDates/{id}/edit/confirm', name: 'app_unavailabledates_showconfirmeditunavailabledatespage', methods: ['POST'])]
    public function showConfirmEditUnavailableDatesPage(Request $request): Response
    {
        if (!$this->isCsrfTokenValid('editUnavailableDates', $request->request->get('_csrf_token'))) {
            throw new \Exception('Invalid CSRF token');
        }

        $bookableId = (int) $request->request->get('bookable');
        $bookable = $this->bookableService->findById($bookableId);
        $unavailableDateId = (int) $request->request->get('unavailableDateId');
        $unavailableDate = $this->unavailableDatesService->findById($unavailableDateId);
        $fromDate = $request->request->get('fromDate');
        $toDate = $request->request->get('toDate');
        $notes = $request->request->get('notes');

        return $this->render('admin/bookable/unavailableDates/edit/confirmEditUnavailableDates.html.twig', [
            'bookable' => $bookable,
            'unavailableDate' => $unavailableDate,
            'todaysDate' => DateUtils::getTodaysDate(),
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'notes' => $notes
        ]);
    }

    #[Route('/admin/unavailableDates/{id}/edit/confirmation', name: 'app_unavailabledates_showconfirmationeditunavailabledatespage', methods: ['POST'])]
    public function showConfirmationEditUnavailableDatesPage(Request $request): Response
    {
        if (!$this->isCsrfTokenValid('editUnavailableDates', $request->request->get('_csrf_token'))) {
            throw new \Exception('Invalid CSRF token');
        }

        $bookableId = (int) $request->request->get('bookable');
        $unavailableDateId = (int) $request->request->get('unavailableDateId');
        $fromDate = $request->request->get('fromDate');
        $toDate = $request->request->get('toDate');
        $notes = $request->request->get('notes');

        $unavailableDates = $this->bookableService->editUnavailableDates($unavailableDateId, new \DateTime($fromDate), new \DateTime($toDate), $notes);

        return $this->render('admin/bookable/unavailableDates/edit/confirmationUnavailableDates.html.twig', [
            'unavailableDates' => $unavailableDates
        ]);
    }
}