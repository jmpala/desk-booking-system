<?php

declare(strict_types=1);

namespace App\Controller;

use App\Commons\RequestParameters;
use App\Entity\UnavailableDates;
use App\Service\BookableService;
use App\Service\UnavailableDatesService;
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
    public function showCreateUnavailableDatesPage(): Response
    {
        return $this->render('admin/bookable/unavailableDates/new/create_unavailable_dates.html.twig', [
            'allBookables' => $this->bookableService->getAllBookableAndRelatedCategories(),
        ]);
    }

    #[Route('/admin/unavailableDates/new/confirm', name: 'app_unavailabledates_showconfirmcreateunavailabledatespage', methods: ['POST'])]
    public function showConfirmCreateUnavailableDatesPage(Request $request): Response
    {
        // TODO: Erase when symfony forms are implemented
        if (!$this->isCsrfTokenValid('newUnavailableDates', $request->request->get('_csrf_token'))) {
            throw new \Exception('Invalid CSRF token');
        }

        return $this->render('admin/bookable/unavailableDates/new/confirm_new_unavailable_dates.html.twig', [
            'bookable' => $this->bookableService->findById(
                $request->request->getInt(RequestParameters::BOOKABLE_ID)
            ),
        ]);
    }

    #[Route('/admin/unavailableDates/new/confirmation', name: 'app_unavailabledates_showconfirmationunavailabledatespage', methods: ['POST'])]
    public function showConfirmationUnavailableDatesPage(Request $request): Response
    {
        // TODO: Erase when symfony forms are implemented
        if (!$this->isCsrfTokenValid('newUnavailableDates', $request->request->get('_csrf_token'))) {
            throw new \Exception('Invalid CSRF token');
        }

        return $this->render('admin/bookable/unavailableDates/new/confirmation_unavailable_dates.html.twig', [
            'unavailableDates' => $this->bookableService->createUnavailableDates(
                $request->request->getInt(RequestParameters::BOOKABLE_ID),
                $request->request->get(RequestParameters::FROM_DATE),
                $request->request->get(RequestParameters::TO_DATE),
                $request->request->get(RequestParameters::NOTES),
            ),
        ]);
    }

    #[Route('/admin/unavailableDates/delete', name: 'app_unavailabledates_deleteunavailabledateperiod', methods: ['POST'])]
    public function deleteUnavailableDatePeriod(Request $request): Response
    {
        // TODO: Erase when symfony forms are implemented
        if (!$this->isCsrfTokenValid('deleteUnavailableDate', $request->request->get('_csrf_token'))) {
            throw new \Exception('Invalid CSRF token');
        }

        $this->bookableService->deleteUnavailableDate(
            $request->request->getInt(RequestParameters::UNAVAILABLE_DATE_ID)
        );
        return $this->redirectToRoute('app_admin_showbookablemanagerpage');
    }

    #[Route('/admin/unavailableDates/{id}/edit', name: 'app_unavailabledates_showeditunavailabledatespage', methods: ['GET'])]
    public function showEditUnavailableDatesPage(UnavailableDates $unavailableDates): Response
    {
        return $this->render('admin/bookable/unavailableDates/edit/edit_unavailable_dates.html.twig', [
            'unavailableDate' => $unavailableDates
        ]);
    }

    #[Route('/admin/unavailableDates/{id}/edit/confirm', name: 'app_unavailabledates_showconfirmeditunavailabledatespage', methods: ['POST'])]
    public function showConfirmEditUnavailableDatesPage(UnavailableDates $unavailableDates, Request $request): Response
    {
        // TODO: Erase when symfony forms are implemented
        if (!$this->isCsrfTokenValid('editUnavailableDates', $request->request->get('_csrf_token'))) {
            throw new \Exception('Invalid CSRF token');
        }

        return $this->render('admin/bookable/unavailableDates/edit/confirm_edit_unavailable_dates.html.twig', [
            'unavailableDate' => $unavailableDates
        ]);
    }

    #[Route('/admin/unavailableDates/{id}/edit/confirmation', name: 'app_unavailabledates_showconfirmationeditunavailabledatespage', methods: ['POST'])]
    public function showConfirmationEditUnavailableDatesPage(Request $request): Response
    {
        // TODO: Erase when symfony forms are implemented
        if (!$this->isCsrfTokenValid('editUnavailableDates', $request->request->get('_csrf_token'))) {
            throw new \Exception('Invalid CSRF token');
        }

        return $this->render('admin/bookable/unavailableDates/edit/confirmation_unavailable_dates.html.twig', [
            'unavailableDates' => $this->bookableService->editUnavailableDates(
                $request->request->getInt(RequestParameters::UNAVAILABLE_DATE_ID),
                new \DateTime($request->request->get(RequestParameters::START_DATE)),
                new \DateTime($request->request->get(RequestParameters::END_DATE)),
                $request->request->get(RequestParameters::NOTES)
            ),
        ]);
    }
}