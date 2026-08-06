<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductCategory;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Hébergement Web',
                'slug' => 'hebergement-web',
                'description' => 'Hébergement web partagé et mutualisé',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Serveurs VPS',
                'slug' => 'serveurs-vps',
                'description' => 'Serveurs privés virtuels',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Serveurs Dédiés',
                'slug' => 'serveurs-dedies',
                'description' => 'Serveurs dédiés haute performance',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Game Servers',
                'slug' => 'game-servers',
                'description' => 'Serveurs de jeux Minecraft, FiveM, Rust, etc.',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Bots Discord',
                'slug' => 'bots-discord',
                'description' => 'Hébergement de bots Discord',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Bases de Données',
                'slug' => 'bases-de-donnees',
                'description' => 'Bases de données MySQL, PostgreSQL, MongoDB',
                'sort_order' => 6,
                'is_active' => true,
            ],
            [
                'name' => 'Noms de Domaine',
                'slug' => 'noms-de-domaine',
                'description' => 'Enregistrement et gestion de domaines',
                'sort_order' => 7,
                'is_active' => true,
            ],
            [
                'name' => 'Services Additionnels',
                'slug' => 'services-additionnels',
                'description' => 'Services et options supplémentaires',
                'sort_order' => 99,
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            ProductCategory::firstOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }

        $this->command->info('✅ Product categories seeded successfully');
    }
}
