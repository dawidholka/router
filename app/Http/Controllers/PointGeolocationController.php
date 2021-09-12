<?php

namespace App\Http\Controllers;

use App\Actions\Points\GeocodePoint;
use App\Models\Point;
use Illuminate\Http\RedirectResponse;

class PointGeolocationController extends Controller
{
    public function __invoke(Point $point, GeocodePoint $geocodePoint): RedirectResponse
    {

        $geocodePoint->execute($point);

        return redirect()->back();
    }
}
