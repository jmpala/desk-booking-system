<?php

declare(strict_types=1);

namespace App\utils;

class DateUtils
{
    public static function getTodaysDate(): \DateTime
    {
        return new \DateTime((new \DateTime())->format('Y-m-d'));
    }

    public static function doDatesOverlap(\DateTime $from1, \DateTime $to1, \DateTime $from2, \DateTime $to2): bool
    {
        return $from1 <= $to2 && $from2 <= $to1;
    }
}