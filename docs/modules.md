# 📦 Guide de Développement de Modules

Les modules HostClient permettent d'étendre les fonctionnalités en ajoutant le support de panels de contrôle, fournisseurs cloud, et services tiers.

## 📋 Table des matières

- [Introduction](#introduction)
- [Structure d'un Module](#structure)
- [Créer un Module](#creation)
- [Provisionnement](#provisionnement)
- [API Module](#api)
- [Configuration](#configuration)
- [Installation](#installation)

## Introduction

Un module HostClient est un package Laravel indépendant qui :
- Gère le provisionnement automatique des services
- Fournit des actions de gestion (suspend, unsuspend, terminate)
- Expose une interface de configuration
- Peut avoir ses propres routes, vues et migrations

## Structure d'un Module {#structure}

```
modules/ExempleModule/
├── Config/
│   └── config.php              # Configuration du module
├── Controllers/
│   └── ExempleController.php   # Contrôleurs
├── Models/
│   └── ExempleServer.php       # Modèles
├── Providers/
│   └── ExempleServiceProvider.php
├── Services/
│   └── ExempleService.php      # Logique d'intégration
├── Routes/
│   ├── web.php                 # Routes web
│   └── api.php                 # Routes API
├── Views/
│   ├── admin/                  # Vues admin
│   └── client/                 # Vues client
├── Migrations/
│   └── 2024_01_01_create_exemple_servers_table.php
├── module.json                 # Métadonnées
├── composer.json               # Dépendances
└── README.md
```

## Créer un Module {#creation}

### 1. Génération

```bash
php artisan module:make NomModule
```

### 2. module.json

```json
{
    "name": "Pterodactyl",
    "alias": "pterodactyl",
    "description": "Intégration avec Pterodactyl Panel",
    "keywords": ["game server", "minecraft", "hosting"],
    "version": "1.0.0",
    "active": true,
    "order": 1,
    "providers": [
        "Modules\\Pterodactyl\\Providers\\PterodactylServiceProvider"
    ],
    "requires": {
        "hostclient": "^1.0"
    }
}
```

### 3. Service Provider

```php
<?php

namespace Modules\Pterodactyl\Providers;

use Illuminate\Support\ServiceProvider;

class PterodactylServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'Pterodactyl';
    protected string $moduleNameLower = 'pterodactyl';

    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
    }

    public function boot(): void
    {
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(__DIR__ . '/../Migrations');
    }

    protected function registerConfig(): void
    {
        $this->publishes([
            __DIR__ . '/../Config/config.php' => config_path("{$this->moduleNameLower}.php"),
        ], 'config');

        $this->mergeConfigFrom(
            __DIR__ . '/../Config/config.php',
            $this->moduleNameLower
        );
    }

    protected function registerViews(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../Views', $this->moduleNameLower);
    }
}
```

### 4. Service d'Intégration

```php
<?php

namespace Modules\Pterodactyl\Services;

use App\Contracts\ServerModuleInterface;
use App\Models\Service;
use GuzzleHttp\Client;

class PterodactylService implements ServerModuleInterface
{
    protected Client $client;
    protected string $apiUrl;
    protected string $apiKey;

    public function __construct()
    {
        $this->apiUrl = config('pterodactyl.api_url');
        $this->apiKey = config('pterodactyl.api_key');
        
        $this->client = new Client([
            'base_uri' => $this->apiUrl,
            'headers' => [
                'Authorization' => "Bearer {$this->apiKey}",
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    /**
     * Crée un nouveau serveur
     */
    public function create(Service $service): array
    {
        $response = $this->client->post('/api/application/servers', [
            'json' => [
                'name' => $service->name,
                'user' => $service->user->id,
                'egg' => $service->config['egg'] ?? 1,
                'docker_image' => $service->config['docker_image'] ?? 'ghcr.io/pterodactyl/yolks:java_17',
                'startup' => $service->config['startup'] ?? '',
                'environment' => $service->config['environment'] ?? [],
                'limits' => [
                    'memory' => $service->config['memory'] ?? 1024,
                    'disk' => $service->config['disk'] ?? 5120,
                    'cpu' => $service->config['cpu'] ?? 100,
                    'io' => $service->config['io'] ?? 500,
                ],
                'feature_limits' => [
                    'databases' => $service->config['databases'] ?? 1,
                    'backups' => $service->config['backups'] ?? 1,
                ],
                'allocation' => [
                    'default' => $service->config['allocation'] ?? 1,
                ],
            ],
        ]);

        $data = json_decode($response->getBody(), true);

        return [
            'success' => true,
            'server_id' => $data['attributes']['id'],
            'identifier' => $data['attributes']['identifier'],
            'data' => $data,
        ];
    }

    /**
     * Suspend un serveur
     */
    public function suspend(Service $service): bool
    {
        $this->client->post("/api/application/servers/{$service->identifier}/suspend");
        return true;
    }

    /**
     * Réactive un serveur
     */
    public function unsuspend(Service $service): bool
    {
        $this->client->post("/api/application/servers/{$service->identifier}/unsuspend");
        return true;
    }

    /**
     * Termine (supprime) un serveur
     */
    public function terminate(Service $service): bool
    {
        $this->client->delete("/api/application/servers/{$service->identifier}");
        return true;
    }

    /**
     * Récupère les informations d'un serveur
     */
    public function getServer(Service $service): array
    {
        $response = $this->client->get("/api/application/servers/{$service->identifier}");
        return json_decode($response->getBody(), true);
    }

    /**
     * Change le mot de passe d'un serveur
     */
    public function changePassword(Service $service, string $password): bool
    {
        // Implémentation spécifique
        return true;
    }

    /**
     * Teste la connexion à l'API
     */
    public static function testConnection(array $config): bool
    {
        try {
            $client = new Client([
                'base_uri' => $config['api_url'],
                'headers' => [
                    'Authorization' => "Bearer {$config['api_key']}",
                    'Accept' => 'application/json',
                ],
            ]);

            $response = $client->get('/api/application/nodes');
            return $response->getStatusCode() === 200;
        } catch (\Exception $e) {
            return false;
        }
    }
}
```

## Configuration du Module {#configuration}

```php
<?php

// modules/Pterodactyl/Config/config.php

return [
    'name' => 'Pterodactyl',
    
    // Configuration API
    'api_url' => env('PTERODACTYL_URL', 'https://panel.example.com'),
    'api_key' => env('PTERODACTYL_API_KEY'),
    
    // Configuration par défaut
    'defaults' => [
        'location' => 1,
        'nest' => 1,
        'egg' => 1,
    ],
    
    // Options disponibles
    'eggs' => [
        1 => 'Minecraft Java',
        2 => 'Minecraft Bedrock',
        3 => 'FiveM',
        4 => 'Discord Bot',
    ],
    
    // Champs de configuration pour le produit
    'product_fields' => [
        [
            'name' => 'memory',
            'label' => 'RAM (MB)',
            'type' => 'number',
            'required' => true,
            'default' => 1024,
        ],
        [
            'name' => 'disk',
            'label' => 'Disk Space (MB)',
            'type' => 'number',
            'required' => true,
            'default' => 5120,
        ],
        [
            'name' => 'cpu',
            'label' => 'CPU Limit (%)',
            'type' => 'number',
            'required' => true,
            'default' => 100,
        ],
        [
            'name' => 'databases',
            'label' => 'Databases',
            'type' => 'number',
            'required' => false,
            'default' => 1,
        ],
        [
            'name' => 'backups',
            'label' => 'Backups',
            'type' => 'number',
            'required' => false,
            'default' => 1,
        ],
    ],
];
```

## Installation d'un Module {#installation}

### Via l'interface Admin

1. Admin → Modules
2. Cliquez sur "Installer un module"
3. Uploadez le fichier ZIP du module
4. Configurez les paramètres
5. Activez le module

### Manuellement

```bash
# Téléchargez le module dans modules/
cd modules
git clone https://github.com/exemple/pterodactyl-module.git Pterodactyl

# Installez les dépendances
composer dump-autoload

# Migrez la base de données
php artisan module:migrate Pterodactyl

# Activez le module
php artisan module:enable Pterodactyl
```

## Modules Officiels Disponibles

### 🎮 Panels de Jeux
- **Pterodactyl** - Game server management
- **Multicraft** - Minecraft hosting
- **TCAdmin** - Multi-game control panel

### 🖥️ Virtualisation
- **Proxmox** - VPS & VM management
- **Virtualizor** - VPS control panel
- **SolusVM** - VPS management

### 🌐 Panels Web
- **cPanel** - Web hosting
- **Plesk** - Web hosting
- **DirectAdmin** - Web hosting

### ☁️ Cloud Providers
- **OVH** - Cloud servers
- **Hetzner** - Dedicated & cloud
- **DigitalOcean** - Droplets

### 🔧 Autres
- **Cloudflare** - DNS management
- **Discord** - Bot hosting
- **Database** - MySQL/PostgreSQL as a service

## Commandes Utiles

```bash
# Lister les modules
php artisan module:list

# Créer un module
php artisan module:make NomModule

# Activer un module
php artisan module:enable NomModule

# Désactiver un module
php artisan module:disable NomModule

# Migrer un module
php artisan module:migrate NomModule

# Publier les assets
php artisan module:publish NomModule
```

## Bonnes Pratiques

1. **Gestion d'erreurs** : Toujours gérer les exceptions API
2. **Logs** : Logger toutes les actions importantes
3. **Tests** : Tester les connexions avant provisionnement
4. **Validation** : Valider toutes les entrées
5. **Documentation** : Documenter l'API du module
6. **Sécurité** : Ne jamais exposer les clés API

## Support

- 📖 Documentation API : Consultez la doc du service intégré
- 💬 Communauté : Discord HostClient
- 🐛 Issues : GitHub
