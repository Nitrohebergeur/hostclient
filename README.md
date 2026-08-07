# HostClient

**Plateforme SaaS open-source de gestion d'hébergement web**

HostClient est une solution moderne et complète pour gérer votre activité d'hébergement web, VPS, serveurs dédiés et noms de domaine.

## 🚀 Fonctionnalités

### Espace Client
- 📊 Dashboard avec statistiques en temps réel
- 🛒 Commande et gestion de services
- 💳 Gestion des factures et paiements
- 🎫 Système de tickets support
- 🔐 Authentification 2FA
- 🔑 Gestion des clés API
- 🌐 Multi-devises et multi-langues

### Panel d'Administration
- 👥 Gestion complète des utilisateurs et permissions
- 📦 Gestion des produits et services
- 💰 Système de billing automatisé
- 🎫 Support client avec SLA
- 🔌 Système de plugins extensible
- 🎨 Système de thèmes personnalisables
- 📈 Rapports et statistiques avancés

### Provisionnement
Support des principales plateformes :
- Pterodactyl (serveurs de jeux)
- Proxmox (VPS)
- cPanel / Plesk / DirectAdmin
- Docker
- Et plus via plugins

## 🛠️ Technologies

- **Backend:** PHP 8.4, Laravel 12
- **Frontend:** Livewire 3, Alpine.js, Tailwind CSS
- **Base de données:** MySQL 8.0+
- **Cache:** Redis
- **Queue:** Laravel Queue avec Redis
- **API:** REST + GraphQL
- **Real-time:** WebSockets (Laravel Reverb)
- **Containerisation:** Docker

## 📋 Prérequis

- PHP 8.4+
- Composer 2.0+
- Node.js 20+
- MySQL 8.0+
- Redis 7+
- Docker & Docker Compose (optionnel)

## 🚀 Installation

### ⚡ Installation en une seule commande (Recommandé)

```bash
bash <(curl -fsSL https://raw.githubusercontent.com/hostclient/hostclient/main/install.sh)
```

Le script détecte automatiquement votre système, installe les dépendances manquantes et configure tout.

**Systèmes supportés :** Ubuntu 22.04/24.04, Debian 11/12, CentOS/Rocky/AlmaLinux 8/9

---

### 🔄 Mise à jour

```bash
bash <(curl -fsSL https://raw.githubusercontent.com/hostclient/hostclient/main/update.sh)
```

---

### 🐳 Avec Docker (Développement)

```bash
git clone https://github.com/hostclient/hostclient.git
cd hostclient
cp .env.example .env
docker-compose up -d
docker-compose exec app composer install
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate --seed
docker-compose exec app npm install && npm run build
```

---

### 🛠️ Installation manuelle

```bash
git clone https://github.com/hostclient/hostclient.git
cd hostclient
composer install --no-dev --optimize-autoloader
npm install && npm run build
cp .env.example .env && php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan optimize
```

### Installation Web

Accédez à `http://votre-domaine.com/install` pour lancer l'installateur graphique.

## 🏗️ Architecture

```
app/
├── Core/              # Cœur du système
├── Domains/           # Domain-Driven Design
│   ├── Billing/       # Facturation
│   ├── Client/        # Gestion clients
│   ├── Product/       # Produits et services
│   ├── Support/       # Tickets support
│   └── Admin/         # Administration
├── Infrastructure/    # Services externes
├── Plugins/           # Système de plugins
└── Themes/            # Système de thèmes
```

## 🔌 Plugins

Les plugins permettent d'étendre les fonctionnalités :

```bash
php artisan plugin:install nom-du-plugin
php artisan plugin:enable nom-du-plugin
```

## 🎨 Thèmes

Personnalisez l'apparence :

```bash
php artisan theme:install nom-du-theme
php artisan theme:activate nom-du-theme
```

## 📚 Documentation

La documentation complète est disponible sur [docs.hostclient.io](https://docs.hostclient.io)

## 🤝 Contribution

Les contributions sont les bienvenues ! Consultez [CONTRIBUTING.md](CONTRIBUTING.md)

## 📄 Licence

HostClient est un logiciel open-source sous licence [MIT](LICENSE)

## 🔒 Sécurité

Si vous découvrez une vulnérabilité, envoyez un email à security@hostclient.io

## 💬 Support

- Documentation: https://docs.hostclient.io
- Forum: https://community.hostclient.io
- Discord: https://discord.gg/hostclient

## 🙏 Remerciements

Merci à tous les contributeurs qui participent à ce projet !
