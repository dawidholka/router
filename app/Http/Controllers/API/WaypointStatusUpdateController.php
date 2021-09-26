<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Route;
use App\Models\User;
use App\Models\Waypoint;
use App\Settings\GeneralSettings;
use App\Support\ColorDictionary;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class WaypointStatusUpdateController extends Controller
{
    public function __invoke(
        Waypoint $waypoint,
        Request $request,
        GeneralSettings $settings
    ): JsonResponse
    {
        abort_if($waypoint->route->driver_id !== Auth::id(), 403);

        $request->validate([
            'status' => ['required', Rule::in(['delivered', 'undelivered', 'problem'])]
        ]);

        abort_if(
            $settings->force_photo_upload && !$waypoint->photo_uploaded && $request['status'] === 'delivered',
            422,
            'Wymagane jest zrobienie zdjęcia do zmiany statusu dostarczenia.'
        );

        $waypoint->status = $request['status'];
        $waypoint->delivered_time = now();
        $waypoint->update();

        return response()->json(['success' => true]);
    }
}
