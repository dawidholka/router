<?php

namespace App\Http\Controllers;

use App\Settings\OSRMSettings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OSRMSettingsController extends Controller
{
    public function show(OSRMSettings $settings): Response
    {
        abort_if(!auth()->user()->admin, 403);

        return Inertia::render('Settings/OSRM', [
            'settings' => [
                'url' => $settings->url,
                'is_cloud_run' => $settings->is_cloud_run,
                'google_json_key' => $settings->google_json_key
            ]
        ]);
    }

    public function update(
        Request      $request,
        OSRMSettings $settings
    ): RedirectResponse
    {
        abort_if(!auth()->user()->admin, 403);

        $request->validate([
        ]);

        $settings->url = $request['url'];
        $settings->is_cloud_run = $request['is_cloud_run'];
        $settings->google_json_key = $request['google_json_key'];
        $settings->save();

        return redirect()->back();
    }
}
