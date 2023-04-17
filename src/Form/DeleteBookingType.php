<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeleteBookingType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options,
    ): void {
        $builder
            ->add(
                'bookingId',
                HiddenType::class,
                [
                    'data' => $options['bookingId'],
                ],
            )
            ->add(
                'userId',
                HiddenType::class,
                [
                    'data' => $options['userId'],
                ],
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('bookingId', '');
        $resolver->setDefault('userId', '');
    }
}
