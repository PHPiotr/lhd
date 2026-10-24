<?php

namespace App\Controller;

use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class AdminController extends AbstractController
{
    // access is enforced by access_control (^/admin requires ROLE_USER)
    #[Route('/admin', name: 'admin')]
    #[Template('Admin/index.html.twig')]
    public function indexAction(): array
    {
        return [];
    }
}
