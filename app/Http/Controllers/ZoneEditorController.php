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
        abort_if(!auth()->user()->admin, 403);

        return Inertia::render('Zones/Editor', new ZoneEditorViewModel());
    }
}
