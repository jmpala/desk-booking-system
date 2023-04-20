<?php

declare(strict_types=1);


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
#[Route('/user-panel')]
class UserPanelController extends AbstractController
{
    #[Route('/', name: 'app_userpanel_showpanel')]
    public function showPanel()
    {
        return $this->render('user_panel/index.html.twig');
    }
}