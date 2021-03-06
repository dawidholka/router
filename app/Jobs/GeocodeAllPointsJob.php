<?php

namespace App\Jobs;

use App\Actions\Points\GeocodePoint;
use App\Models\Point;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GeocodeAllPointsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(GeocodePoint $geocodePoint)
    {
        $points = Point::whereNull('lat')->whereNull('long')->get();

        foreach ($points as $point) {
            try {
                $geocodePoint->execute($point);
            } catch (\Exception $exception) {
                continue;
            }
        }
    }
}
