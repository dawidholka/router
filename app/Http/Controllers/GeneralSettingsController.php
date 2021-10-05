<?php

namespace App\Http\Controllers;

use App\Settings\GeneralSettings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class GeneralSettingsController extends Controller
{
    public function show(GeneralSettings $settings): Response
    {
        abort_if(!auth()->user()->admin, 403);

        return Inertia::render('Settings/General', [
            'settings' => [
                'force_photo_upload' => $settings->force_photo_upload,
                'google_maps_api_key' => $settings->google_maps_api_key,
                'routexl_username' => $settings->routexl_username,
                'routexl_password' => $settings->routexl_password,
                'company_lat' => $settings->company_lat,
                'company_lng' => $settings->company_lng,

            ]
        ]);
    }

    public function update(
        Request $request,
        GeneralSettings $settings
    ): RedirectResponse
    {
        abort_if(!auth()->user()->admin, 403);

        $request->validate([
            'force_photo_upload' => ['required', 'boolean'],
            'google_maps_api_key' => ['required', 'string']
        ]);

        $settings->google_maps_api_key = $request['google_maps_api_key'];
        $settings->force_photo_upload = $request['force_photo_upload'];
        $settings->company_lat = $request['company_lat'];
        $settings->company_lng = $request['company_lng'];
        $settings->save();

        return redirect()->back();
    }
}
