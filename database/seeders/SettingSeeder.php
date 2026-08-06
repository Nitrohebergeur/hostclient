<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General Settings
            ['key' => 'company_name', 'value' => env('HOSTCLIENT_COMPANY_NAME', 'HostClient'), 'type' => 'string', 'group' => 'general'],
            ['key' => 'company_email', 'value' => env('MAIL_FROM_ADDRESS', 'contact@hostclient.local'), 'type' => 'string', 'group' => 'general'],
            ['key' => 'company_phone', 'value' => '', 'type' => 'string', 'group' => 'general'],
            ['key' => 'company_address', 'value' => '', 'type' => 'string', 'group' => 'general'],
            ['key' => 'company_city', 'value' => '', 'type' => 'string', 'group' => 'general'],
            ['key' => 'company_country', 'value' => 'FR', 'type' => 'string', 'group' => 'general'],
            ['key' => 'company_logo', 'value' => '', 'type' => 'string', 'group' => 'general'],
            ['key' => 'company_favicon', 'value' => '', 'type' => 'string', 'group' => 'general'],
            
            // Localization
            ['key' => 'default_currency', 'value' => env('HOSTCLIENT_CURRENCY', 'EUR'), 'type' => 'string', 'group' => 'localization'],
            ['key' => 'default_locale', 'value' => env('HOSTCLIENT_LOCALE', 'fr'), 'type' => 'string', 'group' => 'localization'],
            ['key' => 'default_timezone', 'value' => env('HOSTCLIENT_TIMEZONE', 'Europe/Paris'), 'type' => 'string', 'group' => 'localization'],
            ['key' => 'date_format', 'value' => 'd/m/Y', 'type' => 'string', 'group' => 'localization'],
            ['key' => 'time_format', 'value' => 'H:i', 'type' => 'string', 'group' => 'localization'],
            
            // Billing Settings
            ['key' => 'tax_enabled', 'value' => 'true', 'type' => 'boolean', 'group' => 'billing'],
            ['key' => 'tax_rate', 'value' => env('HOSTCLIENT_TAX_RATE', '20.00'), 'type' => 'decimal', 'group' => 'billing'],
            ['key' => 'tax_name', 'value' => 'TVA', 'type' => 'string', 'group' => 'billing'],
            ['key' => 'invoice_prefix', 'value' => env('HOSTCLIENT_INVOICE_PREFIX', 'INV-'), 'type' => 'string', 'group' => 'billing'],
            ['key' => 'invoice_due_days', 'value' => '14', 'type' => 'integer', 'group' => 'billing'],
            ['key' => 'invoice_notes', 'value' => 'Merci pour votre confiance.', 'type' => 'string', 'group' => 'billing'],
            ['key' => 'invoice_terms', 'value' => 'Paiement sous 14 jours.', 'type' => 'string', 'group' => 'billing'],
            ['key' => 'auto_generate_invoices', 'value' => 'true', 'type' => 'boolean', 'group' => 'billing'],
            ['key' => 'invoice_generation_days_before', 'value' => '7', 'type' => 'integer', 'group' => 'billing'],
            
            // Service Settings
            ['key' => 'auto_suspend_enabled', 'value' => 'true', 'type' => 'boolean', 'group' => 'services'],
            ['key' => 'auto_suspend_days', 'value' => env('HOSTCLIENT_AUTO_SUSPEND_DAYS', '7'), 'type' => 'integer', 'group' => 'services'],
            ['key' => 'auto_terminate_enabled', 'value' => 'true', 'type' => 'boolean', 'group' => 'services'],
            ['key' => 'auto_terminate_days', 'value' => env('HOSTCLIENT_AUTO_TERMINATE_DAYS', '14'), 'type' => 'integer', 'group' => 'services'],
            ['key' => 'service_renewal_enabled', 'value' => 'true', 'type' => 'boolean', 'group' => 'services'],
            
            // Email Settings
            ['key' => 'email_from_name', 'value' => env('MAIL_FROM_NAME', 'HostClient'), 'type' => 'string', 'group' => 'email'],
            ['key' => 'email_from_address', 'value' => env('MAIL_FROM_ADDRESS', 'noreply@hostclient.local'), 'type' => 'string', 'group' => 'email'],
            ['key' => 'email_notifications_enabled', 'value' => 'true', 'type' => 'boolean', 'group' => 'email'],
            ['key' => 'email_new_order', 'value' => 'true', 'type' => 'boolean', 'group' => 'email'],
            ['key' => 'email_invoice_created', 'value' => 'true', 'type' => 'boolean', 'group' => 'email'],
            ['key' => 'email_invoice_paid', 'value' => 'true', 'type' => 'boolean', 'group' => 'email'],
            ['key' => 'email_payment_reminder', 'value' => 'true', 'type' => 'boolean', 'group' => 'email'],
            ['key' => 'email_service_activated', 'value' => 'true', 'type' => 'boolean', 'group' => 'email'],
            ['key' => 'email_service_suspended', 'value' => 'true', 'type' => 'boolean', 'group' => 'email'],
            ['key' => 'email_service_terminated', 'value' => 'true', 'type' => 'boolean', 'group' => 'email'],
            ['key' => 'email_ticket_created', 'value' => 'true', 'type' => 'boolean', 'group' => 'email'],
            ['key' => 'email_ticket_reply', 'value' => 'true', 'type' => 'boolean', 'group' => 'email'],
            
            // Security Settings
            ['key' => 'registration_enabled', 'value' => 'true', 'type' => 'boolean', 'group' => 'security'],
            ['key' => 'email_verification_required', 'value' => 'true', 'type' => 'boolean', 'group' => 'security'],
            ['key' => 'two_factor_enabled', 'value' => 'false', 'type' => 'boolean', 'group' => 'security'],
            ['key' => 'password_min_length', 'value' => '8', 'type' => 'integer', 'group' => 'security'],
            ['key' => 'session_lifetime', 'value' => '120', 'type' => 'integer', 'group' => 'security'],
            
            // Ticket Settings
            ['key' => 'ticket_prefix', 'value' => 'TKT-', 'type' => 'string', 'group' => 'tickets'],
            ['key' => 'ticket_auto_close_days', 'value' => '7', 'type' => 'integer', 'group' => 'tickets'],
            ['key' => 'ticket_attachment_enabled', 'value' => 'true', 'type' => 'boolean', 'group' => 'tickets'],
            ['key' => 'ticket_max_attachments', 'value' => '5', 'type' => 'integer', 'group' => 'tickets'],
            ['key' => 'ticket_max_attachment_size', 'value' => '5120', 'type' => 'integer', 'group' => 'tickets'], // KB
            
            // Store Settings
            ['key' => 'store_enabled', 'value' => 'true', 'type' => 'boolean', 'group' => 'store'],
            ['key' => 'store_guest_checkout', 'value' => 'false', 'type' => 'boolean', 'group' => 'store'],
            ['key' => 'store_terms_url', 'value' => '', 'type' => 'string', 'group' => 'store'],
            ['key' => 'store_privacy_url', 'value' => '', 'type' => 'string', 'group' => 'store'],
            
            // Module Settings
            ['key' => 'modules_enabled', 'value' => 'true', 'type' => 'boolean', 'group' => 'modules'],
            
            // Maintenance
            ['key' => 'maintenance_mode', 'value' => 'false', 'type' => 'boolean', 'group' => 'maintenance'],
            ['key' => 'maintenance_message', 'value' => 'Site en maintenance. Nous revenons bientôt.', 'type' => 'string', 'group' => 'maintenance'],
        ];

        foreach ($settings as $setting) {
            Setting::firstOrCreate(
                ['key' => $setting['key']],
                [
                    'value' => $setting['value'],
                    'type' => $setting['type'],
                    'group' => $setting['group'],
                ]
            );
        }

        $this->command->info('✅ Settings seeded successfully');
    }
}
