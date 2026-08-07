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

### ⚡ Installation automatique en une commande (Recommandé)

```bash
bash <(curl -fsSL https://raw.githubusercontent.com/hostclient/hostclient/main/install.sh)
```

**Ce que fait le script :**
- ✅ Détecte automatiquement votre système d'exploitation
- ✅ Installe toutes les dépendances (PHP 8.4, MySQL, Redis, Node.js, etc.)
- ✅ Configure Nginx avec optimisations de performance
- ✅ Crée et configure la base de données
- ✅ Installe et compile l'application
- ✅ Configure Supervisor pour les workers et le scheduler
- ✅ Configure les permissions et le stockage
- ✅ Crée votre compte administrateur

**Systèmes supportés :** Ubuntu 22.04/24.04, Debian 11/12, CentOS/Rocky/AlmaLinux 8/9

**Durée d'installation :** 5-10 minutes selon votre serveur

---

### 🛠️ Installation manuelle

Si vous préférez tout configurer manuellement :

```bash
# 1. Cloner le dépôt
git clone https://github.com/hostclient/hostclient.git
cd hostclient

# 2. Installer les dépendances PHP
composer install --no-dev --optimize-autoloader

# 3. Installer et compiler les assets
npm install && npm run build

# 4. Configuration
cp .env.example .env
php artisan key:generate

# Éditez .env avec vos paramètres de base de données

# 5. Base de données
php artisan migrate --seed

# 6. Stockage et cache
php artisan storage:link
php artisan optimize

# 7. Permissions (ajustez selon votre serveur web)
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

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

## ⚙️ Configuration Post-Installation

### Nginx avec SSL (Recommandé)

Sécurisez votre installation avec Let's Encrypt :

```bash
# Installer Certbot
sudo apt install certbot python3-certbot-nginx  # Ubuntu/Debian
sudo dnf install certbot python3-certbot-nginx  # CentOS/RHEL

# Obtenir un certificat SSL
sudo certbot --nginx -d votre-domaine.com -d www.votre-domaine.com

# Le renouvellement automatique est configuré par défaut
```

### Queue Workers

Les workers sont automatiquement gérés par Supervisor. Commandes utiles :

```bash
# Voir l'état des workers
sudo supervisorctl status

# Redémarrer les workers
sudo supervisorctl restart hostclient-queue:*

# Logs des workers
tail -f /var/www/hostclient/storage/logs/queue.log
```

### Tâches Planifiées (Cron)

Le scheduler Laravel est automatiquement configuré et gère :
- Renouvellements automatiques
- Mise à jour des taux de change
- Envoi des emails différés
- Génération des rapports

```bash
# Vérifier le scheduler
cd /var/www/hostclient && php artisan schedule:list

# Logs du scheduler
tail -f /var/www/hostclient/storage/logs/scheduler.log
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

## 🔧 Dépannage

### Erreur 500 après installation

```bash
cd /var/www/hostclient
php artisan cache:clear
php artisan config:clear
php artisan view:clear
sudo chmod -R 775 storage bootstrap/cache
```

### Les workers ne se lancent pas

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start hostclient-queue:*
```

### Nginx affiche une page blanche

```bash
# Vérifier les logs
sudo tail -f /var/log/nginx/hostclient-error.log
sudo tail -f /var/www/hostclient/storage/logs/laravel.log

# Vérifier PHP-FPM
sudo systemctl status php8.4-fpm
sudo systemctl restart php8.4-fpm
```

### Base de données inaccessible

```bash
# Tester la connexion MySQL
mysql -u hostclient -p -h 127.0.0.1 hostclient

# Vérifier .env
cat /var/www/hostclient/.env | grep DB_
```

## 💬 Support

- 📖 Documentation: https://docs.hostclient.io
- 💬 Forum: https://community.hostclient.io
- 💬 Discord: https://discord.gg/hostclient
- 🐛 Issues: https://github.com/hostclient/hostclient/issues

## 🙏 Remerciements

Merci à tous les contributeurs qui participent à ce projet !
