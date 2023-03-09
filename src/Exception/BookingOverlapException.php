<?php

declare(strict_types=1);

namespace App\Exception;

/**
 * This exception is fired when two bookings overlap each other
 */
class BookingOverlapException extends \Exception
{
}