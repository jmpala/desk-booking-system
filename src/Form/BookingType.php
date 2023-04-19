<?php

namespace App\Form;

use App\Entity\Bookable;
use App\Entity\Bookings;
use App\Repository\BookableRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookingType extends AbstractType
{
    public function __construct(
        private BookableRepository $bookableRepository,
    )
    {
    }

    public function buildForm(
        FormBuilderInterface $builder,
        array                $options,
    ): void
    {
        $builder
            ->add(
                'bookable',
                EntityType::class,
                [
                    'class' => Bookable::class,
                    'choice_label' => 'code',
                    'choices' => $this->bookableRepository->findAll(),
                    'label' => 'Bookable Resource',
                    'placeholder' => 'Choose a resource',
                ],
            )
            ->add(
                'start_date',
                DateType::class,
                [
                    'widget' => 'single_text',
                    'attr' => [
                        'min' => (new \DateTime())->format('Y-m-d'),
                    ],
                ],
            )
            ->add(
                'end_date',
                DateType::class,
                [
                    'widget' => 'single_text',
                ],
            );

        if ($options['planning_mode']) {
            $builder->add(
                'user',
                HiddenType::class,
                [
                    'mapped' => false,
                    'data' => $options['user']->getId(),
                ],
            );
        }

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
            'data_class' => Bookings::class,
            'planning_mode' => false,
            'user' => null,
        ]);
    }
}
