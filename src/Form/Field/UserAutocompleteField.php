<?php

namespace App\Form\Field;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\ParentEntityAutocompleteType;

#[AsEntityAutocompleteField]
class UserAutocompleteField extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => User::class,
            'label' => 'Please select a user',
            'placeholder' => 'User',
            'searchable_fields' => ['email'],
            'security' => 'ROLE_TEAMLEAD',
            'query_builder' => function (UserRepository $userRepository) {
                return $userRepository->createQueryBuilder('user');
            },
        ]);
    }

    public function getParent(): string
    {
        return ParentEntityAutocompleteType::class;
    }
}
