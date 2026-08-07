<?php

namespace Database\Seeders;

use App\Models\Server;
use Illuminate\Database\Seeder;

class ServerSeeder extends Seeder
{
    public function run(): void
    {
        Server::create([
            'name' => 'Main Pterodactyl Panel',
            'type' => 'pterodactyl',
            'hostname' => 'panel.example.com',
            'port' => 443,
            'use_ssl' => true,
            'api_key' => 'ptlc_changeme',
            'config' => [
                'nest_id' => 1,
                'egg_id' => 1,
                'allocation_id' => null,
                'node_id' => null,
            ],
            'is_active' => false,
            'max_accounts' => null,
            'current_accounts' => 0,
            'status' => 'offline',
            'notes' => 'Example Pterodactyl server — configure before activating.',
        ]);

        Server::create([
            'name' => 'Main cPanel Server',
            'type' => 'cpanel',
            'hostname' => 'whm.example.com',
            'port' => 2087,
            'use_ssl' => true,
            'username' => 'root',
            'api_key' => 'changeme',
            'config' => [
                'package' => 'default',
                'ip' => null,
            ],
            'is_active' => false,
            'max_accounts' => 500,
            'current_accounts' => 0,
            'status' => 'offline',
            'notes' => 'Example cPanel/WHM server — configure before activating.',
        ]);

        Server::create([
            'name' => 'Proxmox Cluster',
            'type' => 'proxmox',
            'hostname' => 'proxmox.example.com',
            'port' => 8006,
            'use_ssl' => true,
            'username' => 'root@pam',
            'api_key' => 'changeme',
            'config' => [
                'node' => 'pve',
                'storage' => 'local-lvm',
                'template_id' => 100,
            ],
            'is_active' => false,
            'max_accounts' => null,
            'current_accounts' => 0,
            'status' => 'offline',
            'notes' => 'Example Proxmox server — configure before activating.',
        ]);
    }
}
