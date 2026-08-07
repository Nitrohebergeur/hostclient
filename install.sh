#!/usr/bin/env bash
# =============================================================================
#  HostClient — Installateur automatique
#  https://github.com/Nitrohebergeur/hostclient
# =============================================================================
set -euo pipefail

# ── Couleurs ──────────────────────────────────────────────────────────────────
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
CYAN='\033[0;36m'
BOLD='\033[1m'
NC='\033[0m'

# ── Configuration par défaut ──────────────────────────────────────────────────
REPO="https://github.com/Nitrohebergeur/hostclient.git"
INSTALL_DIR="/var/www/hostclient"
PHP_MIN="8.4"
NODE_MIN="20"
BRANCH="main"

# ── Fonctions utilitaires ─────────────────────────────────────────────────────
print_banner() {
    echo -e "${CYAN}"
    echo "  ██╗  ██╗ ██████╗ ███████╗████████╗ ██████╗██╗     ██╗███████╗███╗   ██╗████████╗"
    echo "  ██║  ██║██╔═══██╗██╔════╝╚══██╔══╝██╔════╝██║     ██║██╔════╝████╗  ██║╚══██╔══╝"
    echo "  ███████║██║   ██║███████╗   ██║   ██║     ██║     ██║█████╗  ██╔██╗ ██║   ██║   "
    echo "  ██╔══██║██║   ██║╚════██║   ██║   ██║     ██║     ██║██╔══╝  ██║╚██╗██║   ██║   "
    echo "  ██║  ██║╚██████╔╝███████║   ██║   ╚██████╗███████╗██║███████╗██║ ╚████║   ██║   "
    echo "  ╚═╝  ╚═╝ ╚═════╝ ╚══════╝   ╚═╝    ╚═════╝╚══════╝╚═╝╚══════╝╚═╝  ╚═══╝   ╚═╝   "
    echo -e "${NC}"
    echo -e "  ${BOLD}Plateforme SaaS d'hebergement web — Installateur v1.0.0${NC}"
    echo -e "  ${BLUE}https://github.com/Nitrohebergeur/hostclient${NC}"
    echo ""
    echo -e "  ${YELLOW}Ce script doit etre execute en tant que root${NC}"
    echo ""
}

log_step() {
    echo -e "\n${BOLD}${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
    echo -e "${BOLD}${BLUE}  ▶  $1${NC}"
    echo -e "${BOLD}${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}\n"
}

log_ok()   { echo -e "  ${GREEN}✓${NC}  $1"; }
log_warn() { echo -e "  ${YELLOW}⚠${NC}  $1"; }
log_err()  { echo -e "  ${RED}✗${NC}  $1"; }
log_info() { echo -e "  ${CYAN}i${NC}  $1"; }

ask() {
    local prompt="$1"
    local default="${2:-}"
    local answer
    if [ -n "$default" ]; then
        read -rp "  ${prompt} [${default}]: " answer
        echo "${answer:-$default}"
    else
        read -rp "  ${prompt}: " answer
        echo "$answer"
    fi
}

ask_secret() {
    local prompt="$1"
    local answer
    read -srp "  ${prompt}: " answer
    echo ""
    echo "$answer"
}

confirm() {
    local prompt="${1:-Continuer ?}"
    local answer
    read -rp "  ${prompt} [o/N]: " answer
    [[ "$answer" =~ ^[oOyY]$ ]]
}

check_root() {
    if [ "$EUID" -ne 0 ]; then
        log_err "Ce script doit etre execute en tant que root."
        echo -e "  Reessayez avec : sudo bash install.sh"
        exit 1
    fi
}

detect_os() {
    if [ -f /etc/os-release ]; then
        . /etc/os-release
        OS=$ID
        OS_VERSION=$VERSION_ID
    else
        log_err "Systeme d'exploitation non reconnu."
        exit 1
    fi

    case "$OS" in
        ubuntu|debian)
            PKG_UPDATE="apt-get update -qq"
            PKG_INSTALL="apt-get install -y -qq"
            ;;
        centos|rhel|rocky|almalinux|fedora)
            PKG_UPDATE="dnf check-update -q || true"
            PKG_INSTALL="dnf install -y -q"
            ;;
        *)
            log_warn "Distribution non officiellement supportee : $OS"
            PKG_UPDATE="apt-get update -qq"
            PKG_INSTALL="apt-get install -y -qq"
            ;;
    esac

    log_ok "Systeme detecte : $OS $OS_VERSION"
}

version_ge() {
    printf '%s\n%s\n' "$2" "$1" | sort -V -C
}

check_dependencies() {
    log_step "Verification des prerequis"

    local missing=()

    command -v git &>/dev/null && log_ok "git $(git --version | awk '{print $3}')" || missing+=("git")

    if command -v php &>/dev/null; then
        PHP_VERSION=$(php -r "echo PHP_VERSION;")
        if version_ge "$PHP_VERSION" "$PHP_MIN"; then
            log_ok "PHP $PHP_VERSION"
        else
            log_err "PHP $PHP_VERSION detecte — PHP $PHP_MIN+ requis"
            missing+=("php")
        fi
    else
        missing+=("php")
    fi

    command -v composer &>/dev/null && log_ok "Composer $(composer --version --no-ansi 2>/dev/null | awk '{print $3}')" || missing+=("composer")

    if command -v node &>/dev/null; then
        NODE_VERSION=$(node -v | sed 's/v//')
        MAJOR="${NODE_VERSION%%.*}"
        if [ "$MAJOR" -ge "$NODE_MIN" ]; then
            log_ok "Node.js v$NODE_VERSION"
        else
            log_err "Node.js v$NODE_VERSION detecte — Node $NODE_MIN+ requis"
            missing+=("nodejs")
        fi
    else
        missing+=("nodejs")
    fi

    command -v mysql &>/dev/null && log_ok "MySQL/MariaDB disponible" || log_warn "MySQL/MariaDB non trouve — a configurer manuellement"
    command -v redis-cli &>/dev/null && log_ok "Redis disponible" || log_warn "Redis non trouve — recommande"

    for tool in curl unzip; do
        command -v "$tool" &>/dev/null && log_ok "$tool disponible" || missing+=("$tool")
    done

    if [ ${#missing[@]} -gt 0 ]; then
        echo ""
        log_warn "Dependances manquantes : ${missing[*]}"
        log_info "Installation automatique des dependances manquantes..."
        install_dependencies "${missing[@]}"
    fi
}

install_dependencies() {
    log_step "Installation des dependances systeme"
    local deps=("$@")
    $PKG_UPDATE

    for dep in "${deps[@]}"; do
        case "$dep" in
            php)      install_php ;;
            composer) install_composer ;;
            nodejs)   install_nodejs ;;
            *)        $PKG_INSTALL "$dep" && log_ok "$dep installe" ;;
        esac
    done
}

install_php() {
    log_info "Installation de PHP 8.4..."
    case "$OS" in
        ubuntu|debian)
            $PKG_INSTALL software-properties-common
            add-apt-repository ppa:ondrej/php -y
            $PKG_UPDATE
            $PKG_INSTALL php8.4-fpm php8.4-cli php8.4-mysql php8.4-pgsql \
                php8.4-mbstring php8.4-xml php8.4-curl php8.4-zip \
                php8.4-gd php8.4-intl php8.4-bcmath php8.4-redis \
                php8.4-opcache php8.4-soap
            ;;
        centos|rhel|rocky|almalinux)
            $PKG_INSTALL epel-release
            $PKG_INSTALL "https://rpms.remirepo.net/enterprise/remi-release-$(rpm -E %rhel).rpm"
            dnf module enable php:remi-8.4 -y
            $PKG_INSTALL php php-fpm php-mysqlnd php-mbstring php-xml \
                php-curl php-zip php-gd php-intl php-bcmath php-redis php-opcache
            ;;
    esac
    log_ok "PHP 8.4 installe"
}

install_composer() {
    log_info "Installation de Composer..."
    curl -sS https://getcomposer.org/installer | php
    mv composer.phar /usr/local/bin/composer
    chmod +x /usr/local/bin/composer
    log_ok "Composer installe"
}

install_nodejs() {
    log_info "Installation de Node.js 20..."
    curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
    $PKG_INSTALL nodejs
    log_ok "Node.js 20 installe"
}

collect_config() {
    log_step "Configuration de l'installation"
    echo -e "  ${CYAN}Remplissez les informations suivantes (Entree = valeur par defaut)${NC}\n"

    INSTALL_DIR=$(ask "Repertoire d'installation" "/var/www/hostclient")
    BRANCH=$(ask "Branche ou version a installer" "main")

    echo ""
    echo -e "  ── Base de donnees ──"
    DB_HOST=$(ask        "Hote MySQL"                          "127.0.0.1")
    DB_PORT=$(ask        "Port MySQL"                          "3306")
    DB_NAME=$(ask        "Nom de la base"                      "hostclient")
    DB_USER=$(ask        "Utilisateur MySQL"                   "hostclient")
    DB_PASS=$(ask_secret "Mot de passe MySQL")
    DB_ROOT_PASS=$(ask_secret "Mot de passe root MySQL (pour creer la BDD)")

    echo ""
    echo -e "  ── Application ──"
    APP_URL=$(ask  "URL de l'application"  "http://localhost")
    APP_ENV=$(ask  "Environnement"         "production")

    echo ""
    echo -e "  ── Administrateur ──"
    ADMIN_NAME=$(ask    "Nom de l'administrateur"  "Administrateur")
    ADMIN_EMAIL=$(ask   "Email administrateur"     "admin@example.com")
    ADMIN_PASS=$(ask_secret "Mot de passe administrateur")

    echo ""
    echo -e "  ── Redis (optionnel) ──"
    REDIS_HOST=$(ask  "Hote Redis"   "127.0.0.1")
    REDIS_PORT=$(ask  "Port Redis"   "6379")
    REDIS_PASS=$(ask_secret "Mot de passe Redis (vide = aucun)")

    echo ""
    echo -e "  ${CYAN}i${NC}  La configuration email (SMTP) se fait depuis le panel admin."
    echo -e "  ${CYAN}i${NC}  Rendez-vous sur : ${APP_URL}/admin/settings apres l'installation."

    echo ""
    echo -e "  ${YELLOW}── Resume de la configuration ──${NC}"
    echo -e "  Repertoire    : ${CYAN}$INSTALL_DIR${NC}"
    echo -e "  URL           : ${CYAN}$APP_URL${NC}"
    echo -e "  Environnement : ${CYAN}$APP_ENV${NC}"
    echo -e "  Base MySQL    : ${CYAN}$DB_HOST:$DB_PORT/$DB_NAME${NC} (user: $DB_USER)"
    echo -e "  Redis         : ${CYAN}$REDIS_HOST:$REDIS_PORT${NC}"
    echo -e "  Admin         : ${CYAN}$ADMIN_EMAIL${NC}"
    echo ""

    if ! confirm "Confirmer et lancer l'installation ?"; then
        echo -e "  ${YELLOW}Installation annulee.${NC}"
        exit 0
    fi
}

clone_repository() {
    log_step "Telechargement de HostClient depuis GitHub"

    if [ -d "$INSTALL_DIR" ]; then
        log_info "Le repertoire $INSTALL_DIR existe, suppression..."
        rm -rf "$INSTALL_DIR"
    fi

    log_info "Clonage depuis $REPO (branche: $BRANCH)..."
    git clone --branch "$BRANCH" --depth 1 "$REPO" "$INSTALL_DIR"

    # S'assurer que Controller.php de base existe
    if [ ! -f "$INSTALL_DIR/app/Http/Controllers/Controller.php" ]; then
        cat > "$INSTALL_DIR/app/Http/Controllers/Controller.php" <<'CONTROLLER'
<?php

namespace App\Http\Controllers;

abstract class Controller
{
    //
}
CONTROLLER
        log_info "Controller.php de base cree"
    fi

    log_ok "Code source telecharge dans $INSTALL_DIR"
}

setup_env() {
    log_step "Configuration du fichier .env"
    cd "$INSTALL_DIR"
    cp .env.example .env

    # Fonction de remplacement robuste via python3
    # Gere tous les caracteres speciaux (/, @, &, etc.) sans conflit sed
    set_env() {
        local key="$1"
        local val="$2"
        python3 -c "
import re, sys
key = sys.argv[1]
val = sys.argv[2]
# Toujours entourer la valeur de guillemets doubles pour les caracteres speciaux
with open('.env', 'r') as f:
    content = f.read()
content = re.sub(r'^' + re.escape(key) + r'=.*', key + '=\"' + val.replace('\"', '\\\\\"') + '\"', content, flags=re.MULTILINE)
with open('.env', 'w') as f:
    f.write(content)
" "$key" "$val"
    }

    set_env "APP_NAME"         "HostClient"
    set_env "APP_ENV"          "$APP_ENV"
    set_env "APP_DEBUG"        "$([ "$APP_ENV" = 'production' ] && echo 'false' || echo 'true')"
    set_env "APP_URL"          "$APP_URL"
    set_env "DB_CONNECTION"    "mysql"
    set_env "DB_HOST"          "$DB_HOST"
    set_env "DB_PORT"          "$DB_PORT"
    set_env "DB_DATABASE"      "$DB_NAME"
    set_env "DB_USERNAME"      "$DB_USER"
    set_env "DB_PASSWORD"      "$DB_PASS"
    set_env "REDIS_HOST"       "$REDIS_HOST"
    set_env "REDIS_PORT"       "$REDIS_PORT"
    set_env "REDIS_PASSWORD"   "${REDIS_PASS:-null}"
    set_env "CACHE_STORE"      "redis"
    set_env "CACHE_DRIVER"     "redis"
    set_env "SESSION_DRIVER"   "redis"
    set_env "QUEUE_CONNECTION" "redis"
    set_env "REDIS_CLIENT"     "predis"
    # DB_ROOT_PASS n'est PAS ecrit dans .env — usage interne uniquement
    # SMTP laisse vide — configurable depuis le panel admin (/admin/settings)
    set_env "MAIL_MAILER"      "smtp"
    set_env "MAIL_HOST"        ""
    set_env "MAIL_PORT"        "587"
    set_env "MAIL_USERNAME"    ""
    set_env "MAIL_PASSWORD"    ""
    set_env "MAIL_FROM_ADDRESS" ""
    set_env "MAIL_FROM_NAME"   "HostClient"

    log_ok "Fichier .env configure"
    log_info "Email SMTP : a configurer dans le panel admin > Parametres"
}

setup_database() {
    log_step "Creation de la base de donnees MySQL"

    if ! command -v mysql &>/dev/null; then
        log_warn "MySQL non trouve — assurez-vous que la base de donnees existe."
        return
    fi

    log_info "Creation de la base '$DB_NAME' et de l'utilisateur '$DB_USER'..."
    mysql -u root -p"$DB_ROOT_PASS" 2>/dev/null <<SQL || log_warn "Impossible de creer la BDD automatiquement — faites-le manuellement."
CREATE DATABASE IF NOT EXISTS \`${DB_NAME}\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '${DB_USER}'@'${DB_HOST}' IDENTIFIED BY '${DB_PASS}';
GRANT ALL PRIVILEGES ON \`${DB_NAME}\`.* TO '${DB_USER}'@'${DB_HOST}';
FLUSH PRIVILEGES;
SQL
    log_ok "Base de donnees '$DB_NAME' creee"
}

install_php_deps() {
    log_step "Installation des dependances PHP (Composer)"

    export COMPOSER_ALLOW_SUPERUSER=1
    composer config --global --no-interaction policy.advisories.block false 2>/dev/null || true

    cd "$INSTALL_DIR"
    log_info "Repertoire : $(pwd)"

    local OPTS="--no-interaction --optimize-autoloader --no-scripts"

    if [ "$APP_ENV" = "production" ]; then
        COMPOSER_ALLOW_SUPERUSER=1 composer install --no-dev $OPTS 2>&1 || {
            log_err "Echec de l'installation des dependances PHP."
            exit 1
        }
    else
        COMPOSER_ALLOW_SUPERUSER=1 composer install $OPTS 2>&1 || {
            log_err "Echec de l'installation des dependances PHP."
            exit 1
        }
    fi

    # Lancer manuellement le discover (evite le probleme de repertoire)
    COMPOSER_ALLOW_SUPERUSER=1 composer dump-autoload --optimize --no-scripts 2>/dev/null || true
    php "$INSTALL_DIR/artisan" package:discover --ansi 2>/dev/null || true

    log_ok "Dependances PHP installees"
}

install_node_deps() {
    log_step "Installation des dependances Node.js et compilation des assets"
    cd "$INSTALL_DIR"
    npm install
    npm run build
    log_ok "Assets compiles avec succes"
}

run_migrations() {
    log_step "Migrations et donnees initiales"
    cd "$INSTALL_DIR"

    php "$INSTALL_DIR/artisan" key:generate --no-interaction --force
    log_ok "Cle d'application generee"

    # Publier les configs Spatie Permission
    php "$INSTALL_DIR/artisan" vendor:publish \
        --provider="Spatie\Permission\PermissionServiceProvider" \
        --force --no-interaction 2>/dev/null || true
    log_ok "Migrations Spatie publiees"

    # Forcer Spatie a utiliser le cache redis et non database
    php "$INSTALL_DIR/artisan" config:clear --no-interaction 2>/dev/null || true

    php "$INSTALL_DIR/artisan" migrate --force --no-interaction
    log_ok "Migrations executees"

    php "$INSTALL_DIR/artisan" db:seed --force --no-interaction
    log_ok "Donnees initiales inserees"

    # Creer le compte admin avec les infos saisies
    php "$INSTALL_DIR/artisan" tinker --no-interaction <<PHP 2>/dev/null || true
\$user = \App\Models\User::updateOrCreate(
    ['email' => '${ADMIN_EMAIL}'],
    [
        'name'              => '${ADMIN_NAME}',
        'password'          => \Illuminate\Support\Facades\Hash::make('${ADMIN_PASS}'),
        'status'            => 'active',
        'email_verified_at' => now(),
    ]
);
if (!\Spatie\Permission\Models\Role::where('name', 'admin')->exists()) {
    \Spatie\Permission\Models\Role::create(['name' => 'admin', 'guard_name' => 'web']);
}
\$user->syncRoles(['admin']);
exit;
PHP
    log_ok "Compte administrateur cree : ${ADMIN_EMAIL}"
}

setup_storage() {
    log_step "Configuration du stockage et des permissions"
    cd "$INSTALL_DIR"

    php "$INSTALL_DIR/artisan" storage:link --no-interaction 2>/dev/null || true

    chown -R www-data:www-data "$INSTALL_DIR" 2>/dev/null || \
    chown -R nginx:nginx "$INSTALL_DIR" 2>/dev/null || \
    log_warn "Impossible de changer le proprietaire — ajustez manuellement."

    chmod -R 755 "$INSTALL_DIR"
    chmod -R 775 "$INSTALL_DIR/storage"
    chmod -R 775 "$INSTALL_DIR/bootstrap/cache"

    log_ok "Permissions configurees"
}

optimize_app() {
    log_step "Optimisation de l'application"
    cd "$INSTALL_DIR"

    if [ "$APP_ENV" = "production" ]; then
        php "$INSTALL_DIR/artisan" config:cache --no-interaction
        php "$INSTALL_DIR/artisan" route:cache  --no-interaction
        php "$INSTALL_DIR/artisan" view:cache   --no-interaction
        php "$INSTALL_DIR/artisan" event:cache  --no-interaction
        log_ok "Cache production active"
    fi

    php "$INSTALL_DIR/artisan" queue:restart --no-interaction 2>/dev/null || true
    log_ok "Application optimisee"
}

setup_nginx() {
    log_step "Configuration de Nginx"

    # Installer Nginx si absent
    if ! command -v nginx &>/dev/null; then
        log_info "Installation de Nginx..."
        $PKG_INSTALL nginx
        systemctl enable nginx
        systemctl start nginx
        log_ok "Nginx installe et demarre"
    fi

    local DOMAIN
    DOMAIN=$(echo "$APP_URL" | sed 's|https\?://||' | sed 's|/.*||')

    # Detecter le socket PHP-FPM
    local PHP_SOCKET="/var/run/php/php8.4-fpm.sock"
    if [ -S "/run/php-fpm/www.sock" ]; then
        PHP_SOCKET="/run/php-fpm/www.sock"
    elif [ -S "/var/run/php-fpm/php-fpm.sock" ]; then
        PHP_SOCKET="/var/run/php-fpm/php-fpm.sock"
    fi

    local NGINX_CONF="/etc/nginx/sites-available/hostclient"

    # Creer sites-available/sites-enabled si necessaire (CentOS/RHEL)
    if [ ! -d /etc/nginx/sites-available ]; then
        mkdir -p /etc/nginx/sites-available /etc/nginx/sites-enabled
        if ! grep -q "sites-enabled" /etc/nginx/nginx.conf; then
            sed -i '/http {/a\    include /etc/nginx/sites-enabled/*;' /etc/nginx/nginx.conf
        fi
    fi

    log_info "Creation de la configuration Nginx pour ${DOMAIN}..."

    # Ecriture du bloc server sans heredoc pour eviter les problemes de parsing
    {
        echo "server {"
        echo "    listen 80;"
        echo "    listen [::]:80;"
        echo "    server_name ${DOMAIN} www.${DOMAIN};"
        echo "    root ${INSTALL_DIR}/public;"
        echo ""
        echo "    add_header X-Frame-Options \"SAMEORIGIN\" always;"
        echo "    add_header X-Content-Type-Options \"nosniff\" always;"
        echo "    add_header X-XSS-Protection \"1; mode=block\" always;"
        echo "    add_header Referrer-Policy \"no-referrer-when-downgrade\" always;"
        echo ""
        echo "    index index.php index.html;"
        echo "    charset utf-8;"
        echo "    client_max_body_size 100M;"
        echo ""
        echo "    access_log /var/log/nginx/hostclient-access.log;"
        echo "    error_log  /var/log/nginx/hostclient-error.log;"
        echo ""
        echo "    location / {"
        echo '        try_files $uri $uri/ /index.php?$query_string;'
        echo "    }"
        echo ""
        echo "    location = /favicon.ico { access_log off; log_not_found off; }"
        echo "    location = /robots.txt  { access_log off; log_not_found off; }"
        echo ""
        echo "    error_page 404 /index.php;"
        echo ""
        echo "    location ~ \\.php\$ {"
        echo '        try_files $uri =404;'
        echo '        fastcgi_split_path_info ^(.+\.php)(/.+)$;'
        echo "        fastcgi_pass unix:${PHP_SOCKET};"
        echo "        fastcgi_index index.php;"
        echo '        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;'
        echo "        include fastcgi_params;"
        echo "        fastcgi_read_timeout 300;"
        echo "        fastcgi_buffers 16 16k;"
        echo "        fastcgi_buffer_size 32k;"
        echo "    }"
        echo ""
        echo "    location ~ /\\.(?!well-known).* { deny all; }"
        echo ""
        echo "    gzip on;"
        echo "    gzip_vary on;"
        echo "    gzip_proxied any;"
        echo "    gzip_comp_level 6;"
        echo "    gzip_types text/plain text/css text/xml text/javascript application/json application/javascript application/xml+rss font/truetype font/opentype image/svg+xml;"
        echo ""
        echo "    location ~* \\.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)\$ {"
        echo '        expires 1y;'
        echo '        add_header Cache-Control "public, immutable";'
        echo "    }"
        echo "}"
    } > "$NGINX_CONF"

    ln -sf "$NGINX_CONF" /etc/nginx/sites-enabled/hostclient

    # Desactiver le site par defaut
    rm -f /etc/nginx/sites-enabled/default 2>/dev/null || true

    # Tester et recharger
    if nginx -t 2>/dev/null; then
        systemctl reload nginx
        log_ok "Nginx configure pour ${DOMAIN}"
    else
        log_err "Erreur de configuration Nginx"
        nginx -t
        return 1
    fi

    # Redemarrer PHP-FPM
    if systemctl is-active --quiet php8.4-fpm 2>/dev/null; then
        systemctl restart php8.4-fpm
    elif systemctl is-active --quiet php-fpm 2>/dev/null; then
        systemctl restart php-fpm
    fi
    log_ok "PHP-FPM redemarre"
}

setup_supervisor() {
    log_step "Configuration Supervisor (Queue Workers)"

    if ! command -v supervisorctl &>/dev/null; then
        log_info "Installation de Supervisor..."
        $PKG_INSTALL supervisor 2>/dev/null || {
            log_warn "Supervisor non installe — les queues ne tourneront pas automatiquement."
            return
        }
    fi

    cat > /etc/supervisor/conf.d/hostclient.conf <<SUPERVISOR
[program:hostclient-queue]
process_name=%(program_name)s_%(process_num)02d
command=php ${INSTALL_DIR}/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=${INSTALL_DIR}/storage/logs/queue.log
stopwaitsecs=3600

[program:hostclient-scheduler]
process_name=%(program_name)s
command=/bin/bash -c "while true; do php ${INSTALL_DIR}/artisan schedule:run --no-interaction; sleep 60; done"
autostart=true
autorestart=true
user=www-data
redirect_stderr=true
stdout_logfile=${INSTALL_DIR}/storage/logs/scheduler.log
SUPERVISOR
    supervisorctl reread
    supervisorctl update
    supervisorctl start hostclient-queue:* 2>/dev/null || true
    supervisorctl start hostclient-scheduler 2>/dev/null || true

    log_ok "Supervisor configure (2 queue workers + scheduler)"
}

setup_cron() {
    if ! command -v supervisorctl &>/dev/null; then
        log_step "Configuration du Cron"
        local CRON_FILE="/etc/cron.d/hostclient"
        echo "* * * * * www-data php ${INSTALL_DIR}/artisan schedule:run >> /dev/null 2>&1" > "$CRON_FILE"
        chmod 644 "$CRON_FILE"
        log_ok "Cron configure dans $CRON_FILE"
    fi
}

print_success() {
    local DOMAIN
    DOMAIN=$(echo "$APP_URL" | sed 's|https\?://||' | sed 's|/.*||')

    echo ""
    echo -e "${GREEN}${BOLD}"
    echo "  ╔══════════════════════════════════════════════════════════╗"
    echo "  ║                                                          ║"
    echo "  ║   HostClient installe avec succes !                      ║"
    echo "  ║                                                          ║"
    echo "  ╚══════════════════════════════════════════════════════════╝"
    echo -e "${NC}"
    echo -e "  ${BOLD}Acces a votre application :${NC}"
    echo -e "  Site       : ${CYAN}${APP_URL}${NC}"
    echo -e "  Admin      : ${CYAN}${APP_URL}/admin/dashboard${NC}"
    echo -e "  Email      : ${CYAN}${ADMIN_EMAIL}${NC}"
    echo -e "  Mot de passe : ${YELLOW}(celui que vous avez saisi)${NC}"
    echo ""
    echo -e "  ${BOLD}Fichiers importants :${NC}"
    echo -e "  Installation  : ${CYAN}${INSTALL_DIR}${NC}"
    echo -e "  Configuration : ${CYAN}${INSTALL_DIR}/.env${NC}"
    echo -e "  Logs          : ${CYAN}${INSTALL_DIR}/storage/logs${NC}"
    echo -e "  Nginx         : ${CYAN}/etc/nginx/sites-available/hostclient${NC}"
    echo -e "  Supervisor    : ${CYAN}/etc/supervisor/conf.d/hostclient.conf${NC}"
    echo ""
    echo -e "  ${BOLD}Commandes utiles :${NC}"
    echo -e "  ${CYAN}php artisan cache:clear${NC}       # Vider le cache"
    echo -e "  ${CYAN}supervisorctl status${NC}          # Etat des workers"
    echo -e "  ${CYAN}systemctl status nginx${NC}        # Etat de Nginx"
    echo ""
    echo -e "  ${BOLD}Prochaines etapes :${NC}"
    echo -e "  1. Pointez votre DNS vers ce serveur"
    echo -e "  2. SSL : ${CYAN}certbot --nginx -d ${DOMAIN}${NC}"
    echo -e "  3. Configurez le SMTP : ${CYAN}${APP_URL}/admin/settings${NC}"
    echo ""
    echo -e "  ${YELLOW}Support : https://github.com/Nitrohebergeur/hostclient/issues${NC}"
    echo ""
}

# ── Point d'entree ─────────────────────────────────────────────────────────────
main() {
    clear
    print_banner
    check_root
    detect_os
    check_dependencies
    collect_config
    clone_repository
    setup_env
    setup_database
    install_php_deps
    install_node_deps
    run_migrations
    setup_storage
    optimize_app
    setup_nginx
    setup_supervisor
    setup_cron
    print_success
}

main "$@"
