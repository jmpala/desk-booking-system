<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OfficeOverviewController extends AbstractController
{
    #[Route('/', name: 'app_office_overview')]
    public function index(): Response
    {

        return $this->render('office_overview/index.html.twig', [
            'today' => new \DateTime(),
        ]);
    }
}
