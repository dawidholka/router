<?php

namespace App\Support;

use Illuminate\Support\Carbon;

class DateHelper
{
    public static function parse($date): Carbon
    {
        return Carbon::parse($date, 'UTC')->setTimeZone(config('app.timezone'));
    }
}
