<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\User;
use Rollerworks\Component\PasswordStrength\Validator\Constraints\PasswordRequirements;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
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
                        new NotBlank(message: 'Le nom de famille est obligatoire'),
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
                        new NotBlank(message: 'Le prénom est obligatoire'),
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
                        new NotBlank(message: "L'adresse email est obligatoire"),
                        new Email(
                            message: 'Veuillez renseigner une adresse email valide'
                        )
                    ]
                ]
            )
            ->add('plainPassword', RepeatedType::class, [
                'mapped' => false,
                'required' => true,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank(message: 'Le mot de passe est obligatoire'),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                        'max' => 4096,
                    ]),
                    new PasswordRequirements(
                        options: [
                            'requireLetters' => true,
                            'requireSpecialCharacter' => true,
                            'requireNumbers' => true,
                            'requireCaseDiff' => true,
                            'minLength' => 8
                        ]
                    )
                ],
                'first_options' => [
                    'label' => 'Mot de passe',
                    'attr' => [
                        'placeholder' => 'Renseignez un mot de passe sécurisé'
                    ]
                ],
                'second_options' => [
                    'label' => 'Mot de passe (Confirmation)',
                    'attr' => [
                        'placeholder' => 'Confirmez votre mot de passe'
                    ]
                ],
                'invalid_message' => 'Les mots de passe renseignés ne correspondent pas',
                'empty_data' => ''
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
