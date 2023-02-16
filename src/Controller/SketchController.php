<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SketchController extends AbstractController
{
    #[Route('/sketch', name: 'app_sketch')]
    public function index(): Response
    {
        return $this->render('sketch/index.html.twig', [
            'controller_name' => 'SketchController',
        ]);
    }
}
