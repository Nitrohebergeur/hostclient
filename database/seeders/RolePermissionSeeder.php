<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Client permissions
            'view own services',
            'manage own services',
            'view own invoices',
            'pay invoices',
            'create tickets',
            'view own tickets',
            'reply to own tickets',
            'manage own profile',
            'create orders',
            'view own orders',
            'generate api keys',
            
            // Admin permissions
            'view all clients',
            'manage clients',
            'view all services',
            'manage services',
            'activate services',
            'suspend services',
            'terminate services',
            'view all invoices',
            'manage invoices',
            'create invoices',
            'view all orders',
            'manage orders',
            'view all tickets',
            'manage tickets',
            'assign tickets',
            'view all transactions',
            'manage transactions',
            'view products',
            'manage products',
            'view categories',
            'manage categories',
            'view coupons',
            'manage coupons',
            'view payment gateways',
            'manage payment gateways',
            'view settings',
            'manage settings',
            'view modules',
            'manage modules',
            'view users',
            'manage users',
            'view roles',
            'manage roles',
            'view activity log',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        
        // Client role
        $clientRole = Role::firstOrCreate(['name' => 'client']);
        $clientRole->givePermissionTo([
            'view own services',
            'manage own services',
            'view own invoices',
            'pay invoices',
            'create tickets',
            'view own tickets',
            'reply to own tickets',
            'manage own profile',
            'create orders',
            'view own orders',
            'generate api keys',
        ]);

        // Support role
        $supportRole = Role::firstOrCreate(['name' => 'support']);
        $supportRole->givePermissionTo([
            'view all clients',
            'view all services',
            'view all invoices',
            'view all orders',
            'view all tickets',
            'manage tickets',
            'assign tickets',
            'view all transactions',
            'view products',
            'view categories',
        ]);

        // Admin role - gets all permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        $this->command->info('✅ Roles and permissions created successfully');
    }
}
