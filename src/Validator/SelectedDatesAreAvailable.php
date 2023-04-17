<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class SelectedDatesAreAvailable extends Constraint
{
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
    public $message = 'The selected dates are not available.';
    public $messageBooked = 'Booked from {{ start_date }} to {{ end_date }}.';
    public $messageDisabled = 'Disabled from {{ start_date }} to {{ end_date }}.';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
