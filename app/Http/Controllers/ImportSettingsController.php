<?php

namespace App\Http\Controllers;

use App\Settings\GeneralSettings;
use App\Settings\ImportSettings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ImportSettingsController extends Controller
{
    public function show(ImportSettings $settings): Response
    {
        abort_if(!auth()->user()->admin, 403);

        return Inertia::render('Settings/Import', [
            'settings' => [
                'start_row' => $settings->start_row,
                'point_name' => $settings->point_name,
                'point_street' => $settings->point_street,
                'point_building_number' => $settings->point_building_number,
                'point_apartment' => $settings->point_apartment,
                'point_city' => $settings->point_city,
                'point_postcode' => $settings->point_postcode,
                'point_intercom' => $settings->point_intercom,
                'point_phone' => $settings->point_phone,
                'point_note' => $settings->point_note,
                'waypoint_content' => $settings->waypoint_content,
                'waypoint_quantity' => $settings->waypoint_quantity,

            ]
        ]);
    }

    public function update(
        Request $request,
        ImportSettings $settings
    ): RedirectResponse
    {
        abort_if(!auth()->user()->admin, 403);

        $request->validate([
            'start_row' => ['required', 'integer', 'min:1'],
            'point_name' => ['required', 'string'],
            'point_street' => ['required', 'string'],
            'point_building_number' => ['required', 'string'],
            'point_apartment' => ['required', 'string'],
            'point_city' => ['required', 'string'],
            'point_postcode' => ['required', 'string'],
            'point_intercom' => ['required', 'string'],
            'point_phone' => ['required', 'string'],
            'point_note' => ['required', 'string'],
            'waypoint_content' => ['required', 'string'],
            'waypoint_quantity' => ['required', 'string'],
        ]);

        $settings->start_row = $request['start_row'];
        $settings->point_name = $request['point_name'];
        $settings->point_street = $request['point_street'];
        $settings->point_building_number = $request['point_building_number'];
        $settings->point_apartment = $request['point_apartment'];
        $settings->point_city = $request['point_city'];
        $settings->point_postcode = $request['point_postcode'];
        $settings->point_intercom = $request['point_intercom'];
        $settings->point_phone = $request['point_phone'];
        $settings->point_note = $request['point_note'];
        $settings->waypoint_content = $request['waypoint_content'];
        $settings->waypoint_quantity = $request['waypoint_quantity'];
        $settings->save();

        return redirect()->back();
    }
}
