<?php

namespace App\Http\Controllers;

use App\ViewModels\DashboardViewModel;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(): Response
    {
        $viewModel = new DashboardViewModel();

        return Inertia::render('Dashboard', $viewModel->toArray());
    }
}
