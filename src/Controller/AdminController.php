<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\AdminService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    public function __construct(
        private AdminService $adminService,
    ){}

    #[Route('/admin', name: 'app_admin_showadminpanel', methods: ['GET'])]
    public function showAdminPanel(): Response
    {
        return $this->render('admin/admin.html.twig');
    }

    #[Route('/admin/bookable', name: 'app_admin_showbookablemanagerpage', methods: ['GET'])]
    public function showBookableManagerPage(Request $request): Response
    {
        $pageNum = $request->query->getInt('page', 1);
        $col = $request->query->getAlpha('col', 'bookable');
        $order = $request->query->getAlpha('ord', 'asc');
        $past = $request->query->getAlpha('past', 'false');

        $pagerFanta = $this->adminService->getAllUnavailableDatesPaged($pageNum, $col, $order, $past);

        if ($pagerFanta->getCurrentPage() < $pageNum) {
            return $this->redirectToRoute('app_admin_showbookablemanagerpage', [
                'page' => $pagerFanta->getCurrentPage(),
                'col' => $col,
                'ord' => $order,
                'past' => $past,
            ]);
        }

        return $this->render('admin/bookable/bookableManager.html.twig', [
            'pager' => $pagerFanta,
            'selectedCol' => $col,
            'selectedOder' => $order,
            'pastUnavailabledates' => $past,
            'todaysDate' => new \DateTime((new \DateTime())->format('Y-m-d')),
        ]);
    }
}