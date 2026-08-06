#!/bin/bash

# HostClient - Script de REPRISE (reprend après le composer install)
# Usage: bash <(curl -sSL https://raw.githubusercontent.com/Nitrohebergeur/hostclient/main/install-resume.sh)

set -e

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
CYAN='\033[0;36m'
NC='\033[0m'

print_success() { echo -e "${GREEN}✓ $1${NC}"; }
print_error()   { echo -e "${RED}✗ $1${NC}"; }
print_info()    { echo -e "${BLUE}ℹ $1${NC}"; }
print_step()    { echo -e "\n${CYAN}▶ $1${NC}"; }

# Vérifier qu'on est dans le bon dossier
if [ ! -d ~/hostclient ]; then
    print_error "Le dossier ~/hostclient n'existe pas."
    echo "Lancez plutôt le script complet :"
    echo "  bash <(curl -sSL https://raw.githubusercontent.com/Nitrohebergeur/hostclient/main/install.sh)"
    exit 1
fi

cd ~/hostclient

if [ ! -f "artisan" ]; then
    print_error "Fichier artisan manquant dans ~/hostclient"
    exit 1
fi

echo -e "${CYAN}"
echo "╔════════════════════════════════════════════════════════╗"
echo "║       HostClient - Reprise d'installation              ║"
echo "║       Reprend après le composer install                ║"
echo "╚════════════════════════════════════════════════════════╝"
echo -e "${NC}"

# Collecter les infos si .env pas encore configuré
if ! grep -q "APP_KEY=base64" .env 2>/dev/null; then
    print_step "Configuration manquante, collecte des informations..."

    read -p "$(echo -e ${CYAN}Nom entreprise: ${NC})" COMPANY_NAME
    COMPANY_NAME=${COMPANY_NAME:-"HostClient"}

    read -p "$(echo -e ${CYAN}URL \(ex: https://panel.example.com\): ${NC})" APP_URL
    APP_URL=${APP_URL:-"http://localhost"}

    read -p "$(echo -e ${CYAN}Nom admin: ${NC})" ADMIN_NAME
    ADMIN_NAME=${ADMIN_NAME:-"Admin"}

    ADMIN_EMAIL=""
    while [[ ! "$ADMIN_EMAIL" =~ ^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$ ]]; do
        read -p "$(echo -e ${CYAN}Email admin: ${NC})" ADMIN_EMAIL
    done

    ADMIN_PASSWORD=""
    while [ ${#ADMIN_PASSWORD} -lt 8 ]; do
        read -sp "$(echo -e ${CYAN}Mot de passe admin \(min 8 chars\): ${NC})" ADMIN_PASSWORD
        echo ""
    done

    DB_NAME="hostclient"
    DB_USER="hostclient_user"
    DB_PASSWORD=$(openssl rand -base64 20 | tr -d "=+/" | cut -c1-20)

    # Créer/mettre à jour .env
    if [ ! -f ".env" ]; then
        cp .env.example .env
    fi

    DOMAIN=$(echo "$APP_URL" | sed 's|https\?://||')
    sed -i "s|APP_NAME=.*|APP_NAME=\"${COMPANY_NAME}\"|"   .env
    sed -i "s|APP_ENV=.*|APP_ENV=production|"               .env
    sed -i "s|APP_DEBUG=.*|APP_DEBUG=false|"                .env
    sed -i "s|APP_URL=.*|APP_URL=${APP_URL}|"               .env
    sed -i "s|DB_CONNECTION=.*|DB_CONNECTION=mysql|"        .env
    sed -i "s|DB_HOST=.*|DB_HOST=127.0.0.1|"               .env
    sed -i "s|DB_PORT=.*|DB_PORT=3306|"                     .env
    sed -i "s|DB_DATABASE=.*|DB_DATABASE=${DB_NAME}|"       .env
    sed -i "s|DB_USERNAME=.*|DB_USERNAME=${DB_USER}|"       .env
    sed -i "s|DB_PASSWORD=.*|DB_PASSWORD=${DB_PASSWORD}|"   .env

    print_success ".env configuré"

    # Créer DB si elle n'existe pas
    print_info "Vérification base de données..."
    mysql -u root <<EOSQL 2>/dev/null || true
CREATE DATABASE IF NOT EXISTS \`${DB_NAME}\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASSWORD}';
GRANT ALL PRIVILEGES ON \`${DB_NAME}\`.* TO '${DB_USER}'@'localhost';
FLUSH PRIVILEGES;
EOSQL
    print_success "Base de données prête"
else
    # Lire les infos depuis le .env existant
    APP_URL=$(grep "^APP_URL=" .env | cut -d= -f2)
    DOMAIN=$(echo "$APP_URL" | sed 's|https\?://||')

    read -p "$(echo -e ${CYAN}Email admin: ${NC})" ADMIN_EMAIL
    read -sp "$(echo -e ${CYAN}Mot de passe admin: ${NC})" ADMIN_PASSWORD
    echo ""
    read -p "$(echo -e ${CYAN}Nom admin: ${NC})" ADMIN_NAME

    print_info ".env déjà configuré, on continue..."
fi

# S'assurer que les dossiers existent
mkdir -p bootstrap/cache storage/framework/{sessions,views,cache} storage/logs
chmod -R 775 storage bootstrap/cache

# Vérifier si vendor existe, sinon relancer composer
if [ ! -d "vendor" ]; then
    print_step "Installation Composer..."
    COMPOSER_ALLOW_SUPERUSER=1 composer install --no-dev --optimize-autoloader --no-interaction
    print_success "Composer terminé"
else
    print_success "Vendor déjà présent"
fi

# Générer la clé si pas encore fait
if ! grep -q "APP_KEY=base64" .env; then
    print_step "Génération de la clé d'application..."
    php artisan key:generate --force
    print_success "Clé générée"
else
    print_success "Clé déjà générée"
fi

# Migrations
print_step "Migrations base de données..."
php artisan migrate --force
print_success "Migrations terminées"

# Créer l'admin
print_step "Création du compte administrateur..."
php artisan tinker --no-interaction <<EOFTINKER
\$user = App\Models\User::firstOrCreate(
    ['email' => '${ADMIN_EMAIL}'],
    [
        'name'              => '${ADMIN_NAME}',
        'password'          => bcrypt('${ADMIN_PASSWORD}'),
        'email_verified_at' => now(),
    ]
);
echo "Admin : " . \$user->email . "\n";
exit;
EOFTINKER
print_success "Admin créé : ${ADMIN_EMAIL}"

# Storage link
php artisan storage:link 2>/dev/null || true
print_success "Storage link créé"

# Permissions
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true
chmod -R 775 storage bootstrap/cache
chmod -R 755 public

# NPM + build
print_step "Compilation des assets..."
if [ ! -d "node_modules" ]; then
    npm install --silent
fi
npm run build
print_success "Assets compilés"

# Optimisation Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache
print_success "Cache Laravel optimisé"

# Nginx
print_step "Configuration Nginx..."
DOMAIN=$(echo "$APP_URL" | sed 's|https\?://||')

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

ln -sf /etc/nginx/sites-available/hostclient /etc/nginx/sites-enabled/hostclient
rm -f /etc/nginx/sites-enabled/default
nginx -t
systemctl restart nginx
systemctl restart php8.2-fpm
print_success "Nginx configuré pour ${DOMAIN}"

# Cron
(crontab -l 2>/dev/null | grep -v "hostclient"; echo "* * * * * php $(pwd)/artisan schedule:run >> /dev/null 2>&1") | crontab -
print_success "Cron configuré"

# Résumé
echo ""
echo -e "${GREEN}╔════════════════════════════════════════════════════════╗${NC}"
echo -e "${GREEN}║       Installation terminée avec succès ! 🎉           ║${NC}"
echo -e "${GREEN}╚════════════════════════════════════════════════════════╝${NC}"
echo ""
echo -e " ${CYAN}URL      :${NC} ${YELLOW}${APP_URL}${NC}"
echo -e " ${CYAN}Email    :${NC} ${YELLOW}${ADMIN_EMAIL}${NC}"
echo -e " ${CYAN}Dossier  :${NC} $(pwd)"
echo ""
echo -e " ${YELLOW}Pour SSL :${NC}"
echo -e "   apt-get install certbot python3-certbot-nginx"
echo -e "   certbot --nginx -d ${DOMAIN}"
echo ""
