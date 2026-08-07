<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class SettingController extends Controller
{
    public function index(): View
    {
        $settings = SystemSetting::all()->mapWithKeys(fn($s) => [$s->key => $s->value]);
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $group = $request->input('group', 'general');

        foreach ($request->except(['_token', '_method', 'group']) as $key => $value) {
            SystemSetting::set($key, $value ?? '', 'string', $group);
        }

        // Si c'est la config email, mettre à jour le .env aussi
        if ($group === 'email') {
            $this->updateEnvEmail($request);
        }

        Artisan::call('config:clear');

        return back()->with('success', 'Paramètres enregistrés avec succès.');
    }

    public function testEmail(Request $request): RedirectResponse
    {
        $request->validate(['test_email' => 'required|email']);

        // Appliquer la config SMTP depuis la BDD à la volée
        $this->applyMailConfig();

        try {
            Mail::raw('Test email depuis HostClient — la configuration SMTP fonctionne correctement.', function ($message) use ($request) {
                $from = SystemSetting::get('mail_from_address', config('mail.from.address'));
                $name = SystemSetting::get('mail_from_name', 'HostClient');
                $message->to($request->test_email)
                        ->from($from, $name)
                        ->subject('Test Email — HostClient');
            });

            return back()->with('success', 'Email de test envoyé à ' . $request->test_email);
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur d\'envoi : ' . $e->getMessage());
        }
    }

    /**
     * Applique la config mail stockée en BDD à la config runtime Laravel
     */
    protected function applyMailConfig(): void
    {
        $map = [
            'mail_host'          => 'mail.mailers.smtp.host',
            'mail_port'          => 'mail.mailers.smtp.port',
            'mail_username'      => 'mail.mailers.smtp.username',
            'mail_password'      => 'mail.mailers.smtp.password',
            'mail_encryption'    => 'mail.mailers.smtp.encryption',
            'mail_from_address'  => 'mail.from.address',
            'mail_from_name'     => 'mail.from.name',
        ];

        foreach ($map as $dbKey => $configKey) {
            $value = SystemSetting::get($dbKey);
            if ($value !== null) {
                Config::set($configKey, $value);
            }
        }
    }

    /**
     * Met à jour le fichier .env avec les paramètres SMTP
     */
    protected function updateEnvEmail(Request $request): void
    {
        $envPath = base_path('.env');
        if (!file_exists($envPath)) return;

        $env = file_get_contents($envPath);

        $map = [
            'MAIL_HOST'         => $request->input('mail_host', ''),
            'MAIL_PORT'         => $request->input('mail_port', '587'),
            'MAIL_USERNAME'     => $request->input('mail_username', ''),
            'MAIL_PASSWORD'     => $request->input('mail_password', ''),
            'MAIL_ENCRYPTION'   => $request->input('mail_encryption', 'tls'),
            'MAIL_FROM_ADDRESS' => $request->input('mail_from_address', ''),
            'MAIL_FROM_NAME'    => '"' . $request->input('mail_from_name', 'HostClient') . '"',
        ];

        foreach ($map as $key => $value) {
            if (preg_match("/^{$key}=/m", $env)) {
                $env = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $env);
            } else {
                $env .= "\n{$key}={$value}";
            }
        }

        file_put_contents($envPath, $env);
    }
}
