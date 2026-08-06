# 📖 Guide d'Installation HostClient

Ce guide vous accompagne pas à pas dans l'installation et la configuration de HostClient.

## 🚀 Installation Rapide (Recommandé)

Pour une installation automatique en une seule commande :

```bash
bash <(curl -sSL https://raw.githubusercontent.com/Nitrohebergeur/hostclient/main/install.sh)
```

Cette commande va :
- ✅ Vérifier les prérequis système
- ✅ Cloner le repository
- ✅ Installer les dépendances (Composer & NPM)
- ✅ Configurer l'environnement
- ✅ Préparer la base de données
- ✅ Définir les permissions

## Table des Matières

1. [Installation Rapide](#-installation-rapide-recommandé)
2. [Prérequis](#prérequis)
3. [Installation Manuelle](#installation-manuelle)
4. [Installation avec Docker](#installation-avec-docker)
5. [Configuration Avancée](#configuration-avancée)
6. [Dépannage](#dépannage)

## Prérequis

### Système

- **Serveur Web** : Apache 2.4+ ou Nginx 1.18+
- **PHP** : 8.4 ou supérieur
- **Base de données** : MySQL 8.0+ ou MariaDB 10.3+
- **Node.js** : 18.x ou supérieur
- **Composer** : 2.x

### Extensions PHP Requises

```bash
php -m | grep -E 'bcmath|ctype|json|mbstring|openssl|pdo|tokenizer|xml'
```

Toutes ces extensions doivent être activées.

## Installation Manuelle

### Étape 1 : Télécharger le Projet

```bash
# Via Git
git clone https://github.com/Nitrohebergeur/hostclient.git
cd hostclient

# Ou télécharger l'archive ZIP et extraire
```

### Étape 2 : Installer les Dépendances

```bash
# Dépendances PHP
composer install --optimize-autoloader --no-dev

# Dépendances JavaScript
npm install
```

### Étape 3 : Configuration de l'Environnement

```bash
# Copier le fichier d'exemple
cp .env.example .env

# Générer la clé d'application
php artisan key:generate
```

### Étape 4 : Configurer la Base de Données

Éditez `.env` et configurez votre base de données :

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hostclient
DB_USERNAME=root
DB_PASSWORD=votre_mot_de_passe
```

Créez la base de données :

```sql
CREATE DATABASE hostclient CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Étape 5 : Installation Interactive

```bash
php artisan hostclient:install
```

Cette commande effectue :
1. ✅ Vérification des prérequis
2. ✅ Test de connexion à la base de données
3. ✅ Exécution des migrations
4. ✅ Création du compte administrateur
5. ✅ Configuration initiale

### Étape 6 : Compiler les Assets

```bash
# Pour la production
npm run build

# Pour le développement (avec watch)
npm run dev
```

### Étape 7 : Permissions

```bash
# Linux/Mac
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Windows (via PowerShell en tant qu'Admin)
icacls storage /grant "IIS_IUSRS:(OI)(CI)F" /T
icacls bootstrap\cache /grant "IIS_IUSRS:(OI)(CI)F" /T
```

### Étape 8 : Configuration du Serveur Web

#### Apache (.htaccess)

Le fichier `.htaccess` est déjà inclus dans `public/`. Assurez-vous que `mod_rewrite` est activé :

```bash
a2enmod rewrite
systemctl restart apache2
```

Configuration VirtualHost :

```apache
<VirtualHost *:80>
    ServerName hostclient.local
    DocumentRoot /var/www/hostclient/public

    <Directory /var/www/hostclient/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/hostclient-error.log
    CustomLog ${APACHE_LOG_DIR}/hostclient-access.log combined
</VirtualHost>
```

#### Nginx

```nginx
server {
    listen 80;
    server_name hostclient.local;
    root /var/www/hostclient/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.4-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### Étape 9 : Tâches Planifiées

Ajoutez au crontab de l'utilisateur web :

```bash
# Éditer le crontab
crontab -e

# Ajouter cette ligne
* * * * * cd /var/www/hostclient && php artisan schedule:run >> /dev/null 2>&1
```

### Étape 10 : Queue Worker (Optionnel)

Pour traiter les tâches asynchrones :

```bash
# Via Supervisor (recommandé)
sudo nano /etc/supervisor/conf.d/hostclient-worker.conf
```

Contenu :

```ini
[program:hostclient-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/hostclient/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=4
redirect_stderr=true
stdout_logfile=/var/www/hostclient/storage/logs/worker.log
stopwaitsecs=3600
```

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start hostclient-worker:*
```

## Installation avec Docker

### Méthode Rapide

```bash
# Cloner le projet
git clone https://github.com/votre-repo/hostclient.git
cd hostclient

# Copier et configurer .env
cp .env.example .env

# Démarrer avec Docker Compose
docker-compose up -d

# Installer les dépendances
docker-compose exec app composer install
docker-compose exec app php artisan key:generate

# Installer
docker-compose exec app php artisan hostclient:install

# Compiler les assets
docker-compose exec app npm install
docker-compose exec app npm run build
```

Accédez à : `http://localhost:8000`

## Configuration Avancée

### SSL avec Let's Encrypt

```bash
sudo apt install certbot python3-certbot-nginx

# Nginx
sudo certbot --nginx -d hostclient.example.com

# Apache
sudo certbot --apache -d hostclient.example.com
```

### Redis (Cache & Sessions)

```bash
# Installer Redis
sudo apt install redis-server

# Configurer .env
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

### Optimisation Production

```bash
# Cache de configuration
php artisan config:cache

# Cache des routes
php artisan route:cache

# Cache des vues
php artisan view:cache

# Optimiser l'autoloader
composer install --optimize-autoloader --no-dev

# Compiler les assets
npm run build
```

### Backup Automatique

```bash
# Installer le package (déjà inclus)
# Configuration dans config/backup.php

# Tester
php artisan backup:run

# Le backup s'exécute automatiquement chaque jour à 2h via le scheduler
```

## Dépannage

### Erreur : "Please provide a valid cache path"

```bash
php artisan cache:clear
php artisan config:clear
chmod -R 775 storage bootstrap/cache
```

### Erreur : "Class not found"

```bash
composer dump-autoload
php artisan clear-compiled
php artisan optimize
```

### Erreur de connexion à la base de données

1. Vérifiez les identifiants dans `.env`
2. Testez la connexion :
```bash
php artisan tinker
>>> DB::connection()->getPdo();
```

### Les assets ne se chargent pas

```bash
# Nettoyer et recompiler
rm -rf node_modules public/build
npm install
npm run build

# Vérifier les permissions
chmod -R 775 public/build
```

### Problèmes de permissions

```bash
# Linux/Mac
sudo chown -R $USER:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
sudo chmod -R 775 public

# S'assurer que les nouveaux fichiers héritent des permissions
sudo chmod g+s storage bootstrap/cache
```

### Mode Maintenance

```bash
# Activer
php artisan down --secret="token-secret"

# Désactiver
php artisan up
```

### Logs

```bash
# Voir les derniers logs
tail -f storage/logs/laravel.log

# Nettoyer les vieux logs
php artisan log:clear
```

## Vérification Post-Installation

### Checklist

- [ ] Application accessible via navigateur
- [ ] Connexion avec le compte admin fonctionne
- [ ] Dashboard admin charge correctement
- [ ] Les assets (CSS/JS) sont chargés
- [ ] La boutique affiche les produits (si configurés)
- [ ] Les emails de test fonctionnent
- [ ] Les tâches cron s'exécutent
- [ ] Les backups fonctionnent

### Tests Rapides

```bash
# Test de l'application
php artisan test

# Test de l'email
php artisan tinker
>>> Mail::raw('Test', fn($m) => $m->to('test@example.com')->subject('Test'));

# Test du scheduler
php artisan schedule:test

# Vérifier les queues
php artisan queue:work --once
```

## Mise à Jour

```bash
# Sauvegarde
php artisan backup:run

# Git pull
git pull origin main

# Mettre à jour les dépendances
composer install --optimize-autoloader --no-dev
npm install && npm run build

# Migrations
php artisan migrate --force

# Clear cache
php artisan optimize:clear
php artisan optimize
```

## Support

Si vous rencontrez des problèmes :

1. Consultez les logs : `storage/logs/laravel.log`
2. Vérifiez la documentation : `/docs`
3. Ouvrez une issue : [GitHub Issues](https://github.com/votre-repo/hostclient/issues)

---

✅ Installation réussie ! Votre HostClient est prêt à l'emploi.
