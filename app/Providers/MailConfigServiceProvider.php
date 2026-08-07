<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use App\Models\SystemSetting;

class MailConfigServiceProvider extends ServiceProvider
{
    /**
     * Charge la configuration SMTP depuis la base de données
     * et l'applique à la config runtime Laravel.
     * Cela permet de changer le SMTP depuis le panel admin sans modifier .env.
     */
    public function boot(): void
    {
        try {
            $map = [
                'mail_host'         => 'mail.mailers.smtp.host',
                'mail_port'         => 'mail.mailers.smtp.port',
                'mail_username'     => 'mail.mailers.smtp.username',
                'mail_password'     => 'mail.mailers.smtp.password',
                'mail_encryption'   => 'mail.mailers.smtp.encryption',
                'mail_from_address' => 'mail.from.address',
                'mail_from_name'    => 'mail.from.name',
                'mail_mailer'       => 'mail.default',
            ];

            foreach ($map as $dbKey => $configKey) {
                $value = SystemSetting::get($dbKey);
                if ($value !== null && $value !== '') {
                    Config::set($configKey, $value);
                }
            }
        } catch (\Exception $e) {
            // Si la BDD n'est pas encore disponible (ex: premiere installation),
            // on laisse la config .env par defaut sans planter l'app.
        }
    }
}
