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

        return $this->render('admin/bookable/unavailableDates/createUnavailableDates.html.twig', [
            'allBookables' => $allBookables,
            'todaysDate' => DateUtils::getTodaysDate(),
            'errors' => $errors,
        ]);
    }
}