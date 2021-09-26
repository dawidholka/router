<?php

namespace App\Http\Controllers;

use App\Models\Waypoint;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class WaypointPhotoController extends Controller
{
    public function __invoke(Waypoint $waypoint): ?Media
    {
        $photo = $waypoint->getFirstMedia('image');

        abort_unless((bool)$photo, 404);

        return $photo;
    }
}
