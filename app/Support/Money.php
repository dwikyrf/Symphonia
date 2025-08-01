<?php

namespace App\Support;

class Money
{
    /**
     * Bulatkan ke kelipatan tertentu (default = 1 000).
     *
     * @param  int|float  $amount   nilai asli
     * @param  int        $nearest  kelipatan
     * @return int
     */
    public static function roundTo($amount, int $nearest = 1000): int
    {
        return (int) (round($amount / $nearest) * $nearest);
    }
}
