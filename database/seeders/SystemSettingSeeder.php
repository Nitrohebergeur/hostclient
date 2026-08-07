<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use Illuminate\Database\Seeder;

class SystemSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // Général
            ['key' => 'app_name', 'value' => 'HostClient', 'type' => 'string', 'group' => 'general'],
            ['key' => 'app_url', 'value' => 'https://yourdomain.com', 'type' => 'string', 'group' => 'general'],
            ['key' => 'app_logo', 'value' => '', 'type' => 'string', 'group' => 'general'],
            ['key' => 'app_currency', 'value' => 'EUR', 'type' => 'string', 'group' => 'general'],
            ['key' => 'app_timezone', 'value' => 'Europe/Paris', 'type' => 'string', 'group' => 'general'],
            ['key' => 'app_language', 'value' => 'fr', 'type' => 'string', 'group' => 'general'],

            // Facturation
            ['key' => 'invoice_prefix', 'value' => 'INV', 'type' => 'string', 'group' => 'billing'],
            ['key' => 'tax_rate', 'value' => '20', 'type' => 'float', 'group' => 'billing'],
            ['key' => 'tax_name', 'value' => 'TVA', 'type' => 'string', 'group' => 'billing'],
            ['key' => 'invoice_due_days', 'value' => '15', 'type' => 'integer', 'group' => 'billing'],
            ['key' => 'auto_suspend_days', 'value' => '3', 'type' => 'integer', 'group' => 'billing'],
            ['key' => 'auto_terminate_days', 'value' => '30', 'type' => 'integer', 'group' => 'billing'],

            // Auto-update
            ['key' => 'auto_update_enabled', 'value' => '0', 'type' => 'boolean', 'group' => 'updates'],
            ['key' => 'auto_update_branch', 'value' => 'main', 'type' => 'string', 'group' => 'updates'],
            ['key' => 'auto_update_check_interval', 'value' => 'daily', 'type' => 'string', 'group' => 'updates'],
            ['key' => 'auto_update_backup_enabled', 'value' => '1', 'type' => 'boolean', 'group' => 'updates'],
            ['key' => 'github_webhook_secret', 'value' => '', 'type' => 'string', 'group' => 'updates'],

            // Email
            ['key' => 'mail_from_name', 'value' => 'HostClient', 'type' => 'string', 'group' => 'email'],
            ['key' => 'mail_from_address', 'value' => 'noreply@yourdomain.com', 'type' => 'string', 'group' => 'email'],

            // Sécurité
            ['key' => 'registration_enabled', 'value' => '1', 'type' => 'boolean', 'group' => 'security'],
            ['key' => 'email_verification_required', 'value' => '1', 'type' => 'boolean', 'group' => 'security'],
            ['key' => '2fa_required', 'value' => '0', 'type' => 'boolean', 'group' => 'security'],
        ];

        foreach ($settings as $setting) {
            SystemSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
