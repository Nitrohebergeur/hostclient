<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class HomePageSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $controller = new \App\Http\Controllers\Admin\HomePageController();
        
        // Utiliser les méthodes protégées via Reflection pour obtenir le HTML et CSS par défaut
        $reflection = new \ReflectionClass($controller);
        
        $htmlMethod = $reflection->getMethod('getDefaultHtml');
        $htmlMethod->setAccessible(true);
        $defaultHtml = $htmlMethod->invoke($controller);
        
        $cssMethod = $reflection->getMethod('getDefaultCss');
        $cssMethod->setAccessible(true);
        $defaultCss = $cssMethod->invoke($controller);

        Setting::set('homepage_html', $defaultHtml, 'string', 'homepage');
        Setting::set('homepage_css', $defaultCss, 'string', 'homepage');

        $this->command->info('Homepage settings created successfully!');
    }
}
