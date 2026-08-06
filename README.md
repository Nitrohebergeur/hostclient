# HostClient - Panel Client Moderne pour Hébergeurs Web

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.4+-777BB4?logo=php)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

**HostClient** est une solution complète et moderne de gestion client pour les hébergeurs web. Conçu avec Laravel 12, il offre une interface intuitive pour la gestion des services, la facturation automatisée, le support client et bien plus encore.

## ✨ Fonctionnalités Principales

### 🎯 Gestion des Services
- Provisionnement automatique des services
- Support de multiples types d'hébergement (VPS, Dédié, Minecraft, FiveM, etc.)
- Cycles de facturation flexibles (mensuel, trimestriel, annuel, etc.)
- Suspension et résiliation automatiques
- Historique complet des actions

### 💳 Facturation & Paiements
- Génération automatique des factures
- Support de multiples passerelles de paiement :
  - Stripe
  - PayPal
  - Mollie
  - Paiement par solde
- Gestion des coupons de réduction
- Rappels de paiement automatiques
- Génération de PDF pour les factures

### 🎫 Support Client
- Système de tickets intégré
- Catégories et priorités personnalisables
- Réponses internes pour l'équipe
- Pièces jointes
- Notifications en temps réel

### 👥 Gestion des Utilisateurs
- Système de rôles et permissions (Spatie)
- Authentification 2FA
- API REST complète avec Sanctum
- Gestion des clés API
- Journal d'activité complet

### 🔌 Système de Modules
- Architecture modulaire extensible
- Modules pré-configurés :
  - Pterodactyl (Game Servers)
  - Proxmox (VPS)
  - cPanel / Plesk
  - CloudFlare
  - OVH

### 🎨 Interface Moderne
- Design responsive avec Tailwind CSS 4
- Mode sombre
- Alpine.js pour l'interactivité
- Lucide Icons
- Animations fluides

## 📋 Prérequis

- PHP >= 8.4
- Composer
- Node.js >= 18.x & NPM
- MySQL >= 8.0 ou MariaDB >= 10.3
- Extensions PHP requises :
  - BCMath
  - Ctype
  - JSON
  - Mbstring
  - OpenSSL
  - PDO
  - Tokenizer
  - XML

## 🚀 Installation

### 1. Cloner le projet

```bash
git clone https://github.com/votre-repo/hostclient.git
cd hostclient
```

### 2. Installer les dépendances

```bash
composer install
npm install
```

### 3. Configuration

```bash
# Copier le fichier d'environnement
copy .env.example .env

# Générer la clé d'application
php artisan key:generate

# Configurer la base de données dans .env
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=hostclient
# DB_USERNAME=root
# DB_PASSWORD=
```

### 4. Installation guidée

```bash
php artisan hostclient:install
```

Cette commande va :
- Vérifier les prérequis système
- Tester la connexion à la base de données
- Exécuter les migrations
- Créer votre compte administrateur
- Configurer les paramètres de base

### 5. Compiler les assets

```bash
# Développement
npm run dev

# Production
npm run build
```

### 6. Lancer l'application

```bash
php artisan serve
```

Accédez à : `http://localhost:8000`

## ⚙️ Configuration

### Paramètres Principaux

Modifiez `.env` pour configurer :

```env
# Informations Entreprise
HOSTCLIENT_COMPANY_NAME="Votre Entreprise"
HOSTCLIENT_COMPANY_EMAIL=contact@example.com

# Devise & Localisation
HOSTCLIENT_CURRENCY=EUR
HOSTCLIENT_LOCALE=fr
HOSTCLIENT_TIMEZONE=Europe/Paris

# Facturation
HOSTCLIENT_TAX_RATE=20.00
HOSTCLIENT_INVOICE_PREFIX=INV-
HOSTCLIENT_INVOICE_DUE_DAYS=14

# Automatisation
HOSTCLIENT_AUTO_SUSPEND_DAYS=7
HOSTCLIENT_AUTO_TERMINATE_DAYS=14
```

### Passerelles de Paiement

#### Stripe
```env
STRIPE_KEY=pk_test_xxxxx
STRIPE_SECRET=sk_test_xxxxx
STRIPE_WEBHOOK_SECRET=whsec_xxxxx
```

#### PayPal
```env
PAYPAL_MODE=sandbox
PAYPAL_CLIENT_ID=xxxxx
PAYPAL_SECRET=xxxxx
```

#### Mollie
```env
MOLLIE_KEY=test_xxxxx
```

### Modules

Activez les modules dans `.env` :

```env
# Pterodactyl
PTERODACTYL_URL=https://panel.example.com
PTERODACTYL_API_KEY=xxxxx

# Proxmox
PROXMOX_URL=https://proxmox.example.com:8006
PROXMOX_USERNAME=root@pam
PROXMOX_PASSWORD=xxxxx

# cPanel
CPANEL_URL=https://cpanel.example.com:2087
CPANEL_USERNAME=root
CPANEL_API_TOKEN=xxxxx
```

## 📅 Tâches Planifiées

Ajoutez au crontab :

```bash
* * * * * cd /chemin/vers/hostclient && php artisan schedule:run >> /dev/null 2>&1
```

Les tâches automatiques incluent :
- Génération des factures de renouvellement (quotidien à 6h)
- Suspension des services impayés (horaire)
- Résiliation des services suspendus (quotidien à 7h)
- Sauvegarde automatique (quotidien à 2h)

## 🔐 Sécurité

- Authentification 2FA disponible
- Protection CSRF sur tous les formulaires
- Rate limiting sur l'API
- Logs d'activité complets
- Chiffrement des données sensibles
- Validation stricte des entrées

## 📚 Documentation

Documentation complète disponible dans `/docs` :
- [Installation détaillée](docs/installation.md)
- [Structure du projet](docs/STRUCTURE.md)
- [Système de modules](docs/modules.md)

## 🛠️ Développement

### Tests

```bash
php artisan test
```

### Code Style

```bash
# Fixer le style
./vendor/bin/pint

# Analyser le code
./vendor/bin/phpstan analyse
```

### Créer un Module

```bash
php artisan module:make NomDuModule
```

## 🤝 Contribution

Les contributions sont les bienvenues ! Consultez [CONTRIBUTING.md](CONTRIBUTING.md) pour plus de détails.

## 📝 Licence

Ce projet est sous licence MIT. Voir [LICENSE](LICENSE) pour plus d'informations.

## 🙏 Remerciements

- [Laravel](https://laravel.com)
- [Tailwind CSS](https://tailwindcss.com)
- [Alpine.js](https://alpinejs.dev)
- [Lucide Icons](https://lucide.dev)
- Tous les contributeurs

## 📞 Support

- Documentation : [docs/](docs/)
- Issues : [GitHub Issues](https://github.com/votre-repo/hostclient/issues)
- Email : support@example.com

---

Développé avec ❤️ pour la communauté des hébergeurs web
