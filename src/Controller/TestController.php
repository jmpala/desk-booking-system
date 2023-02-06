<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{

    #[Route('/')]
    public function testPage(LoggerInterface $logger): Response
    {
        dump('testPage-flag');
        $logger->error('testPage-flag');
        return $this->render('test.html.twig');
    }
}