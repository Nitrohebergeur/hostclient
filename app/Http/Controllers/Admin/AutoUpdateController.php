<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AutoUpdate;
use App\Models\SystemSetting;
use App\Services\AutoUpdateService;
use Illuminate\Http\Request;

class AutoUpdateController extends Controller
{
    public function __construct(
        protected AutoUpdateService $updateService
    ) {}

    public function index()
    {
        $updates = AutoUpdate::orderBy('created_at', 'desc')->paginate(20);
        
        $settings = [
            'auto_update_enabled' => SystemSetting::get('auto_update_enabled', false),
            'auto_update_branch' => SystemSetting::get('auto_update_branch', 'main'),
            'auto_update_check_interval' => SystemSetting::get('auto_update_check_interval', 'daily'),
            'auto_update_backup_enabled' => SystemSetting::get('auto_update_backup_enabled', true),
        ];

        $currentVersion = $this->updateService->getCurrentVersion();
        $latestVersion = null;
        
        try {
            $latestVersion = $this->updateService->getLatestVersion();
        } catch (\Exception $e) {
            // Ignorer l'erreur
        }

        return view('admin.auto-updates.index', compact('updates', 'settings', 'currentVersion', 'latestVersion'));
    }

    public function checkForUpdates()
    {
        try {
            $latestVersion = $this->updateService->getLatestVersion();
            $currentVersion = $this->updateService->getCurrentVersion();

            if (version_compare($latestVersion['version'], $currentVersion, '>')) {
                return redirect()
                    ->back()
                    ->with('info', "New version available: {$latestVersion['version']}");
            }

            return redirect()
                ->back()
                ->with('success', 'You are running the latest version.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to check for updates: ' . $e->getMessage());
        }
    }

    public function download(Request $request)
    {
        try {
            $version = $request->input('version');
            $commitHash = $request->input('commit_hash');
            $branch = $request->input('branch', 'main');

            $update = AutoUpdate::create([
                'version' => $version,
                'commit_hash' => $commitHash,
                'branch' => $branch,
                'status' => 'downloading',
                'started_at' => now(),
            ]);

            // Lancer le téléchargement en arrière-plan
            $this->updateService->downloadUpdate($update);

            return redirect()
                ->back()
                ->with('success', 'Update download started.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to start download: ' . $e->getMessage());
        }
    }

    public function install(AutoUpdate $update)
    {
        try {
            if ($update->status !== 'pending') {
                return redirect()
                    ->back()
                    ->with('error', 'Update is not ready for installation.');
            }

            $this->updateService->installUpdate($update);

            return redirect()
                ->back()
                ->with('success', 'Update installed successfully. Please restart the application.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to install update: ' . $e->getMessage());
        }
    }

    public function rollback(AutoUpdate $update)
    {
        try {
            if ($update->status !== 'completed') {
                return redirect()
                    ->back()
                    ->with('error', 'Cannot rollback this update.');
            }

            $this->updateService->rollbackUpdate($update);

            return redirect()
                ->back()
                ->with('success', 'Update rolled back successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to rollback update: ' . $e->getMessage());
        }
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'auto_update_enabled' => 'boolean',
            'auto_update_branch' => 'required|in:main,develop,stable',
            'auto_update_check_interval' => 'required|in:hourly,daily,weekly',
            'auto_update_backup_enabled' => 'boolean',
        ]);

        foreach ($validated as $key => $value) {
            $type = is_bool($value) ? 'boolean' : 'string';
            SystemSetting::set($key, $value, $type, 'updates');
        }

        return redirect()
            ->back()
            ->with('success', 'Auto-update settings saved.');
    }
}
