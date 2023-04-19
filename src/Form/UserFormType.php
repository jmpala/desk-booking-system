<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserFormType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options,
    ): void {
        $builder
            ->add(
                'email',
                EmailType::class,
                [
                    'attr' => [
                        'placeholder' => 'Please enter an email',
                    ],
                ],
            )
            ->add(
                'roles',
                ChoiceType::class,
                [
                    'expanded' => false,
                    'multiple' => false,
                    'choices' => $this->generateRolesChoices($options['roles_choices']),
                ],
            )
        ;

        $builder->get('roles')
            ->addModelTransformer(
                new CallbackTransformer(
                    function (
                        $rolesArray,
                    ) {
                        // transform the array to a string
                        return count($rolesArray) ?
                            $rolesArray[0] :
                            '';
                    },
                    function (
                        $rolesString,
                    ) {
                        // transform the string back to an array
                        return [$rolesString];
                    }
                ),
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'roles_choices' => [],
        ]);
    }

    private function generateRolesChoices(array $roles): array
    {
        $roles = array_keys($roles);
        $rolesChoices = [];
        foreach ($roles as $role) {
            $rolesChoices[$role] = $role;
        }
        return $rolesChoices;
    }
}
