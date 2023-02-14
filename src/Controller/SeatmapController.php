<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SeatmapController extends AbstractController
{
    #[Route('/seatmap', name: 'app_seatmap')]
    public function index(): Response
    {
        return $this->render('seatmap/index.html.twig', [
            'controller_name' => 'SeatmapController',
        ]);
    }
}