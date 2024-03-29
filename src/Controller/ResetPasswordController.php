<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordType;
use App\Form\ResetPasswordRequestType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\ResetPassword\Controller\ResetPasswordControllerTrait;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\Exception\TooManyPasswordRequestsException;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

class ResetPasswordController extends AbstractController
{
    use ResetPasswordControllerTrait;

    public function __construct(
        private ResetPasswordHelperInterface $resetPasswordHelper,
        private TranslatorInterface $translator,
        private ManagerRegistry $managerRegistry
    ) {
    }

    #[Route('/reset-password', name: 'security_forgot_password_request')]
    public function request(
        Request $request,
        MailerInterface $mailer
    ): Response {
        $form = $this->createForm(ResetPasswordRequestType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->processSendingPasswordResetEmail(
                $form->get('email')->getData(),
                $mailer
            );
        }

        return $this->render(
            'reset_password/request.html.twig',
            [
                'requestForm' => $form->createView(),
            ]
        );
    }

    #[Route('/reset-password/check-email', name: 'security_check_email')]
    public function checkEmail(): Response
    {
        if (null === ($resetToken = $this->getTokenObjectFromSession())) {
            return $this->redirectToRoute('security_forgot_password_request');
        }

        return $this->render(
            'reset_password/check_email.html.twig',
            [
                'resetToken' => $resetToken,
            ]
        );
    }

    #[Route('/reset-password/reset/{token}', name: 'security_reset_password')]
    public function reset(
        Request $request,
        UserPasswordHasherInterface $passwordEncoder,
        string $token = null
    ): Response {
        if ($token) {
            $this->storeTokenInSession($token);

            return $this->redirectToRoute('security_reset_password');
        }

        $token = $this->getTokenFromSession();
        if (null === $token) {
            throw $this->createNotFoundException(
                'Aucun jeton de réinitialisation du mot de passe trouvé dans l\'URL ou dans la session.'
            );
        }

        try {
            /** @var UserInterface $user */
            $user = $this->resetPasswordHelper->validateTokenAndFetchUser($token);
        } catch (ResetPasswordExceptionInterface $e) {
            $this->addFlash(
                'reset_password_error',
                sprintf(
                    'Un problème est survenu lors de la validation de votre demande de réinitialisation - %s',
                    $this->translator->trans($e->getReason(), [], 'ResetPasswordBundle')
                )
            );

            return $this->redirectToRoute('security_forgot_password_request');
        }

        $form = $this->createForm(ChangePasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->resetPasswordHelper->removeResetRequest($token);

            $encodedPassword = $passwordEncoder->hashPassword(
                $user,
                $form->get('plainPassword')->getData()
            );

            $user->setPassword($encodedPassword);
            $this->managerRegistry->getManager()->flush();

            $this->cleanSessionAfterReset();

            return $this->redirectToRoute('security_login');
        }

        return $this->render(
            'reset_password/reset.html.twig',
            [
                'resetForm' => $form->createView(),
            ]
        );
    }

    private function processSendingPasswordResetEmail(
        string $emailFormData,
        MailerInterface $mailer
    ): RedirectResponse {
        $user = $this->managerRegistry->getRepository(User::class)->findOneBy(
            [
                'email' => $emailFormData,
            ]
        );

        if (!$user) {
            return $this->redirectToRoute('security_check_email');
        }

        try {
            $resetToken = $this->resetPasswordHelper->generateResetToken($user);
        } catch (ResetPasswordExceptionInterface $e) {
            if ($e instanceof TooManyPasswordRequestsException) {
                $this->addFlash(
                    'reset_password_error',
                    'Une demande de réinitialisation de mot de passe est déjà en cours. 
                    Veuillez vérifier vos Emails ainsi que votre boite de spams.'
                );
            }
            // $this->addFlash('reset_password_error', sprintf(
            //     'There was a problem handling your password reset request - %s',
            //     $e->getReason()
            // ));

            return $this->redirectToRoute('security_check_email');
        }

        $email = (new TemplatedEmail())
            ->from(new Address('no-reply@symshop.com', 'SymShop'))
            ->to(new Address($user->getEmail()))
            ->subject('Votre demande de réinitialisation de mot de passe')
            ->htmlTemplate('reset_password/email.html.twig')
            ->context(
                [
                    'resetToken' => $resetToken,
                ]
            );

        $mailer->send($email);

        $this->setTokenObjectInSession($resetToken);

        return $this->redirectToRoute('security_check_email');
    }
}
