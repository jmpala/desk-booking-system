<?php

namespace App\Form;

use App\Validator\UniqueEmail;
use App\Validator\UniqueEmailInDatabase;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class ChangeEmailFormType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options,
    ): void {
        $builder
            ->add(
                'newEmail',
                RepeatedType::class,
                [
                    'type' => EmailType::class,
                    'options' => [
                        'attr' => [
                            'autocomplete' => 'email',
                        ],
                    ],
                    'first_options' => [
                        'label' => 'New email',
                        'constraints' => [
                            new NotBlank([
                                'message' => 'Please enter an email',
                            ]),
                            new Email([
                                'message' => 'Please enter a valid email',
                            ]),
                            new UniqueEmailInDatabase([
                                'message' => 'This email is already in use',
                            ]),
                        ],
                    ],
                    'second_options' => [
                        'label' => 'Repeat email',
                    ],
                    'mapped' => false,
                ],
            )
            ->add(
                'currentPassword',
                PasswordType::class,
                [
                    'label' => 'Password',
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Please enter a password',
                        ]),
                        new UserPassword([
                            'message' => 'The password is invalid.',
                        ]),
                    ],
                    'mapped' => false,
                ],
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
