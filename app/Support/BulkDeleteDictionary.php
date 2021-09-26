<?php

namespace App\Support;

final class BulkDeleteDictionary
{
    const ALL = 'all';
    const LAST_HOUR = 'last-hour';
    const OLDER_THEN_30_DAYS = 'older-then-30-days';
    const OLDER_THEN_90_DAYS = 'older-then-90-days';

    public static function toArray(): array
    {
        return [
            self::ALL,
            self::LAST_HOUR,
            self::OLDER_THEN_30_DAYS,
            self::OLDER_THEN_90_DAYS
        ];
    }
}
