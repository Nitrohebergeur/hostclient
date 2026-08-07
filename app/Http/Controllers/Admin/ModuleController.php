<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ModuleController extends Controller
{
    protected string $modulesPath;

    public function __construct()
    {
        $this->modulesPath = base_path('modules');
    }

    public function index()
    {
        $modules = $this->getModules();

        return view('admin.modules.index', compact('modules'));
    }

    public function install(string $module)
    {
        $modulePath = $this->modulesPath . '/' . $module;

        if (!File::exists($modulePath)) {
            return back()->with('error', 'Module introuvable.');
        }

        $enabled = config('hostclient.modules.enabled', []);
        if (!in_array($module, $enabled)) {
            $enabled[] = $module;
            Setting::set('modules_enabled', $enabled, 'json', 'modules');
        }

        // Run module migrations if they exist
        $migrationsPath = $modulePath . '/Migrations';
        if (File::exists($migrationsPath)) {
            \Artisan::call('migrate', ['--path' => "modules/{$module}/Migrations", '--force' => true]);
        }

        return back()->with('success', "Module {$module} installé.");
    }

    public function uninstall(string $module)
    {
        $enabled = config('hostclient.modules.enabled', []);
        $enabled = array_filter($enabled, fn($m) => $m !== $module);

        Setting::set('modules_enabled', array_values($enabled), 'json', 'modules');

        return back()->with('success', "Module {$module} désinstallé.");
    }

    public function toggle(string $module)
    {
        $enabled = config('hostclient.modules.enabled', []);

        if (in_array($module, $enabled)) {
            $enabled = array_filter($enabled, fn($m) => $m !== $module);
            $message = "Module {$module} désactivé.";
        } else {
            $enabled[] = $module;
            $message = "Module {$module} activé.";
        }

        Setting::set('modules_enabled', array_values($enabled), 'json', 'modules');

        return back()->with('success', $message);
    }

    protected function getModules(): array
    {
        $modules = [];

        if (!File::exists($this->modulesPath)) {
            return $modules;
        }

        foreach (File::directories($this->modulesPath) as $path) {
            $name       = basename($path);
            $configFile = $path . '/module.json';
            $config     = File::exists($configFile) ? json_decode(File::get($configFile), true) : [];

            $modules[] = [
                'name'        => $name,
                'display'     => $config['name'] ?? $name,
                'description' => $config['description'] ?? '',
                'version'     => $config['version'] ?? '1.0.0',
                'author'      => $config['author'] ?? 'Unknown',
                'enabled'     => in_array($name, config('hostclient.modules.enabled', [])),
            ];
        }

        return $modules;
    }
}
