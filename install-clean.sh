#!/bin/bash

# Script d'installation PROPRE - Désinstalle tout puis réinstalle
set -e

echo "=========================================="
echo "  INSTALLATION PROPRE HOSTCLIENT"
echo "  Désinstallation complète puis réinstallation"
echo "=========================================="
echo ""

# Vérifier root
if [[ $EUID -ne 0 ]]; then
   echo "❌ Ce script doit être exécuté en tant que root"
   echo "Utilisez: sudo bash install-clean.sh"
   exit 1
fi

# Collecter les informations AVANT de tout désinstaller
echo "📋 Collecte des informations..."
read -p "Email admin: " ADMIN_EMAIL
read -sp "Mot de passe admin: " ADMIN_PASSWORD
echo ""
read -p "Nom admin: " ADMIN_NAME
read -p "URL (ex: https://panel.example.com): " APP_URL
read -p "Nom entreprise: " COMPANY_NAME

DB_NAME="hostclient"
DB_USER="hostclient_user"
DB_PASSWORD=$(openssl rand -base64 20 | tr -d "=+/" | cut -c1-20)

echo ""
echo "⚠️  ATTENTION: Ce script va DÉSINSTALLER:"
echo "  - PHP 8.2 et toutes ses extensions"
echo "  - MySQL/MariaDB (et SUPPRIMER toutes les bases de données)"
echo "  - Nginx"
echo "  - Node.js et NPM"
echo "  - Tous les dossiers hostclient existants"
echo ""
read -p "Êtes-vous sûr de vouloir continuer? (tapez OUI): " CONFIRM

if [ "$CONFIRM" != "OUI" ]; then
    echo "❌ Installation annulée"
    exit 0
fi

echo ""
echo "🗑️  DÉSINSTALLATION COMPLÈTE..."

# Arrêter les services
echo "Arrêt des services..."
systemctl stop nginx 2>/dev/null || true
systemctl stop php8.2-fpm 2>/dev/null || true
systemctl stop mysql 2>/dev/null || true
systemctl stop mariadb 2>/dev/null || true

# Désinstaller Nginx
echo "Suppression Nginx..."
apt-get purge -y nginx nginx-common nginx-core 2>/dev/null || true
rm -rf /etc/nginx /var/log/nginx /var/www

# Désinstaller PHP
echo "Suppression PHP..."
apt-get purge -y 'php8.*' 2>/dev/null || true
rm -rf /etc/php /var/lib/php

# Désinstaller MySQL/MariaDB
echo "Suppression MySQL/MariaDB..."
apt-get purge -y mysql-server mysql-client mysql-common mariadb-server mariadb-client mariadb-common 2>/dev/null || true
rm -rf /etc/mysql /var/lib/mysql /var/log/mysql

# Désinstaller Node.js
echo "Suppression Node.js..."
apt-get purge -y nodejs npm 2>/dev/null || true
rm -rf /usr/lib/node_modules /usr/local/lib/node_modules ~/.npm

# Désinstaller Composer
echo "Suppression Composer..."
rm -f /usr/local/bin/composer /usr/bin/composer

# Supprimer les repositories ajoutés
echo "Nettoyage repositories..."
rm -f /etc/apt/sources.list.d/php.list
rm -f /etc/apt/sources.list.d/nodesource.list
rm -f /usr/share/keyrings/php-archive-keyring.gpg
rm -f /usr/share/keyrings/nodesource.gpg

# Supprimer les dossiers hostclient
echo "Suppression dossiers hostclient..."
rm -rf ~/hostclient /tmp/hostclient /var/www/hostclient

# Nettoyer
apt-get autoremove -y
apt-get autoclean
apt-get clean

echo "✅ Désinstallation terminée"
echo ""
echo "📦 INSTALLATION FRAÎCHE..."
export DEBIAN_FRONTEND=noninteractive

# Update
echo "Mise à jour des paquets..."
apt-get update -qq

# Outils de base
echo "Installation outils de base..."
apt-get install -y curl wget git unzip ca-certificates apt-transport-https lsb-release gnupg2

# Repository PHP
echo "Ajout repository PHP Sury..."
wget -q https://packages.sury.org/php/apt.gpg -O /usr/share/keyrings/php-archive-keyring.gpg
echo "deb [signed-by=/usr/share/keyrings/php-archive-keyring.gpg] https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list
apt-get update -qq

# Installation PHP 8.2
echo "Installation PHP 8.2..."
apt-get install -y php8.2 php8.2-cli php8.2-fpm php8.2-mysql php8.2-xml php8.2-mbstring \
    php8.2-curl php8.2-zip php8.2-gd php8.2-bcmath php8.2-intl php8.2-soap php8.2-gmp

# Composer
echo "Installation Composer..."
curl -sS https://getcomposer.org/installer -o composer-setup.php
php composer-setup.php --install-dir=/usr/local/bin --filename=composer
rm composer-setup.php

# Node.js
echo "Installation Node.js 20..."
curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
apt-get install -y nodejs

# MariaDB
echo "Installation MariaDB..."
apt-get install -y mariadb-server mariadb-client

# Démarrer et configurer MariaDB
systemctl start mariadb
systemctl enable mariadb

# Configuration MariaDB sans mot de passe root
echo "Configuration MariaDB..."
mysql <<EOSQL
CREATE DATABASE IF NOT EXISTS ${DB_NAME} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASSWORD}';
GRANT ALL PRIVILEGES ON ${DB_NAME}.* TO '${DB_USER}'@'localhost';
FLUSH PRIVILEGES;
EOSQL

# Nginx
echo "Installation Nginx..."
apt-get install -y nginx

echo "✅ Tous les composants installés"
echo ""
echo "📥 CLONAGE ET CONFIGURATION HOSTCLIENT..."

# Cloner
cd ~
git clone https://github.com/Nitrohebergeur/hostclient.git
cd hostclient

# Créer le fichier artisan s'il n'existe pas
if [ ! -f "artisan" ]; then
    echo "Création du fichier artisan..."
    cat > artisan <<'EOFARTISAN'
#!/usr/bin/env php
<?php
define('LARAVEL_START', microtime(true));
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$status = $kernel->handle(
    $input = new Symfony\Component\Console\Input\ArgvInput,
    new Symfony\Component\Console\Output\ConsoleOutput
);
$kernel->terminate($input, $status);
exit($status);
EOFARTISAN
    chmod +x artisan
fi

# Créer les dossiers nécessaires
mkdir -p bootstrap/cache storage/framework/{sessions,views,cache}
chmod -R 775 storage bootstrap/cache

# Composer
echo "Installation dépendances PHP..."
COMPOSER_ALLOW_SUPERUSER=1 composer install --no-dev --optimize-autoloader --no-interaction

# Configuration .env
cp .env.example .env
sed -i "s|APP_NAME=.*|APP_NAME=\"${COMPANY_NAME}\"|" .env
sed -i "s|APP_URL=.*|APP_URL=${APP_URL}|" .env
sed -i "s|APP_ENV=.*|APP_ENV=production|" .env
sed -i "s|APP_DEBUG=.*|APP_DEBUG=false|" .env
sed -i "s|DB_DATABASE=.*|DB_DATABASE=${DB_NAME}|" .env
sed -i "s|DB_USERNAME=.*|DB_USERNAME=${DB_USER}|" .env
sed -i "s|DB_PASSWORD=.*|DB_PASSWORD=${DB_PASSWORD}|" .env

# Génération clé
php artisan key:generate --force

# Migrations
echo "Migrations base de données..."
php artisan migrate --force

# Création admin
echo "Création compte administrateur..."
php artisan tinker <<EOFTINKER
\$user = App\Models\User::firstOrCreate(
    ['email' => '${ADMIN_EMAIL}'],
    [
        'name' => '${ADMIN_NAME}',
        'password' => bcrypt('${ADMIN_PASSWORD}'),
        'email_verified_at' => now()
    ]
);
echo "Admin créé\n";
exit
EOFTINKER

# Storage link
php artisan storage:link

# NPM
echo "Installation et compilation assets..."
npm install --silent
npm run build

# Configuration Nginx
DOMAIN=$(echo $APP_URL | sed 's/https\?:\/\///')
cat > /etc/nginx/sites-available/hostclient <<EOFNGINX
server {
    listen 80;
    server_name ${DOMAIN};
    root $(pwd)/public;
    index index.php;
    charset utf-8;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
EOFNGINX

ln -sf /etc/nginx/sites-available/hostclient /etc/nginx/sites-enabled/
rm -f /etc/nginx/sites-enabled/default

# Tester et redémarrer
nginx -t
systemctl restart nginx
systemctl restart php8.2-fpm

# Cron
(crontab -l 2>/dev/null | grep -v "hostclient"; echo "* * * * * cd $(pwd) && php artisan schedule:run >> /dev/null 2>&1") | crontab -

# Sauvegarder les identifiants
cat > $(pwd)/CREDENTIALS.txt <<EOFCREDS
╔════════════════════════════════════════╗
║     INFORMATIONS D'INSTALLATION        ║
╚════════════════════════════════════════╝

🌐 URL: ${APP_URL}
👤 Email admin: ${ADMIN_EMAIL}
🔐 Mot de passe admin: ${ADMIN_PASSWORD}

🗄️  Base de données: ${DB_NAME}
👤 User DB: ${DB_USER}
🔐 Password DB: ${DB_PASSWORD}

📁 Emplacement: $(pwd)

⚠️  Supprimez ce fichier après avoir noté les infos!
   rm $(pwd)/CREDENTIALS.txt
EOFCREDS
chmod 600 $(pwd)/CREDENTIALS.txt

echo ""
echo "=========================================="
echo "  ✅ INSTALLATION TERMINÉE!"
echo "=========================================="
echo ""
echo "🌐 URL: ${APP_URL}"
echo "👤 Email: ${ADMIN_EMAIL}"
echo "🔐 Mot de passe: ${ADMIN_PASSWORD}"
echo ""
echo "📄 Identifiants sauvegardés: $(pwd)/CREDENTIALS.txt"
echo ""
echo "🔒 Pour SSL (Let's Encrypt):"
echo "   apt-get install certbot python3-certbot-nginx"
echo "   certbot --nginx -d ${DOMAIN}"
echo ""
