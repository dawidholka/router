<?php

namespace App\Http\Controllers;

use App\ViewModels\ZoneEditorViewModel;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ZoneEditorController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Zones/Editor', new ZoneEditorViewModel());
    }
}
