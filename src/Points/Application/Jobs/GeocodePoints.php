<?php

namespace Router\Points\Application\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Router\Points\Domain\Actions\BulkGecodePoints;

class GeocodePoints implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(protected Collection $points)
    {
    }

    public function handle(BulkGecodePoints $bulkGecodePoints)
    {
        $bulkGecodePoints->execute($this->points);
    }
}
