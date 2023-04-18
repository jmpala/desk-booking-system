<?php

namespace App\Form;

use App\Entity\Bookable;
use App\Entity\UnavailableDates;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UnavailableDateType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array                $options
    ): void
    {
        $builder
            ->add(
                'bookable',
                EntityType::class,
                [
                    'class' => Bookable::class,
                    'choice_label' => 'code',
                ]
            )
            ->add(
                'start_date',
                DateType::class, [
                    'widget' => 'single_text',
                ]
            )
            ->add(
                'end_date',
                DateType::class,
                [
                    'widget' => 'single_text',
                ]
            )
            ->add(
                'notes',
                TextareaType::class
            );

        $builder->get('start_date')
            ->addEventListener(
                FormEvents::PRE_SET_DATA,
                function (
                    FormEvent $event,
                ): void {
                    $event->setData(
                        $event->getData()
                        ?? new \DateTime(),
                    );
                },
            );

        $builder->get('end_date')
            ->addEventListener(
                FormEvents::PRE_SET_DATA,
                function (
                    FormEvent $event,
                ): void {
                    $event->setData(
                        $event->getData()
                        ?? new \DateTime(),
                    );
                },
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UnavailableDates::class,
        ]);
    }
}
