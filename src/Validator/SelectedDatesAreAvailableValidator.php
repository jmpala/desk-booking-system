<?php

namespace App\Validator;

use App\Service\BookableService;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class SelectedDatesAreAvailableValidator extends ConstraintValidator
{
    public function __construct(
        private BookableService $bookableService,
    ) {
    }

    public function validate(
        $value,
        Constraint $constraint,
    ) {
        /* @var \App\Entity\Bookings $value */
        /* @var App\Validator\SelectedDatesAreAvailable $constraint */

        $isBlockBooked = $this->bookableService->checkBookableAvailabilityByDate(
            $value->getBookable()
                ->getId(),
            $value->getStartDate(),
            $value->getEndDate(),
        );

        if ($isBlockBooked['isAvailable']) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->addViolation()
        ;

        if (isset($isBlockBooked['bookings'])) {
            foreach ($isBlockBooked['bookings'] as $booking) {
                $this->context->buildViolation($constraint->messageBooked)
                    ->setParameter(
                        '{{ start_date }}',
                        $booking['from'],
                    )
                    ->setParameter(
                        '{{ end_date }}',
                        $booking['to'],
                    )
                    ->addViolation()
                ;
            }
        }

        if (isset($isBlockBooked['unavailableDates'])) {
            foreach ($isBlockBooked['unavailableDates'] as $booking) {
                $this->context->buildViolation($constraint->messageDisabled)
                    ->setParameter(
                        '{{ start_date }}',
                        $booking['from'],
                    )
                    ->setParameter(
                        '{{ end_date }}',
                        $booking['to'],
                    )
                    ->addViolation()
                ;
            }
        }
    }
}
