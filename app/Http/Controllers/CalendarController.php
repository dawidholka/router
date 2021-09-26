<?php

namespace App\Http\Controllers;

use App\Models\Route;
use App\Support\ColorDictionary;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;
use Inertia\Response;

class CalendarController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Calendar');
    }

    public function events(Request $request)
    {
        $request->validate([
            'start' => ['date', 'required'],
            'end' => ['date', 'required']
        ]);
        $startDay = Carbon::parse($request['start']);
        $endDay = Carbon::parse($request['end']);

        $routes = Route::whereBetween('delivery_time',[$startDay, $endDay])->get();
        $routes->load('driver');
        $events = [];
        foreach($routes as $route){
            $data = [];
            $data['title'] = 'Trasa #'.$route->id. ' '. $route->driver?->name;
            $data['start'] = $route->delivery_time;
            $data['color'] = $route->driver?->color ?? ColorDictionary::getRandomColor();
            $data['url'] = route('routes.show', $route->id);
            $events[] = $data;
        }
        return response()->json($events);
    }
}
