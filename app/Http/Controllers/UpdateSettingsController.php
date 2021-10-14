<?php

namespace App\Http\Controllers;

use Codedge\Updater\UpdaterManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Inertia\Response;

class UpdateSettingsController extends Controller
{
    public function show(UpdaterManager $updaterManager): Response
    {
        $currentVersion = $updaterManager->source()->getVersionInstalled();
        $newVersion = Cache::get('new-version');

        if(version_compare($currentVersion, $newVersion) >= 0){
            Cache::forget('new-version');
            $newVersion = null;
        }

        return Inertia::render('Settings/Update', [
            'currentVersion' => $currentVersion,
            'newVersion' => $newVersion
        ]);
    }

    public function updateCheck(UpdaterManager $updaterManager): RedirectResponse
    {
        Cache::forget('new-version');

        $newVersion = Cache::rememberForever('new-version', function () use ($updaterManager) {
            return $updaterManager->source()->getVersionAvailable();
        });

        return redirect()->back();
    }

    public function installUpdate(UpdaterManager $updater): RedirectResponse
    {
        if ($updater->source()->isNewVersionAvailable()) {

            // Get the current installed version
            echo $updater->source()->getVersionInstalled();

            // Get the new version available
            $versionAvailable = $updater->source()->getVersionAvailable();

            // Create a release
            $release = $updater->source()->fetch($versionAvailable);

            // Run the update process
            $updater->source()->update($release);

        }

        return redirect()->back();
    }
}
