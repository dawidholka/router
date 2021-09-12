<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MapPinSvgController extends Controller
{
    public function __invoke(Request $request, string $color): Response
    {
        return response()
            ->view('map-pin', [
                'color' => $color
            ], 200)
            ->header('Content-Type', 'image/svg+xml');
    }
}
