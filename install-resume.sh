#!/bin/bash

# HostClient - Script de REPRISE
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

if [ ! -d ~/hostclient ]; then
    print_error "Le dossier ~/hostclient n'existe pas."
    exit 1
fi

cd ~/hostclient

echo -e "${CYAN}"
echo "╔════════════════════════════════════════════════════════╗"
echo "║       HostClient - Reprise d'installation              ║"
echo "╚════════════════════════════════════════════════════════╝"
echo -e "${NC}"

# Lire les infos depuis .env
APP_URL=$(grep "^APP_URL=" .env | cut -d= -f2)
DOMAIN=$(echo "$APP_URL" | sed 's|https\?://||')

read -p "$(echo -e ${CYAN}Email admin: ${NC})" ADMIN_EMAIL
read -sp "$(echo -e ${CYAN}Mot de passe admin: ${NC})" ADMIN_PASSWORD
echo ""
read -p "$(echo -e ${CYAN}Nom admin: ${NC})" ADMIN_NAME

# Dossiers nécessaires
mkdir -p bootstrap/cache storage/framework/{sessions,views,cache} storage/logs
chmod -R 775 storage bootstrap/cache

# Vendor
if [ ! -d "vendor" ]; then
    print_step "Installation Composer..."
    COMPOSER_ALLOW_SUPERUSER=1 composer install --no-dev --optimize-autoloader --no-interaction
fi
print_success "Vendor présent"

# Clé app
if ! grep -q "APP_KEY=base64" .env; then
    print_step "Génération clé..."
    php artisan key:generate --force
fi
print_success "Clé présente"

# Migrations
print_step "Migrations..."
php artisan migrate --force
print_success "Migrations OK"

# Admin
print_step "Création du compte admin..."
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
print_success "Admin créé"

# Storage link
php artisan storage:link 2>/dev/null || true
print_success "Storage link OK"

# Permissions
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true
chmod -R 775 storage bootstrap/cache
chmod -R 755 public

# NPM
print_step "Compilation des assets..."
if [ ! -d "node_modules" ]; then
    npm install --silent
fi
npm run build
print_success "Assets compilés"

# Cache Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache
print_success "Cache optimisé"

# Nginx
print_step "Configuration Nginx..."
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
print_success "Nginx configuré"

# Cron
(crontab -l 2>/dev/null | grep -v "hostclient"; echo "* * * * * php $(pwd)/artisan schedule:run >> /dev/null 2>&1") | crontab -
print_success "Cron configuré"

echo ""
echo -e "${GREEN}╔════════════════════════════════════════════════════════╗${NC}"
echo -e "${GREEN}║       Installation terminée avec succès ! 🎉           ║${NC}"
echo -e "${GREEN}╚════════════════════════════════════════════════════════╝${NC}"
echo ""
echo -e " ${CYAN}URL   :${NC} ${YELLOW}${APP_URL}${NC}"
echo -e " ${CYAN}Email :${NC} ${YELLOW}${ADMIN_EMAIL}${NC}"
echo ""
echo -e " ${YELLOW}Pour SSL :${NC}"
echo -e "   apt-get install certbot python3-certbot-nginx"
echo -e "   certbot --nginx -d ${DOMAIN}"
echo ""
