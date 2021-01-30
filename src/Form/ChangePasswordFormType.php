<?php

declare(strict_types=1);

namespace App\Form;

use Rollerworks\Component\PasswordStrength\Validator\Constraints\PasswordRequirements;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ChangePasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'plainPassword',
                RepeatedType::class,
                [
                    'type' => PasswordType::class,
                    'first_options' => [
                        'constraints' => [
                            new NotBlank(message: 'Veuillez renseigner un mot de passe'),
                            new PasswordRequirements(
                                [
                                    'requireLetters' => true,
                                    'requireSpecialCharacter' => true,
                                    'requireNumbers' => true,
                                    'requireCaseDiff' => true,
                                    'minLength' => 8
                                ]
                            ),
                            new Length(
                                max: 4096,
                                maxMessage: 'Votre mot de passe doit avoir moins de {{ limit }} caractères'
                            )
                        ],
                        'label' => 'Nouveau mot de passe',
                    ],
                    'second_options' => [
                        'label' => 'Répéter le nouveau mot de passe',
                    ],
                    'invalid_message' => 'Les mots de passe renseignés ne correspondent pas.',
                    'mapped' => false,
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
