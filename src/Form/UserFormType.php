<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class)
            ->add('roles', ChoiceType::class, [
                'expanded' => true,
                'multiple' => false,
                'choices'  => $this->generateRolesChoices($options['roles_choices']),
            ])
            ->add('return', ButtonType::class, [
                'label' => 'Return',
                'attr' => [
                    'class' => 'btn btn-secondary',
                    'onClick' => 'window.history.back();'
                ],
            ])
            ->add('submit', ButtonType::class, [
                'label' => 'Create',
                'attr' => [
                    'class' => 'btn btn-primary',
                ],
            ])
        ;

        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesArray) {
                    // transform the array to a string
                    return count($rolesArray) ? $rolesArray[0] : '';
                },
                function ($rolesString) {
                    // transform the string back to an array
                    return [$rolesString];
                }
            ));
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
