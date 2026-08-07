<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
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

        // ── Utilisateur Admin ────────────────────────────────────────
        $adminUser = User::updateOrCreate(
            ['email' => 'admin@hostclient.io'],
            [
                'name'     => 'Administrateur',
                'password' => Hash::make('Admin@HostClient2024!'),
                'status'   => 'active',
                'email_verified_at' => now(),
            ]
        );
        $adminUser->assignRole('admin');

        // ── Utilisateur Demo Client ──────────────────────────────────
        $demoClient = User::updateOrCreate(
            ['email' => 'client@hostclient.io'],
            [
                'name'     => 'Client Demo',
                'password' => Hash::make('Client@HostClient2024!'),
                'status'   => 'active',
                'country'  => 'FR',
                'city'     => 'Paris',
                'email_verified_at' => now(),
            ]
        );
        $demoClient->assignRole('client');

        // ── Produits, Serveurs, Passerelles de paiement ─────────────
        $this->call([
            ProductSeeder::class,
            ServerSeeder::class,
            PaymentGatewaySeeder::class,
            SystemSettingSeeder::class,
        ]);

        $this->command->info('✅ Seeder terminé !');
        $this->command->table(
            ['Rôle', 'Email', 'Mot de passe'],
            [
                ['admin',  'admin@hostclient.io',  'Admin@HostClient2024!'],
                ['client', 'client@hostclient.io', 'Client@HostClient2024!'],
            ]
        );
    }
}
