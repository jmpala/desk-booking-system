<?php

declare(strict_types=1);

namespace App\Controller;

use App\service\AdminService;
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
        $pageNum = (int) $request->query->get('page') ?: 1;
        $col = $request->query->get('col') ?: 'bookable';
        $order = $request->query->get('ord') ?: 'asc';
        $past = $request->query->get('past') ?: 'false';

        $pagerFanta = $this->adminService->getAllUnavailableDates($col, $order, $past);

        $pagerFanta->setMaxPerPage(10);

        if ($pageNum > $pagerFanta->getNbPages()) {
            $pageNum = min($pageNum, $pagerFanta->getNbPages());
            return $this->redirect($request->getBaseUrl() . $request->getPathInfo() . '?page=' . $pageNum . '&col=' . $col . '&ord=' . $order);
        }

        $pagerFanta->setCurrentPage($pageNum);

        return $this->render('admin/bookableManager.html.twig', [
            'pager' => $pagerFanta,
            'selectedCol' => $col,
            'selectedOder' => $order,
            'pastUnavailabledates' => $past,
            'todaysDate' => new \DateTime((new \DateTime())->format('Y-m-d')),
        ]);
    }
}