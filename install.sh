#!/bin/bash

# HostClient Auto-Installer - Installation Propre
# Usage: bash <(curl -sSL https://raw.githubusercontent.com/Nitrohebergeur/hostclient/main/install.sh)

set -e

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
CYAN='\033[0;36m'
MAGENTA='\033[0;35m'
NC='\033[0m'

# Variables globales
DB_NAME="hostclient"
DB_USER="hostclient_user"
DB_PASSWORD=""
ADMIN_EMAIL=""
ADMIN_PASSWORD=""
ADMIN_NAME=""
COMPANY_NAME=""
APP_URL="http://localhost"

# Functions
print_success() { echo -e "${GREEN}✓ $1${NC}"; }
print_error() { echo -e "${RED}✗ $1${NC}"; }
print_info() { echo -e "${BLUE}ℹ $1${NC}"; }
print_warning() { echo -e "${YELLOW}⚠ $1${NC}"; }
print_step() { echo -e "${CYAN}▶ $1${NC}"; }

print_header() {
    clear
    echo -e "${MAGENTA}"
    echo "╔════════════════════════════════════════════════════════╗"
    echo "║       HostClient Auto-Installer v3.0                   ║"
    echo "║       Installation Propre avec Nettoyage              ║"
    echo "║       https://github.com/Nitrohebergeur                ║"
    echo "╚════════════════════════════════════════════════════════╝"
    echo -e "${NC}"
}

generate_password() {
    openssl rand -base64 32 | tr -d "=+/" | cut -c1-25
}

# Collecter les informations
collect_info() {
    print_step "Configuration initiale"
    echo ""
    
    read -p "$(echo -e ${CYAN}Nom de votre entreprise:${NC} )" COMPANY_NAME
    COMPANY_NAME=${COMPANY_NAME:-"Mon Entreprise"}
    
    read -p "$(echo -e ${CYAN}URL de l\'application \(ex: https://panel.example.com\):${NC} )" APP_URL
    APP_URL=${APP_URL:-"http://localhost"}
    
    echo ""
    print_step "Informations du compte administrateur"
    
    read -p "$(echo -e ${CYAN}Nom complet de l\'administrateur:${NC} )" ADMIN_NAME
    ADMIN_NAME=${ADMIN_NAME:-"Admin"}
    
    while [[ ! "$ADMIN_EMAIL" =~ ^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$ ]]; do
        read -p "$(echo -e ${CYAN}Email administrateur:${NC} )" ADMIN_EMAIL
        if [[ ! "$ADMIN_EMAIL" =~ ^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$ ]]; then
            print_error "Email invalide, réessayez"
        fi
    done
    
    while [ -z "$ADMIN_PASSWORD" ] || [ ${#ADMIN_PASSWORD} -lt 8 ]; do
        read -sp "$(echo -e ${CYAN}Mot de passe admin \(min 8 caractères\):${NC} )" ADMIN_PASSWORD
        echo ""
        if [ ${#ADMIN_PASSWORD} -lt 8 ]; then
            print_error "Le mot de passe doit contenir au moins 8 caractères"
        fi
    done
    
    DB_PASSWORD=$(generate_password)
    
    echo ""
    print_success "Configuration collectée avec succès"
}

# Désinstallation complète
uninstall_all() {
    print_step "Nettoyage de l'environnement..."
    echo ""
    
    print_warning "⚠️  Désinstallation de tous les composants existants..."
    
    # Arrêter les services
    print_info "Arrêt des services..."
    systemctl stop nginx 2>/dev/null || true
    systemctl stop php8.2-fpm 2>/dev/null || true
    systemctl stop mysql 2>/dev/null || true
    systemctl stop mariadb 2>/dev/null || true
    
    # Désinstaller Nginx
    print_info "Suppression Nginx..."
    apt-get purge -y nginx nginx-common nginx-core 2>/dev/null || true
    rm -rf /etc/nginx /var/log/nginx
    
    # Désinstaller PHP
    print_info "Suppression PHP..."
    apt-get purge -y 'php8.*' 2>/dev/null || true
    rm -rf /etc/php /var/lib/php
    
    # Désinstaller MySQL/MariaDB
    print_info "Suppression MySQL/MariaDB..."
    apt-get purge -y mysql-server mysql-client mysql-common mariadb-server mariadb-client mariadb-common 2>/dev/null || true
    rm -rf /etc/mysql /var/lib/mysql /var/log/mysql
    
    # Désinstaller Node.js
    print_info "Suppression Node.js..."
    apt-get purge -y nodejs npm 2>/dev/null || true
    rm -rf /usr/lib/node_modules /usr/local/lib/node_modules ~/.npm
    
    # Désinstaller Composer
    print_info "Suppression Composer..."
    rm -f /usr/local/bin/composer /usr/bin/composer
    
    # Nettoyer repos
    rm -f /etc/apt/sources.list.d/php.list
    rm -f /etc/apt/sources.list.d/nodesource.list
    rm -f /usr/share/keyrings/php-archive-keyring.gpg
    rm -f /usr/share/keyrings/nodesource.gpg
    
    # Supprimer dossiers hostclient
    rm -rf ~/hostclient /tmp/hostclient /var/www/hostclient
    
    # Nettoyer
    apt-get autoremove -y >/dev/null 2>&1
    apt-get autoclean >/dev/null 2>&1
    apt-get clean >/dev/null 2>&1
    
    print_success "Nettoyage terminé"
}

# Installation des dépendances
install_dependencies() {
    print_step "Installation des dépendances système..."
    export DEBIAN_FRONTEND=noninteractive
    
    print_info "Mise à jour des paquets..."
    apt-get update -qq
    
    print_info "Installation des outils de base..."
    apt-get install -y curl wget git unzip ca-certificates apt-transport-https lsb-release gnupg2 >/dev/null 2>&1
    
    print_info "Ajout du repository PHP..."
    wget -q https://packages.sury.org/php/apt.gpg -O /usr/share/keyrings/php-archive-keyring.gpg
    echo "deb [signed-by=/usr/share/keyrings/php-archive-keyring.gpg] https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list
    apt-get update -qq
    
    print_info "Installation de PHP 8.2..."
    apt-get install -y php8.2 php8.2-cli php8.2-fpm php8.2-mysql php8.2-xml php8.2-mbstring \
        php8.2-curl php8.2-zip php8.2-gd php8.2-bcmath php8.2-intl php8.2-soap php8.2-gmp >/dev/null 2>&1
    print_success "PHP 8.2 installé"
    
    print_info "Installation de Composer..."
    curl -sS https://getcomposer.org/installer -o composer-setup.php
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer --quiet
    rm composer-setup.php
    print_success "Composer installé"
    
    print_info "Installation de Node.js 20..."
    curl -fsSL https://deb.nodesource.com/setup_20.x | bash - >/dev/null 2>&1
    apt-get install -y nodejs >/dev/null 2>&1
    print_success "Node.js installé"
    
    print_info "Installation de MariaDB..."
    apt-get install -y mariadb-server mariadb-client >/dev/null 2>&1
    print_success "MariaDB installé"
    
    print_info "Installation de Nginx..."
    apt-get install -y nginx >/dev/null 2>&1
    print_success "Nginx installé"
    
    print_success "Toutes les dépendances sont installées"
}

# Configuration MySQL
setup_mysql() {
    print_step "Configuration de MySQL..."
    
    systemctl start mariadb
    systemctl enable mariadb >/dev/null 2>&1
    
    sleep 3
    
    print_info "Création de la base de données..."
    mysql <<EOSQL 2>/dev/null
CREATE DATABASE IF NOT EXISTS ${DB_NAME} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASSWORD}';
GRANT ALL PRIVILEGES ON ${DB_NAME}.* TO '${DB_USER}'@'localhost';
FLUSH PRIVILEGES;
EOSQL
    
    print_success "Base de données configurée"
}

# Installation HostClient
install_hostclient() {
    print_step "Installation de HostClient..."
    
    cd ~
    print_info "Clonage du repository..."
    git clone -q https://github.com/Nitrohebergeur/hostclient.git
    cd hostclient
    
    # Créer artisan si absent
    if [ ! -f "artisan" ]; then
        print_info "Création du fichier artisan..."
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
    
    # Créer dossiers
    mkdir -p bootstrap/cache storage/framework/{sessions,views,cache} storage/logs
    chmod -R 775 storage bootstrap/cache
    
    print_info "Installation des dépendances PHP..."
    # Ignorer l'erreur de package:discover, on le fera après
    COMPOSER_ALLOW_SUPERUSER=1 composer install --no-dev --optimize-autoloader --no-interaction --no-scripts -q 2>&1 || true
    
    # Lancer package:discover manuellement après
    php artisan package:discover --ansi 2>&1 || true
    
    print_info "Configuration de l'environnement..."
    cp .env.example .env
    sed -i "s|APP_NAME=.*|APP_NAME=\"${COMPANY_NAME}\"|" .env
    sed -i "s|APP_URL=.*|APP_URL=${APP_URL}|" .env
    sed -i "s|APP_ENV=.*|APP_ENV=production|" .env
    sed -i "s|APP_DEBUG=.*|APP_DEBUG=false|" .env
    sed -i "s|DB_DATABASE=.*|DB_DATABASE=${DB_NAME}|" .env
    sed -i "s|DB_USERNAME=.*|DB_USERNAME=${DB_USER}|" .env
    sed -i "s|DB_PASSWORD=.*|DB_PASSWORD=${DB_PASSWORD}|" .env
    
    print_info "Génération de la clé d'application..."
    php artisan key:generate --force >/dev/null 2>&1
    
    print_info "Exécution des migrations..."
    php artisan migrate --force >/dev/null 2>&1
    
    print_info "Création du compte administrateur..."
    php artisan tinker <<EOFTINKER >/dev/null 2>&1
\$user = App\Models\User::firstOrCreate(
    ['email' => '${ADMIN_EMAIL}'],
    ['name' => '${ADMIN_NAME}', 'password' => bcrypt('${ADMIN_PASSWORD}'), 'email_verified_at' => now()]
);
exit
EOFTINKER
    
    php artisan storage:link >/dev/null 2>&1
    
    print_info "Installation des dépendances JavaScript..."
    npm install --silent >/dev/null 2>&1
    
    print_info "Compilation des assets..."
    npm run build >/dev/null 2>&1
    
    print_success "HostClient installé"
}

# Configuration Nginx
setup_nginx() {
    print_step "Configuration de Nginx..."
    
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
    
    nginx -t >/dev/null 2>&1
    systemctl restart nginx
    systemctl restart php8.2-fpm
    systemctl enable nginx >/dev/null 2>&1
    
    print_success "Nginx configuré"
}

# Tâches cron
setup_cron() {
    print_step "Configuration des tâches planifiées..."
    (crontab -l 2>/dev/null | grep -v "hostclient"; echo "* * * * * cd $(pwd) && php artisan schedule:run >> /dev/null 2>&1") | crontab -
    print_success "Tâches planifiées configurées"
}

# Sauvegarder les identifiants
save_credentials() {
    cat > $(pwd)/CREDENTIALS.txt <<EOFCREDS
╔════════════════════════════════════════╗
║     INFORMATIONS D'INSTALLATION        ║
╚════════════════════════════════════════╝

🌐 URL: ${APP_URL}
👤 Email: ${ADMIN_EMAIL}
🔐 Mot de passe: ${ADMIN_PASSWORD}

🗄️  Base de données: ${DB_NAME}
👤 User DB: ${DB_USER}
🔐 Password DB: ${DB_PASSWORD}

📁 Emplacement: $(pwd)

⚠️  Supprimez ce fichier: rm $(pwd)/CREDENTIALS.txt
EOFCREDS
    chmod 600 $(pwd)/CREDENTIALS.txt
}

# Résumé final
show_summary() {
    echo ""
    echo -e "${GREEN}╔════════════════════════════════════════════════════════╗${NC}"
    echo -e "${GREEN}║          Installation terminée avec succès! 🎉         ║${NC}"
    echo -e "${GREEN}╚════════════════════════════════════════════════════════╝${NC}"
    echo ""
    echo -e "${CYAN}📋 Informations:${NC}"
    echo -e "   ${BLUE}•${NC} URL: ${YELLOW}${APP_URL}${NC}"
    echo -e "   ${BLUE}•${NC} Email: ${YELLOW}${ADMIN_EMAIL}${NC}"
    echo -e "   ${BLUE}•${NC} Mot de passe: ${YELLOW}${ADMIN_PASSWORD}${NC}"
    echo ""
    echo -e "${CYAN}📁 Emplacement:${NC} $(pwd)"
    echo -e "${CYAN}📄 Identifiants:${NC} $(pwd)/CREDENTIALS.txt"
    echo ""
    echo -e "${YELLOW}🔒 Pour activer SSL (Let's Encrypt):${NC}"
    echo -e "   apt-get install certbot python3-certbot-nginx"
    echo -e "   certbot --nginx -d $(echo $APP_URL | sed 's/https\?:\/\///')"
    echo ""
}

# Main
main() {
    print_header
    
    if [[ $EUID -ne 0 ]]; then
        print_error "Ce script doit être exécuté en tant que root"
        echo "Utilisez: sudo bash install.sh"
        exit 1
    fi
    
    collect_info
    echo ""
    
    uninstall_all
    echo ""
    
    install_dependencies
    echo ""
    
    setup_mysql
    echo ""
    
    install_hostclient
    echo ""
    
    setup_nginx
    echo ""
    
    setup_cron
    echo ""
    
    save_credentials
    
    show_summary
}

main
