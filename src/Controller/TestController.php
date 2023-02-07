<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{

    #[Route('/', name: 'app_test_url')]
    public function testPage(LoggerInterface $logger): Response
    {
        return $this->render('test.html.twig');
    }
}