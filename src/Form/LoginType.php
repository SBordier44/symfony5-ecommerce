<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'email',
                EmailType::class,
                [
                    'label' => 'Adresse Email',
                    'attr' => [
                        'placeholder' => 'Votre adresse Email'
                    ],
                    'constraints' => [
                        new Email(
                            message: 'Veuillez renseigner une adresse Email valide',
                            mode: Email::VALIDATION_MODE_STRICT
                        )
                    ]
                ]
            )
            ->add(
                'password',
                PasswordType::class,
                [
                    'label' => 'Mot de passe',
                    'attr' => [
                        'placeholder' => 'Votre mot de passe'
                    ],
                    'constraints' => [
                        new NotBlank(message: 'Veuillez renseigner un mot de passe')
                    ]
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([]);
    }
}
