<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Créer des catégories
        $webHosting = ProductCategory::create([
            'name' => 'Web Hosting',
            'slug' => 'web-hosting',
            'description' => 'Shared web hosting plans',
            'icon' => 'fas fa-globe',
            'order' => 1,
            'is_active' => true,
        ]);

        $vps = ProductCategory::create([
            'name' => 'VPS',
            'slug' => 'vps',
            'description' => 'Virtual Private Servers',
            'icon' => 'fas fa-server',
            'order' => 2,
            'is_active' => true,
        ]);

        $gameServers = ProductCategory::create([
            'name' => 'Game Servers',
            'slug' => 'game-servers',
            'description' => 'Gaming server hosting',
            'icon' => 'fas fa-gamepad',
            'order' => 3,
            'is_active' => true,
        ]);

        // Produits Web Hosting
        Product::create([
            'category_id' => $webHosting->id,
            'name' => 'Starter Hosting',
            'slug' => 'starter-hosting',
            'description' => 'Perfect for small websites and blogs',
            'type' => 'hosting',
            'module' => 'cpanel',
            'allow_hourly_billing' => false,
            'price_hourly' => 0,
            'price_monthly' => 4.99,
            'price_quarterly' => 13.99,
            'price_semiannually' => 26.99,
            'price_annually' => 49.99,
            'price_biennially' => 89.99,
            'setup_fee' => 0,
            'currency' => 'EUR',
            'resources' => [
                'disk' => '10 GB SSD',
                'bandwidth' => '100 GB',
                'domains' => 1,
                'databases' => 5,
                'email_accounts' => 10,
                'ftp_accounts' => 5,
            ],
            'order' => 1,
            'is_active' => true,
            'is_featured' => false,
            'auto_provision' => true,
        ]);

        Product::create([
            'category_id' => $webHosting->id,
            'name' => 'Business Hosting',
            'slug' => 'business-hosting',
            'description' => 'Ideal for growing businesses',
            'type' => 'hosting',
            'module' => 'cpanel',
            'allow_hourly_billing' => false,
            'price_hourly' => 0,
            'price_monthly' => 9.99,
            'price_quarterly' => 27.99,
            'price_semiannually' => 54.99,
            'price_annually' => 99.99,
            'price_biennially' => 179.99,
            'setup_fee' => 0,
            'currency' => 'EUR',
            'resources' => [
                'disk' => '50 GB SSD',
                'bandwidth' => 'Unlimited',
                'domains' => 10,
                'databases' => 'Unlimited',
                'email_accounts' => 'Unlimited',
                'ftp_accounts' => 'Unlimited',
            ],
            'order' => 2,
            'is_active' => true,
            'is_featured' => true,
            'auto_provision' => true,
        ]);

        // Produits VPS avec facturation horaire
        Product::create([
            'category_id' => $vps->id,
            'name' => 'VPS Basic',
            'slug' => 'vps-basic',
            'description' => 'Entry-level VPS with full root access',
            'type' => 'vps',
            'module' => 'proxmox',
            'allow_hourly_billing' => true,
            'price_hourly' => 0.0149,
            'price_monthly' => 9.99,
            'price_quarterly' => 27.99,
            'price_semiannually' => 54.99,
            'price_annually' => 99.99,
            'price_biennially' => 179.99,
            'setup_fee' => 0,
            'currency' => 'EUR',
            'resources' => [
                'cpu' => '2 vCores',
                'ram' => '2 GB',
                'disk' => '40 GB SSD',
                'bandwidth' => '2 TB',
                'ipv4' => 1,
                'ipv6' => true,
            ],
            'order' => 1,
            'is_active' => true,
            'is_featured' => false,
            'auto_provision' => true,
        ]);

        Product::create([
            'category_id' => $vps->id,
            'name' => 'VPS Pro',
            'slug' => 'vps-pro',
            'description' => 'Professional VPS with enhanced resources',
            'type' => 'vps',
            'module' => 'proxmox',
            'allow_hourly_billing' => true,
            'price_hourly' => 0.0299,
            'price_monthly' => 19.99,
            'price_quarterly' => 56.99,
            'price_semiannually' => 109.99,
            'price_annually' => 199.99,
            'price_biennially' => 359.99,
            'setup_fee' => 0,
            'currency' => 'EUR',
            'resources' => [
                'cpu' => '4 vCores',
                'ram' => '8 GB',
                'disk' => '120 GB SSD',
                'bandwidth' => '4 TB',
                'ipv4' => 1,
                'ipv6' => true,
            ],
            'order' => 2,
            'is_active' => true,
            'is_featured' => true,
            'auto_provision' => true,
        ]);

        // Produits Game Servers avec facturation horaire
        Product::create([
            'category_id' => $gameServers->id,
            'name' => 'Minecraft Server',
            'slug' => 'minecraft-server',
            'description' => 'High-performance Minecraft server hosting',
            'type' => 'game',
            'module' => 'pterodactyl',
            'allow_hourly_billing' => true,
            'price_hourly' => 0.0099,
            'price_monthly' => 6.99,
            'price_quarterly' => 19.99,
            'price_semiannually' => 37.99,
            'price_annually' => 69.99,
            'price_biennially' => 129.99,
            'setup_fee' => 0,
            'currency' => 'EUR',
            'resources' => [
                'cpu' => '2 vCores',
                'ram' => '4 GB',
                'disk' => '20 GB NVMe',
                'slots' => '20 players',
                'backups' => 'Daily',
                'ddos_protection' => true,
            ],
            'order' => 1,
            'is_active' => true,
            'is_featured' => false,
            'auto_provision' => true,
        ]);

        Product::create([
            'category_id' => $gameServers->id,
            'name' => 'ARK: Survival Evolved',
            'slug' => 'ark-server',
            'description' => 'ARK server with instant setup',
            'type' => 'game',
            'module' => 'pterodactyl',
            'allow_hourly_billing' => true,
            'price_hourly' => 0.0199,
            'price_monthly' => 13.99,
            'price_quarterly' => 39.99,
            'price_semiannually' => 76.99,
            'price_annually' => 139.99,
            'price_biennially' => 259.99,
            'setup_fee' => 0,
            'currency' => 'EUR',
            'resources' => [
                'cpu' => '4 vCores',
                'ram' => '8 GB',
                'disk' => '40 GB NVMe',
                'slots' => '50 players',
                'backups' => 'Daily',
                'ddos_protection' => true,
                'mod_support' => true,
            ],
            'order' => 2,
            'is_active' => true,
            'is_featured' => true,
            'auto_provision' => true,
        ]);
    }
}
