<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\UnavailableDates;
use App\Form\DeleteUnavailableDatesType;
use App\Form\UnavailableDateType;
use App\Repository\UnavailableDatesRepository;
use App\Service\AdminService;
use App\Service\BookableService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class UnavailableDatesController extends AbstractController
{

    public function __construct(
        private BookableService            $bookableService,
        private AdminService               $adminService,
        private UnavailableDatesRepository $unavailableDatesRepository,
    )
    {
    }

    #[Route('/admin/unavailableDates', name: 'app_admin_showbookablemanagerpage', methods: ['GET'])]
    public function listAllPaged(Request $request): Response
    {
        $deleteForm = $this->createForm(DeleteUnavailableDatesType::class);
        return $this->render('admin/unavailable_dates/list.html.twig', [
            'deleteForm' => $deleteForm->createView(),
            'pager' => $this->adminService->getAllUnavailableDatesPaged()
        ]);
    }

    #[Route('/admin/unavailableDates/new', name: 'app_unavailabledates_showcreateunavailabledatespage', methods: ['GET', 'POST'])]
    public function showCreate(Request $request): Response
    {
        $form = $this->createForm(UnavailableDateType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $unavailableDates = $form->getData();
            $this->unavailableDatesRepository->save($unavailableDates, true);
            $this->addFlash(
                'success',
                'Unavailable dates created!',
            );
            return $this->redirectToRoute('app_admin_showbookablemanagerpage');
        }

        return $this->render(
            'admin/unavailable_dates/create.html.twig',
            [
                'form' => $form->createView(),
            ],
        );
    }

    #[Route('/admin/unavailableDates/{id}/delete', name: 'app_unavailabledates_deleteunavailabledateperiod', methods: ['POST'])]
    public function delete(UnavailableDates $unavailableDates, Request $request): Response
    {
        $deleteForm = $this->createForm(DeleteUnavailableDatesType::class);
        $deleteForm->handleRequest($request);
        if ($deleteForm->isSubmitted() && $deleteForm->isValid()) {
            $this->bookableService->deleteUnavailableDate($unavailableDates->getId());
            $this->addFlash(
                'success',
                'Unavailable dates deleted!',
            );
            return $this->redirectToRoute('app_admin_showbookablemanagerpage');
        }
        $this->addFlash(
            'danger',
            'Could not delete unavailable dates!',
        );
        return $this->redirectToRoute('app_admin_showbookablemanagerpage');
    }

    #[Route('/admin/unavailableDates/{id}/edit', name: 'app_unavailabledates_showeditunavailabledatespage', methods: ['GET', 'POST'])]
    public function showEdit(UnavailableDates $unavailableDates, Request $request): Response
    {
        $form = $this->createForm(UnavailableDateType::class, $unavailableDates);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $unavailableDates = $form->getData();
            $this->unavailableDatesRepository->save($unavailableDates, true);
            $this->addFlash(
                'success',
                'Unavailable dates Updated!',
            );
            return $this->redirectToRoute('app_admin_showbookablemanagerpage');
        }

        return $this->render(
            'admin/unavailable_dates/edit.html.twig',
            [
                'form' => $form->createView(),
            ],
        );
    }
}