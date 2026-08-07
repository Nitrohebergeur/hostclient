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

# Extraire le domaine depuis l'URL
DOMAIN=$(echo "$APP_URL" | sed 's|https\?://||' | sed 's|/.*||')

print_success "Configuration collectée pour ${DOMAIN}"

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
apt-get install -y curl wget git unzip ca-certificates apt-transport-https lsb-release gnupg2 net-tools

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

# Nginx + certbot
print_info "Installation Nginx + Certbot..."
apt-get install -y nginx certbot python3-certbot-nginx
print_success "Nginx et Certbot installés"

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
if [ ! -f "artisan" ] || [ ! -f "bootstrap/app.php" ] || [ ! -f ".env.example" ]; then
    print_error "Le dépôt est incomplet. Vérifiez le repository."
    exit 1
fi
print_success "Projet Laravel valide"

# Créer les dossiers requis
mkdir -p bootstrap/cache
mkdir -p storage/framework/{sessions,views,cache}
mkdir -p storage/logs
touch storage/logs/laravel.log
chmod -R 775 storage bootstrap/cache

# Copier .env
print_info "Création du fichier .env..."
cp .env.example .env

# Configurer .env
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

# Seeders
print_info "Exécution des seeders (rôles, permissions, paramètres)..."
php artisan db:seed --force
print_success "Seeders terminés"

# Créer le compte admin et assigner le rôle via SQL direct (pas de tinker en prod)
print_info "Création du compte administrateur..."
ADMIN_FIRST=$(echo "$ADMIN_NAME" | awk '{print $1}')
ADMIN_LAST=$(echo "$ADMIN_NAME" | awk '{print $2}')
ADMIN_LAST=${ADMIN_LAST:-$ADMIN_FIRST}
ADMIN_HASH=$(php -r "echo password_hash('${ADMIN_PASSWORD}', PASSWORD_BCRYPT);")

mysql -u root "$DB_NAME" <<EOSQL
-- Créer ou mettre à jour le compte admin
INSERT INTO users (first_name, last_name, email, password, email_verified_at, email_verified, is_active, created_at, updated_at)
VALUES ('${ADMIN_FIRST}', '${ADMIN_LAST}', '${ADMIN_EMAIL}', '${ADMIN_HASH}', NOW(), 1, 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE password='${ADMIN_HASH}', is_active=1, email_verified=1, updated_at=NOW();

-- S'assurer que le rôle admin existe (Spatie)
INSERT IGNORE INTO roles (name, guard_name, created_at, updated_at)
VALUES ('admin', 'web', NOW(), NOW());

-- Assigner le rôle admin à l'utilisateur (Spatie: model_has_roles)
INSERT IGNORE INTO model_has_roles (role_id, model_type, model_id)
SELECT r.id, 'App\\\\Models\\\\User', u.id
FROM roles r
JOIN users u ON u.email = '${ADMIN_EMAIL}'
WHERE r.name = 'admin' AND r.guard_name = 'web';
EOSQL
print_success "Admin créé et rôle 'admin' assigné : ${ADMIN_EMAIL}"

# Storage link
php artisan storage:link 2>/dev/null || true
print_success "Lien de stockage créé"

# Permissions finales (CRITIQUE : www-data doit pouvoir écrire dans storage)
print_info "Correction des permissions..."
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage
chmod -R 775 bootstrap/cache
chmod -R 755 public
# S'assurer que les sous-dossiers existent avec les bonnes permissions
mkdir -p storage/framework/{sessions,views,cache}
mkdir -p storage/logs
touch storage/logs/laravel.log
chown -R www-data:www-data storage
chmod -R 775 storage
print_success "Permissions corrigées"

# NPM + build
print_info "Installation des dépendances JavaScript..."
npm install
# Installer @tailwindcss/vite explicitement (requis par vite.config.js)
npm install @tailwindcss/vite tailwindcss --save-dev
print_info "Compilation des assets Vite..."
npm run build

# Vérifier que le manifest Vite existe (critique pour Laravel)
if [ ! -f "public/build/manifest.json" ]; then
    print_error "Le manifest Vite n'a pas été généré. Nouvelle tentative..."
    npm install @tailwindcss/vite tailwindcss --save-dev
    npm run build
    if [ ! -f "public/build/manifest.json" ]; then
        print_error "Échec de la compilation des assets. Vérifiez les erreurs npm ci-dessus."
        exit 1
    fi
fi
print_success "Assets compilés (manifest.json généré)"
print_success "Assets compilés"

# ============================================================
# 7. Configuration Nginx
# ============================================================
print_step "Configuration de Nginx"

APP_DIR=$(pwd)

# Vérifier si un certificat SSL existe déjà
SSL_EXISTS=false
if [ -f "/etc/letsencrypt/live/${DOMAIN}/fullchain.pem" ]; then
    SSL_EXISTS=true
    print_info "Certificat SSL existant détecté pour ${DOMAIN}"
fi

# Créer la configuration HTTP de base d'abord (nécessaire pour certbot)
cat > /etc/nginx/sites-available/hostclient <<EOFNGINX
server {
    listen 80;
    listen [::]:80;
    server_name ${DOMAIN};

    root ${APP_DIR}/public;
    index index.php index.html;
    charset utf-8;

    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;

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

# Obtenir le certificat SSL automatiquement si le domaine est accessible
if [ "$SSL_EXISTS" = false ] && [ "$DOMAIN" != "localhost" ]; then
    print_info "Tentative d'obtention du certificat SSL pour ${DOMAIN}..."
    # S'assurer que le plugin nginx certbot est bien installé
    apt-get install -y python3-certbot-nginx -qq
    if certbot --nginx -d "${DOMAIN}" --non-interactive --agree-tos -m "admin@${DOMAIN}" 2>/dev/null; then
        print_success "Certificat SSL obtenu et configuré automatiquement ✓"
        SSL_EXISTS=true
    else
        print_warning "Impossible d'obtenir le SSL automatiquement (DNS pas encore propagé ?)."
        print_warning "Le site fonctionne en HTTP. Pour activer HTTPS plus tard :"
        echo -e "   ${CYAN}apt-get install -y python3-certbot-nginx${NC}"
        echo -e "   ${CYAN}certbot --nginx -d ${DOMAIN}${NC}"
    fi
else
    print_success "Certificat SSL déjà configuré ✓"
fi

systemctl restart php8.2-fpm
print_success "Nginx et PHP-FPM configurés pour ${DOMAIN}"

# ============================================================
# 8. Optimisation finale Laravel
# ============================================================
print_step "Optimisation finale"

php artisan optimize:clear 2>/dev/null || true
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan permission:cache-reset 2>/dev/null || true

print_success "Caches Laravel optimisés"

# ============================================================
# 9. Cron
# ============================================================
print_step "Configuration des tâches planifiées"
ARTISAN_PATH="$(pwd)/artisan"
(crontab -l 2>/dev/null | grep -v "hostclient"; echo "* * * * * php ${ARTISAN_PATH} schedule:run >> /dev/null 2>&1") | crontab -
print_success "Cron configuré"

# ============================================================
# 10. Sauvegarde des identifiants
# ============================================================
CREDS_FILE="$(pwd)/CREDENTIALS.txt"
cat > "${CREDS_FILE}" <<EOFCREDS
╔════════════════════════════════════════════════════════╗
║        INFORMATIONS D'INSTALLATION - HostClient        ║
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
PROCHAINES ÉTAPES
──────────────────────────────────────────────────────────
1. Connectez-vous : ${APP_URL}/login
2. Configurez Mail dans Paramètres > Email
3. Activez les passerelles de paiement dans Paramètres
4. Créez vos catégories et produits

⚠️  Supprimez ce fichier après avoir noté les infos :
   rm ${CREDS_FILE}
EOFCREDS
chmod 600 "${CREDS_FILE}"

# ============================================================
# 11. Résumé final
# ============================================================
FINAL_URL="${APP_URL}"
if [ "$SSL_EXISTS" = true ] && [[ "$APP_URL" != https* ]]; then
    FINAL_URL="https://${DOMAIN}"
fi

echo ""
echo -e "${GREEN}╔════════════════════════════════════════════════════════╗${NC}"
echo -e "${GREEN}║       Installation terminée avec succès ! 🎉           ║${NC}"
echo -e "${GREEN}╚════════════════════════════════════════════════════════╝${NC}"
echo ""
echo -e " ${CYAN}URL       :${NC} ${YELLOW}${FINAL_URL}/login${NC}"
echo -e " ${CYAN}Email     :${NC} ${YELLOW}${ADMIN_EMAIL}${NC}"
echo -e " ${CYAN}Password  :${NC} ${YELLOW}${ADMIN_PASSWORD}${NC}"
echo -e " ${CYAN}Dossier   :${NC} $(pwd)"
echo ""
echo -e " ${MAGENTA}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo -e " ${YELLOW}📧 Configurer les emails${NC}"
echo -e "   Connexion > ${CYAN}Paramètres > Email${NC}"
echo ""
echo -e " ${YELLOW}💳 Activer les passerelles de paiement${NC}"
echo -e "   Connexion > ${CYAN}Paramètres > Passerelles de paiement${NC}"
echo ""
if [ "$SSL_EXISTS" = false ]; then
echo -e " ${YELLOW}🔒 Activer SSL (HTTPS)${NC}"
echo -e "   ${CYAN}certbot --nginx -d ${DOMAIN}${NC}"
echo ""
fi
echo -e " ${YELLOW}📁 Identifiants sauvegardés :${NC} ${CREDS_FILE}"
echo -e " ${MAGENTA}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo ""
