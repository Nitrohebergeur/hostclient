<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TicketCategory;

class TicketCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Support Général',
                'slug' => 'support-general',
                'description' => 'Questions générales et support',
                'sort_order' => 1,
            ],
            [
                'name' => 'Problème Technique',
                'slug' => 'probleme-technique',
                'description' => 'Problèmes techniques et pannes',
                'sort_order' => 2,
            ],
            [
                'name' => 'Facturation',
                'slug' => 'facturation',
                'description' => 'Questions concernant la facturation et les paiements',
                'sort_order' => 3,
            ],
            [
                'name' => 'Ventes & Produits',
                'slug' => 'ventes-produits',
                'description' => 'Questions sur les produits et services disponibles',
                'sort_order' => 4,
            ],
            [
                'name' => 'Abus & Sécurité',
                'slug' => 'abus-securite',
                'description' => 'Signalement d\'abus et questions de sécurité',
                'sort_order' => 5,
            ],
            [
                'name' => 'Migration & Transfert',
                'slug' => 'migration-transfert',
                'description' => 'Migration de services et transfert de données',
                'sort_order' => 6,
            ],
        ];

        foreach ($categories as $category) {
            TicketCategory::firstOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }

        $this->command->info('✅ Ticket categories seeded successfully');
    }
}
