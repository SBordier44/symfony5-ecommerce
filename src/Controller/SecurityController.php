<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\LoginType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'security_login')]
    public function login(
        AuthenticationUtils $authenticationUtils
    ): Response {
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        $form = $this->createForm(
            LoginType::class,
            [
                'email' => $authenticationUtils->getLastUsername()
            ]
        );

        return $this->render(
            'security/login.html.twig',
            [
                'form' => $form->createView(),
                'error' => $authenticationUtils->getLastAuthenticationError()
            ]
        );
    }

    #[Route('/logout', name: 'security_logout')]
    public function logout(): void
    {
    }
}
