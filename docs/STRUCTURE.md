# 📁 Structure du Projet HostClient

## Vue d'ensemble

HostClient est structuré comme une application Laravel 12 moderne avec une architecture modulaire.

```
hostclient/
├── app/
│   ├── Console/
│   │   └── Commands/
│   │       └── InstallCommand.php          # Assistant d'installation
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/                      # Contrôleurs admin
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── ClientController.php
│   │   │   │   ├── ProductController.php
│   │   │   │   ├── ServiceController.php
│   │   │   │   ├── OrderController.php
│   │   │   │   ├── InvoiceController.php
│   │   │   │   ├── TicketController.php
│   │   │   │   └── SettingController.php
│   │   │   ├── Client/                     # Contrôleurs client
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── ServiceController.php
│   │   │   │   ├── InvoiceController.php
│   │   │   │   └── TicketController.php
│   │   │   └── Api/V1/                     # API REST
│   │   ├── Middleware/
│   │   └── Requests/
│   ├── Models/
│   │   ├── User.php                        # Utilisateurs
│   │   ├── Product.php                     # Produits
│   │   ├── ProductCategory.php             # Catégories
│   │   ├── Service.php                     # Services clients
│   │   ├── Order.php                       # Commandes
│   │   ├── Invoice.php                     # Factures
│   │   ├── Ticket.php                      # Tickets support
│   │   ├── Transaction.php                 # Transactions
│   │   └── Coupon.php                      # Coupons
│   ├── Services/                           # Services métier
│   │   ├── PaymentService.php
│   │   ├── InvoiceService.php
│   │   └── ServiceProvisionService.php
│   └── Notifications/                      # Notifications
├── bootstrap/
├── config/
│   └── hostclient.php                      # Configuration principale
├── database/
│   ├── factories/
│   ├── migrations/                         # Migrations SQL
│   │   ├── 2024_01_01_000001_create_users_table.php
│   │   ├── 2024_01_01_000002_create_products_table.php
│   │   ├── 2024_01_01_000003_create_services_table.php
│   │   ├── 2024_01_01_000004_create_orders_table.php
│   │   ├── 2024_01_01_000005_create_invoices_table.php
│   │   ├── 2024_01_01_000006_create_payments_table.php
│   │   ├── 2024_01_01_000007_create_tickets_table.php
│   │   ├── 2024_01_01_000008_create_coupons_table.php
│   │   ├── 2024_01_01_000009_create_notifications_table.php
│   │   └── 2024_01_01_000010_create_settings_table.php
│   └── seeders/
├── modules/                                # Modules externes
│   ├── Pterodactyl/
│   ├── Proxmox/
│   ├── cPanel/
│   └── Plesk/
├── public/
│   └── index.php
├── resources/
│   ├── css/
│   │   └── app.css                        # Styles Tailwind
│   ├── js/
│   │   ├── app.js                         # JavaScript principal
│   │   └── bootstrap.js
│   ├── views/
│   │   ├── admin/                         # Vues admin
│   │   ├── client/                        # Vues client
│   │   │   └── dashboard.blade.php
│   │   ├── layouts/
│   │   │   ├── app.blade.php
│   │   │   ├── navigation.blade.php
│   │   │   └── guest.blade.php
│   │   └── welcome.blade.php
│   └── lang/                              # Traductions
│       ├── en/
│       └── fr/
├── routes/
│   ├── web.php                            # Routes web
│   ├── api.php                            # Routes API
│   └── console.php                        # Routes console
├── storage/
│   ├── app/
│   ├── framework/
│   └── logs/
├── tests/
├── .env.example                           # Variables d'environnement
├── composer.json                          # Dépendances PHP
├── package.json                           # Dépendances Node
├── tailwind.config.js                     # Config Tailwind
├── vite.config.js                         # Config Vite
├── docker-compose.yml                     # Config Docker
├── Dockerfile
├── README.md
├── INSTALLATION_GUIDE.md
└── LICENSE
```

## 🗄️ Structure de la Base de Données

### Tables Principales

#### Users
- Utilisateurs (clients et administrateurs)
- Authentification, 2FA, solde

#### Products & Categories
- Produits disponibles
- Catégories de produits
- Tarification, cycles de facturation

#### Services
- Services actifs des clients
- Statut, dates d'expiration
- Configuration spécifique

#### Orders
- Commandes clients
- Items de commande
- Statuts de paiement

#### Invoices
- Factures générées
- Items de facture
- Paiements

#### Tickets
- Tickets de support
- Catégories, priorités
- Réponses et pièces jointes

#### Transactions
- Historique des paiements
- Remboursements, crédits

#### Coupons
- Codes promotionnels
- Utilisation

## 🎨 Architecture Frontend

### Stack
- **Tailwind CSS 4** : Framework CSS utility-first
- **Alpine.js** : Framework JavaScript léger
- **Livewire** : Composants dynamiques
- **Lucide Icons** : Icônes SVG
- **Chart.js** : Graphiques

### Composants
```
resources/js/
├── app.js                    # Point d'entrée
├── components/               # Composants Alpine.js
│   ├── darkMode.js
│   ├── dropdown.js
│   ├── modal.js
│   └── notification.js
└── utils/                    # Utilitaires
```

## 🔌 Architecture Backend

### Modèles (Eloquent ORM)
Chaque modèle représente une table et gère les relations :
- Relations : `hasMany`, `belongsTo`, `morphMany`
- Scopes : filtres réutilisables
- Mutators/Accessors : transformation des données
- Events : automatisation

### Contrôleurs
Séparation Admin/Client/API :
- **Admin** : gestion complète
- **Client** : vue limitée
- **API** : accès programmati que

### Services
Logique métier complexe :
- `PaymentService` : gestion des paiements
- `InvoiceService` : génération de factures
- `ProvisionService` : provisionnement automatique

### Middleware
- Authentification (Sanctum)
- Autorisations (Spatie Permissions)
- Rate limiting
- CORS

## 📦 Système de Modules

Les modules sont des packages Laravel indépendants :

```
modules/Pterodactyl/
├── Config/
├── Controllers/
├── Models/
├── Routes/
│   ├── web.php
│   └── api.php
├── Views/
├── Migrations/
├── module.json              # Métadonnées
└── README.md
```

### Chargement des Modules
Les modules sont auto-découverts et enregistrés au démarrage.

## 🔐 Sécurité

### Authentification
- Laravel Fortify (login, register, 2FA)
- Laravel Sanctum (API tokens)

### Autorisations
- Spatie Laravel Permission
- Rôles : admin, client
- Permissions granulaires

### Protection
- CSRF tokens
- Rate limiting
- XSS protection
- SQL injection prevention
- Content Security Policy

## 🔄 Automatisation

### Tâches Planifiées (Cron)
```php
// app/Console/Kernel.php
$schedule->command('invoices:generate')->daily();
$schedule->command('services:suspend')->hourly();
$schedule->command('services:terminate')->daily();
$schedule->command('backup:run')->daily();
```

### Queues
- Envoi d'emails
- Génération de PDF
- Provisionnement de services
- Notifications

## 📊 API REST

### Endpoints
```
GET    /api/v1/services
POST   /api/v1/services
GET    /api/v1/services/{id}
PUT    /api/v1/services/{id}
DELETE /api/v1/services/{id}

GET    /api/v1/invoices
GET    /api/v1/invoices/{id}/download
POST   /api/v1/invoices/{id}/pay

GET    /api/v1/tickets
POST   /api/v1/tickets
POST   /api/v1/tickets/{id}/reply
```

### Documentation
Auto-générée avec Swagger/OpenAPI

## 🎨 Système de Thèmes

```
resources/themes/
├── default/
│   ├── css/
│   ├── js/
│   └── views/
└── custom/
```

## 🌍 Internationalisation

```
resources/lang/
├── en/
│   ├── auth.php
│   ├── messages.php
│   └── validation.php
└── fr/
    ├── auth.php
    ├── messages.php
    └── validation.php
```

## 📝 Logging & Monitoring

- Laravel Log (storage/logs/)
- Activity Log (Spatie)
- Audit Trail
- Error tracking

## 🚀 Déploiement

### Développement
```bash
php artisan serve
npm run dev
```

### Production
```bash
composer install --optimize-autoloader --no-dev
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Docker
```bash
docker-compose up -d
```
