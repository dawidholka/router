<?php

namespace Router\Routes\Domain\Actions;

use App\Support\BulkDeleteDictionary;
use Illuminate\Support\Carbon;
use Router\Routes\Domain\Models\Route;

class BulkDeleteRoutes
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
        Route::query()->delete();
    }

    private function deleteLastHour()
    {
        Route::where('created_at', '>=', now()->subHour())->delete();
    }

    private function deleteOlderThen(Carbon $date)
    {
        Route::where('created_at', '<=', $date)->delete();
    }
}
