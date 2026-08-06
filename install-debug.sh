#!/bin/bash

# Version DEBUG du script d'installation
# Pour voir exactement où ça bloque

set -ex  # Mode debug: affiche toutes les commandes + arrêt en cas d'erreur

echo "=== DEBUT INSTALLATION DEBUG ==="

# Vérifier root
if [[ $EUID -ne 0 ]]; then
   echo "Ce script doit être exécuté en tant que root" 
   exit 1
fi

# Variables
export DEBIAN_FRONTEND=noninteractive
OS=$(lsb_release -is | tr '[:upper:]' '[:lower:]')
OS_VERSION=$(lsb_release -rs)

echo "=== Système: $OS $OS_VERSION ==="

# 1. Update
echo "=== Mise à jour des paquets ==="
apt-get update

# 2. Outils de base
echo "=== Installation outils de base ==="
apt-get install -y curl wget git unzip ca-certificates apt-transport-https lsb-release gnupg2

# 3. Repository PHP
echo "=== Ajout du repository PHP ==="
if [ "$OS" = "debian" ]; then
    wget -q https://packages.sury.org/php/apt.gpg -O /usr/share/keyrings/php-archive-keyring.gpg
    echo "deb [signed-by=/usr/share/keyrings/php-archive-keyring.gpg] https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list
fi

echo "=== Update après ajout repo PHP ==="
apt-get update

# 4. PHP
echo "=== Installation PHP 8.2 ==="
apt-get install -y php8.2 php8.2-cli php8.2-fpm php8.2-mysql php8.2-xml php8.2-mbstring php8.2-curl php8.2-zip php8.2-gd php8.2-bcmath php8.2-intl php8.2-soap php8.2-gmp

echo "=== Version PHP ==="
php -v

# 5. Composer
echo "=== Installation Composer ==="
curl -sS https://getcomposer.org/installer -o composer-setup.php
php composer-setup.php --install-dir=/usr/local/bin --filename=composer
rm composer-setup.php

echo "=== Version Composer ==="
composer --version

# 6. Node.js
echo "=== Installation Node.js ==="
curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
apt-get install -y nodejs

echo "=== Version Node.js ==="
node -v
npm -v

# 7. MySQL
echo "=== Installation MySQL ==="
apt-get install -y default-mysql-server default-mysql-client

echo "=== Démarrage MySQL ==="
systemctl start mysql || systemctl start mariadb
systemctl enable mysql || systemctl enable mariadb

echo "=== Status MySQL ==="
systemctl status mysql --no-pager || systemctl status mariadb --no-pager

echo "=== Installation MySQL réussie ==="

# 8. Nginx
echo "=== Installation Nginx ==="
apt-get install -y nginx

echo "=== Status Nginx ==="
systemctl status nginx --no-pager

echo ""
echo "=== TOUTES LES DÉPENDANCES SONT INSTALLÉES ==="
echo ""
echo "Versions installées:"
echo "- PHP: $(php -v | head -n 1)"
echo "- Composer: $(composer --version)"
echo "- Node.js: $(node -v)"
echo "- NPM: $(npm -v)"
echo "- Nginx: $(nginx -v 2>&1)"
echo "- MySQL: $(mysql --version)"
echo ""
echo "Vous pouvez maintenant relancer le script d'installation principal"
