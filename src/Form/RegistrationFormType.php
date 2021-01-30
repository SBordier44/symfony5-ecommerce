<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\User;
use Rollerworks\Component\PasswordStrength\Validator\Constraints\PasswordRequirements;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'lastName',
                TextType::class,
                [
                    'label' => 'Votre Nom',
                    'attr' => [
                        'placeholder' => 'Veuillez renseigner votre nom de famille'
                    ],
                    'constraints' => [
                        new NotBlank(message: 'Veuillez renseigner votre nom'),
                        new Length(
                            [
                                'min' => 3
                            ]
                        )
                    ]
                ]
            )
            ->add(
                'firstName',
                TextType::class,
                [
                    'label' => 'Votre Prénom',
                    'attr' => [
                        'placeholder' => 'Veuillez renseigner votre prénom'
                    ],
                    'constraints' => [
                        new NotBlank(message: 'Veuillez renseigner votre prénom'),
                        new Length(min: 3)
                    ]
                ]
            )
            ->add(
                'email',
                EmailType::class,
                [
                    'label' => 'Votre adresse Email',
                    'attr' => [
                        'placeholder' => 'Renseignez votre adresse Email'
                    ],
                    'constraints' => [
                        new NotBlank(message: 'Veuillez renseigner votre adresse Email'),
                        new Email(
                            message: 'Veuillez renseigner une adresse Email valide',
                            mode: Email::VALIDATION_MODE_STRICT
                        )
                    ]
                ]
            )
            ->add(
                'plainPassword',
                PasswordType::class,
                [
                    'mapped' => false,
                    'label' => 'Votre mot de passe',
                    'attr' => [
                        'placeholder' => 'Veuillez renseigner un mot de passe'
                    ],
                    'constraints' => [
                        new NotBlank(message: 'Veuillez renseigner un mot de passe'),
                        new PasswordRequirements(
                            options: [
                                         'requireLetters' => true,
                                         'requireSpecialCharacter' => true,
                                         'requireNumbers' => true,
                                         'requireCaseDiff' => true,
                                         'minLength' => 8
                                     ]
                        ),
                        new Length(
                            max: 4096,
                            minMessage: 'Votre mot de passe doit avoir moins de {{ limit }} caractères'
                        )
                    ],
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => User::class,
            ]
        );
    }
}
