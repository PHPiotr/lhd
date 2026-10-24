<?php

namespace App\Controller;

use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'login')]
    #[Template('Security/login.html.twig')]
    public function loginAction(AuthenticationUtils $authenticationUtils)
    {
        if ($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('admin');
        }

        return [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError(),
        ];
    }

    #[Route('/logout', name: 'logout')]
    public function logoutAction(): never
    {
        throw new \LogicException('Intercepted by the logout key on the firewall.');
    }
}
