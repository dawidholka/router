<?php

namespace Router\Points\Application\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Router\Points\Domain\Actions\GeocodePoint;
use Router\Points\Domain\Models\Point;

class PointGeolocationController extends Controller
{
    public function __invoke(Point $point, GeocodePoint $geocodePoint): RedirectResponse
    {
        abort_if(!auth()->user()->admin, 403);

        $geocodePoint->execute($point);

        return redirect()->back();
    }
}
