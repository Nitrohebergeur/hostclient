<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Permissions ────────────────────────────────────────────
        $permissions = [
            // Clients
            'view clients', 'create clients', 'edit clients', 'delete clients',
            // Services
            'view services', 'create services', 'edit services', 'delete services',
            'suspend services', 'activate services',
            // Factures
            'view invoices', 'create invoices', 'edit invoices', 'delete invoices',
            'mark invoices paid', 'refund invoices',
            // Tickets
            'view tickets', 'reply tickets', 'close tickets', 'assign tickets',
            // Produits
            'view products', 'create products', 'edit products', 'delete products',
            // Paramètres
            'view settings', 'edit settings',
            // Plugins
            'manage plugins', 'manage themes',
            // Journaux
            'view logs', 'view audit logs',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // ── Rôles ───────────────────────────────────────────────────
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->syncPermissions(Permission::all());

        $support = Role::firstOrCreate(['name' => 'support', 'guard_name' => 'web']);
        $support->syncPermissions([
            'view clients', 'view services', 'view invoices',
            'view tickets', 'reply tickets', 'close tickets', 'assign tickets',
        ]);

        Role::firstOrCreate(['name' => 'client', 'guard_name' => 'web']);

        // ── Produits, Serveurs, Passerelles de paiement ─────────────
        $this->call([
            ProductSeeder::class,
            ServerSeeder::class,
            PaymentGatewaySeeder::class,
            CurrencySeeder::class,
            SystemSettingSeeder::class,
        ]);

        $this->command->info('✅ Seeder termine !');
    }
}
