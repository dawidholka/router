<?php

namespace Router\Points\Domain\Actions;

use App\Support\BulkDeleteDictionary;
use Illuminate\Support\Carbon;
use Router\Points\Domain\Models\Point;

class BulkDeletePoints
{
    public function execute(string $option)
    {
        switch ($option) {
            case BulkDeleteDictionary::ALL:
                $this->deleteAll();
                break;
            case BulkDeleteDictionary::LAST_HOUR:
                $this->deleteLastHour();
                break;
            case BulkDeleteDictionary::OLDER_THEN_30_DAYS:
                $this->deleteOlderThen(now()->subDays(30));
                break;
            case BulkDeleteDictionary::OLDER_THEN_90_DAYS:
                $this->deleteOlderThen(now()->subDays(90));
                break;
        }
    }

    private function deleteAll()
    {
        Point::query()->delete();
    }

    private function deleteLastHour()
    {
        Point::where('created_at', '>=', now()->subHour())->delete();
    }

    private function deleteOlderThen(Carbon $date)
    {
        Point::where('created_at', '<=', $date)->delete();
    }
}
