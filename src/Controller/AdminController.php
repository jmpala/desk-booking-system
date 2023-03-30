<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\AdminService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function showBookableManagerPage(): Response
    {
        return $this->render('admin/bookable/bookable_manager.html.twig', [
            'pager' => $this->adminService->getAllUnavailableDatesPaged()
        ]);
    }
}