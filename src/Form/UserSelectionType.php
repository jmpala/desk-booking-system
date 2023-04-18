<?php

declare(strict_types=1);

namespace App\Form;

use App\Form\Field\UserAutocompleteField;
use App\Form\Model\UserSelectionModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserSelectionType extends AbstractType
{
    public
    function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'user',
            UserAutocompleteField::class
        );
    }

    public
    function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserSelectionModel::class,
        ]);
    }
}
