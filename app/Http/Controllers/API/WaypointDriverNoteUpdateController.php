<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Route;
use App\Models\User;
use App\Models\Waypoint;
use App\Support\ColorDictionary;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class WaypointDriverNoteUpdateController extends Controller
{
    public function __invoke(Waypoint $waypoint, Request $request): JsonResponse
    {
        abort_if($waypoint->route->driver_id !== Auth::id(), 403);

        $request->validate([
            'note' => ['required', 'string']
        ]);

        $point = $waypoint->point;

        $point->driver_note = $request['note'];
        $point->save();

        return response()->json(['success' => true]);
    }
}
