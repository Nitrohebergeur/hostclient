#!/bin/bash

# HostClient Auto-Installer v5.0
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

print_success() { echo -e "${GREEN}✓ $1${NC}"; }
print_error()   { echo -e "${RED}✗ $1${NC}"; }
print_info()    { echo -e "${BLUE}ℹ $1${NC}"; }
print_warning() { echo -e "${YELLOW}⚠ $1${NC}"; }
print_step()    { echo -e "\n${CYAN}▶ $1${NC}"; }

generate_password() {
    openssl rand -base64 32 | tr -d "=+/" | cut -c1-25
}

# ============================================================
# 1. Vérifications
# ============================================================
if [[ $EUID -ne 0 ]]; then
    print_error "Ce script doit être exécuté en tant que root"
    exit 1
fi

clear
echo -e "${MAGENTA}"
echo "╔════════════════════════════════════════════════════════╗"
echo "║       HostClient Auto-Installer v5.0                   ║"
echo "║       https://github.com/Nitrohebergeur                ║"
echo "╚════════════════════════════════════════════════════════╝"
echo -e "${NC}"

# ============================================================
# 2. Collecte des informations
# ============================================================
print_step "Configuration"

read -p "$(echo -e ${CYAN}Nom de votre entreprise: ${NC})" COMPANY_NAME
COMPANY_NAME=${COMPANY_NAME:-"HostClient"}

read -p "$(echo -e ${CYAN}URL de l\'application \(ex: https://panel.example.com\): ${NC})" APP_URL
APP_URL=${APP_URL:-"http://localhost"}

read -p "$(echo -e ${CYAN}Nom complet de l\'administrateur: ${NC})" ADMIN_NAME
ADMIN_NAME=${ADMIN_NAME:-"Admin"}

ADMIN_EMAIL=""
while [[ ! "$ADMIN_EMAIL" =~ ^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$ ]]; do
    read -p "$(echo -e ${CYAN}Email administrateur: ${NC})" ADMIN_EMAIL
    [[ ! "$ADMIN_EMAIL" =~ ^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$ ]] && print_error "Email invalide"
done

ADMIN_PASSWORD=""
while [ -z "$ADMIN_PASSWORD" ] || [ ${#ADMIN_PASSWORD} -lt 8 ]; do
    read -sp "$(echo -e ${CYAN}Mot de passe admin \(min 8 caractères\): ${NC})" ADMIN_PASSWORD
    echo ""
    [ ${#ADMIN_PASSWORD} -lt 8 ] && print_error "Minimum 8 caractères"
done

DB_NAME="hostclient"
DB_USER="hostclient_user"
DB_PASSWORD=$(generate_password)

print_success "Configuration collectée"

# ============================================================
# 3. Désinstallation complète
# ============================================================
print_step "Nettoyage de l'environnement existant"

systemctl stop nginx php8.2-fpm mysql mariadb 2>/dev/null || true

print_info "Suppression PHP..."
apt-get purge -y 'php8.*' 2>/dev/null || true
rm -rf /etc/php /var/lib/php

print_info "Suppression MySQL/MariaDB..."
apt-get purge -y mysql-server mysql-client mysql-common mariadb-server mariadb-client mariadb-common 2>/dev/null || true
rm -rf /etc/mysql /var/lib/mysql /var/log/mysql

print_info "Suppression Nginx..."
apt-get purge -y nginx nginx-common nginx-core 2>/dev/null || true
rm -rf /etc/nginx /var/log/nginx

print_info "Suppression Node.js..."
apt-get purge -y nodejs npm 2>/dev/null || true
rm -rf /usr/local/lib/node_modules ~/.npm

print_info "Suppression Composer..."
rm -f /usr/local/bin/composer

print_info "Suppression anciens repositories..."
rm -f /etc/apt/sources.list.d/nodesource.list
rm -f /etc/apt/sources.list.d/php.list
rm -f /usr/share/keyrings/nodesource.gpg
rm -f /usr/share/keyrings/php-archive-keyring.gpg

print_info "Suppression dossiers hostclient..."
rm -rf ~/hostclient /tmp/hostclient

apt-get autoremove -y >/dev/null 2>&1
apt-get clean >/dev/null 2>&1

print_success "Nettoyage terminé"

# ============================================================
# 4. Installation des dépendances
# ============================================================
print_step "Installation des dépendances système"
export DEBIAN_FRONTEND=noninteractive

print_info "Mise à jour des paquets..."
apt-get update -qq

print_info "Outils de base..."
apt-get install -y curl wget git unzip ca-certificates apt-transport-https lsb-release gnupg2

# PHP via Sury
print_info "Ajout du repository PHP..."
curl -sSLo /usr/share/keyrings/php-archive-keyring.gpg https://packages.sury.org/php/apt.gpg
echo "deb [signed-by=/usr/share/keyrings/php-archive-keyring.gpg] https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list
apt-get update -qq

print_info "Installation PHP 8.2..."
apt-get install -y php8.2 php8.2-cli php8.2-fpm php8.2-mysql php8.2-xml \
    php8.2-mbstring php8.2-curl php8.2-zip php8.2-gd php8.2-bcmath \
    php8.2-intl php8.2-soap php8.2-gmp
print_success "PHP 8.2 installé : $(php -r 'echo PHP_VERSION;')"

# Composer
print_info "Installation Composer..."
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
print_success "Composer installé : $(composer --version --no-ansi)"

# Node.js via script officiel
print_info "Installation Node.js 20..."
curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
apt-get install -y nodejs
print_success "Node.js installé : $(node -v)"

# MariaDB
print_info "Installation MariaDB..."
apt-get install -y mariadb-server mariadb-client
print_success "MariaDB installé"

# Nginx
print_info "Installation Nginx..."
apt-get install -y nginx
print_success "Nginx installé"

# ============================================================
# 5. Configuration MariaDB
# ============================================================
print_step "Configuration de la base de données"

systemctl start mariadb
systemctl enable mariadb
sleep 3

mysql -u root <<EOSQL
CREATE DATABASE IF NOT EXISTS \`${DB_NAME}\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASSWORD}';
GRANT ALL PRIVILEGES ON \`${DB_NAME}\`.* TO '${DB_USER}'@'localhost';
FLUSH PRIVILEGES;
EOSQL

print_success "Base de données '${DB_NAME}' créée"

# ============================================================
# 6. Clonage et configuration HostClient
# ============================================================
print_step "Installation de HostClient"

cd ~
print_info "Clonage du repository..."
git clone https://github.com/Nitrohebergeur/hostclient.git
cd hostclient

# Vérifier que c'est un projet Laravel valide
if [ ! -f "artisan" ]; then
    print_error "Le dépôt est incomplet : fichier 'artisan' manquant"
    print_info "Assurez-vous que le repository contient un projet Laravel complet"
    exit 1
fi

if [ ! -f "bootstrap/app.php" ]; then
    print_error "Le dépôt est incomplet : bootstrap/app.php manquant"
    exit 1
fi

if [ ! -f ".env.example" ]; then
    print_error ".env.example manquant"
    exit 1
fi

print_success "Projet Laravel valide"

# Créer les dossiers requis
mkdir -p bootstrap/cache
mkdir -p storage/framework/{sessions,views,cache}
mkdir -p storage/logs
chmod -R 775 storage bootstrap/cache

# Copier .env AVANT composer install
print_info "Création du fichier .env..."
cp .env.example .env

# Configurer .env
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

# Configuration HostClient
echo "" >> .env
echo "# HostClient" >> .env
echo "HOSTCLIENT_COMPANY_NAME=\"${COMPANY_NAME}\"" >> .env
echo "HOSTCLIENT_CURRENCY=EUR" >> .env
echo "HOSTCLIENT_LOCALE=fr" >> .env
echo "HOSTCLIENT_TIMEZONE=Europe/Paris" >> .env
echo "HOSTCLIENT_TAX_RATE=20.00" >> .env
echo "HOSTCLIENT_INVOICE_PREFIX=INV-" >> .env
echo "HOSTCLIENT_ORDER_PREFIX=ORD-" >> .env
echo "HOSTCLIENT_TICKET_PREFIX=TKT-" >> .env
echo "HOSTCLIENT_AUTO_SUSPEND_DAYS=7" >> .env
echo "HOSTCLIENT_AUTO_TERMINATE_DAYS=14" >> .env
echo "" >> .env
echo "# Mail (à configurer)" >> .env
echo "MAIL_MAILER=log" >> .env
echo "MAIL_HOST=127.0.0.1" >> .env
echo "MAIL_PORT=2525" >> .env
echo "MAIL_USERNAME=" >> .env
echo "MAIL_PASSWORD=" >> .env
echo "MAIL_ENCRYPTION=" >> .env
echo "MAIL_FROM_ADDRESS=\"noreply@${DOMAIN}\"" >> .env
echo "MAIL_FROM_NAME=\"${COMPANY_NAME}\"" >> .env
echo "" >> .env
echo "# Stripe (à configurer)" >> .env
echo "STRIPE_KEY=" >> .env
echo "STRIPE_SECRET=" >> .env
echo "STRIPE_WEBHOOK_SECRET=" >> .env
echo "" >> .env
echo "# PayPal (à configurer)" >> .env
echo "PAYPAL_CLIENT_ID=" >> .env
echo "PAYPAL_SECRET=" >> .env
echo "PAYPAL_MODE=sandbox" >> .env
echo "" >> .env
echo "# Mollie (à configurer)" >> .env
echo "MOLLIE_KEY=" >> .env

print_success ".env configuré"

# Composer install
print_info "Installation des dépendances PHP (Composer)..."
COMPOSER_ALLOW_SUPERUSER=1 composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction

print_success "Composer terminé"

# Générer la clé app
print_info "Génération de la clé d'application..."
php artisan key:generate --force
print_success "Clé générée"

# Migrations
print_info "Exécution des migrations..."
php artisan migrate --force
print_success "Migrations terminées"

# Seeders (rôles, permissions, paramètres, catégories, gateways, tickets)
print_info "Exécution des seeders..."
php artisan db:seed --force
print_success "Seeders terminés"

# Créer l'admin
print_info "Création du compte administrateur..."
ADMIN_FIRST=$(echo "$ADMIN_NAME" | awk '{print $1}')
ADMIN_LAST=$(echo "$ADMIN_NAME" | awk '{print $2}')
ADMIN_LAST=${ADMIN_LAST:-$ADMIN_FIRST}
ADMIN_HASH=$(php -r "echo password_hash('${ADMIN_PASSWORD}', PASSWORD_BCRYPT);")
mysql -u root "$DB_NAME" <<EOSQL 2>/dev/null
-- Créer / mettre à jour le compte admin
INSERT INTO users (first_name, last_name, email, password, email_verified_at, email_verified, is_active, created_at, updated_at)
VALUES ('${ADMIN_FIRST}', '${ADMIN_LAST}', '${ADMIN_EMAIL}', '${ADMIN_HASH}', NOW(), 1, 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE password='${ADMIN_HASH}', is_active=1, email_verified=1;

-- Créer le rôle 'admin' s'il n'existe pas (Spatie)
INSERT IGNORE INTO roles (name, guard_name, created_at, updated_at)
VALUES ('admin', 'web', NOW(), NOW());

-- Assigner le rôle 'admin' à l'utilisateur (Spatie: model_has_roles)
INSERT IGNORE INTO model_has_roles (role_id, model_type, model_id)
SELECT id, 'App\\Models\\User', u.id
FROM roles r, users u
WHERE r.name = 'admin' AND r.guard_name = 'web' AND u.email = '${ADMIN_EMAIL}';
EOSQL
print_success "Admin créé et rôle 'admin' assigné : ${ADMIN_EMAIL}"

# Storage link
php artisan storage:link
print_success "Lien de stockage créé"

# Permissions finales
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true
chmod -R 775 storage bootstrap/cache
chmod -R 755 public

# NPM + build
print_info "Installation des dépendances JavaScript..."
npm install
npm install @tailwindcss/vite --save-dev
print_info "Compilation des assets..."
npm run build
print_success "Assets compilés"

# Optimisation
print_info "Optimisation des caches..."
php artisan optimize:clear 2>/dev/null || true
php artisan config:cache
php artisan route:cache
php artisan view:cache
print_success "Caches optimisés"

# ============================================================
# 7. Configuration Nginx
# ============================================================
print_step "Configuration de Nginx"

# Vérifier si un certificat SSL existe
SSL_EXISTS=false
if [ -f "/etc/letsencrypt/live/${DOMAIN}/fullchain.pem" ]; then
    SSL_EXISTS=true
    print_info "Certificat SSL détecté pour ${DOMAIN}"
fi

# Configuration avec SSL si disponible
if [ "$SSL_EXISTS" = true ]; then
    cat > /etc/nginx/sites-available/hostclient <<EOFNGINX
server {
    listen 80;
    listen [::]:80;
    server_name ${DOMAIN};
    return 301 https://\$server_name\$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name ${DOMAIN};

    root $(pwd)/public;
    index index.php index.html;
    charset utf-8;

    # SSL Configuration
    ssl_certificate /etc/letsencrypt/live/${DOMAIN}/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/${DOMAIN}/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;
    ssl_prefer_server_ciphers on;
    ssl_session_cache shared:SSL:10m;
    ssl_session_timeout 10m;

    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;

    # Logs
    access_log /var/log/nginx/${DOMAIN}-access.log;
    error_log /var/log/nginx/${DOMAIN}-error.log;

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
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
EOFNGINX
    print_success "Configuration Nginx avec SSL créée"
else
    # Configuration HTTP seulement
    cat > /etc/nginx/sites-available/hostclient <<EOFNGINX
server {
    listen 80;
    listen [::]:80;
    server_name ${DOMAIN};

    root $(pwd)/public;
    index index.php index.html;
    charset utf-8;

    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;

    # Logs
    access_log /var/log/nginx/${DOMAIN}-access.log;
    error_log /var/log/nginx/${DOMAIN}-error.log;

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
    print_success "Configuration Nginx (HTTP) créée"
fi

ln -sf /etc/nginx/sites-available/hostclient /etc/nginx/sites-enabled/hostclient
rm -f /etc/nginx/sites-enabled/default

# Test et redémarrage
if nginx -t 2>&1; then
    systemctl restart nginx
    systemctl restart php8.2-fpm
    systemctl enable nginx php8.2-fpm
    print_success "Nginx configuré pour ${DOMAIN}"
else
    print_error "Erreur dans la configuration Nginx"
    exit 1
fi

# ============================================================
# 8. Cron
# ============================================================
print_step "Configuration des tâches planifiées"
ARTISAN_PATH="$(pwd)/artisan"
(crontab -l 2>/dev/null | grep -v "hostclient"; echo "* * * * * php ${ARTISAN_PATH} schedule:run >> /dev/null 2>&1") | crontab -
print_success "Cron configuré"

# ============================================================
# 9. Sauvegarde des identifiants
# ============================================================
CREDS_FILE="$(pwd)/CREDENTIALS.txt"
cat > "${CREDS_FILE}" <<EOFCREDS
╔════════════════════════════════════════════════════════╗
║        INFORMATIONS D'INSTALLATION - HostClient         ║
╚════════════════════════════════════════════════════════╝

Date          : $(date)
URL           : ${APP_URL}
Dossier       : $(pwd)

──────────────────────────────────────────────────────────
COMPTE ADMINISTRATEUR
──────────────────────────────────────────────────────────
Email         : ${ADMIN_EMAIL}
Mot de passe  : ${ADMIN_PASSWORD}

──────────────────────────────────────────────────────────
BASE DE DONNÉES
──────────────────────────────────────────────────────────
Host          : 127.0.0.1
Port          : 3306
Database      : ${DB_NAME}
Utilisateur   : ${DB_USER}
Mot de passe  : ${DB_PASSWORD}

──────────────────────────────────────────────────────────
CONFIGURATION À COMPLÉTER DANS .env
──────────────────────────────────────────────────────────
# Mail (obligatoire pour les notifications)
MAIL_MAILER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@${DOMAIN}"
MAIL_FROM_NAME="${COMPANY_NAME}"

# Stripe (optionnel)
STRIPE_KEY=
STRIPE_SECRET=
STRIPE_WEBHOOK_SECRET=

# PayPal (optionnel)
PAYPAL_CLIENT_ID=
PAYPAL_SECRET=
PAYPAL_MODE=sandbox

# Mollie (optionnel)
MOLLIE_KEY=

──────────────────────────────────────────────────────────
PROCHAINES ÉTAPES
──────────────────────────────────────────────────────────
1. Connectez-vous : ${APP_URL}/admin/login
2. Configurez Mail dans Paramètres > Email
3. Activez les passerelles de paiement (Paramètres > Paiement)
4. Créez vos catégories et produits
5. Configurez SSL : certbot --nginx -d ${DOMAIN}

⚠️  Supprimez ce fichier après avoir noté les infos :
   rm ${CREDS_FILE}
EOFCREDS
chmod 600 "${CREDS_FILE}"

# Sauvegarde .env
cp .env ".env.backup.$(date +%Y%m%d_%H%M%S)"
chmod 600 .env

# ============================================================
# 10. Optimisation finale
# ============================================================
print_step "Optimisation finale"
php artisan optimize:clear 2>/dev/null || true
php artisan config:cache
php artisan route:cache
php artisan view:cache
print_success "Optimisations appliquées"

# ============================================================
# 10. Résumé final
# ============================================================
echo ""
echo -e "${GREEN}╔════════════════════════════════════════════════════════╗${NC}"
echo -e "${GREEN}║       Installation terminée avec succès ! 🎉           ║${NC}"
echo -e "${GREEN}╚════════════════════════════════════════════════════════╝${NC}"
echo ""
echo -e " ${CYAN}URL      :${NC} ${YELLOW}${APP_URL}${NC}"
echo -e " ${CYAN}Email    :${NC} ${YELLOW}${ADMIN_EMAIL}${NC}"
echo -e " ${CYAN}Password :${NC} ${YELLOW}${ADMIN_PASSWORD}${NC}"
echo -e " ${CYAN}Dossier  :${NC} $(pwd)"
echo ""
echo -e " ${YELLOW}Pour activer SSL :${NC}"
echo -e "   apt-get install certbot python3-certbot-nginx"
echo -e "   certbot --nginx -d ${DOMAIN}"
echo ""
echo -e " ${YELLOW}Identifiants sauvegardés :${NC} ${CREDS_FILE}"
echo ""
