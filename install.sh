#!/usr/bin/env bash
# =============================================================================
#  HostClient — Installateur automatique
#  https://github.com/hostclient/hostclient
# =============================================================================
set -euo pipefail

# ── Couleurs ──────────────────────────────────────────────────────────────────
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
CYAN='\033[0;36m'
BOLD='\033[1m'
NC='\033[0m' # No Color

# ── Configuration par défaut ──────────────────────────────────────────────────
REPO="https://github.com/hostclient/hostclient.git"
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
    echo -e "  ${BOLD}Plateforme SaaS d'hébergement web — Installateur v1.0.0${NC}"
    echo -e "  ${BLUE}https://github.com/hostclient/hostclient${NC}"
    echo ""
    echo -e "  ${YELLOW}⚠️  Ce script doit être exécuté en tant que root ou avec sudo${NC}"
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
log_info() { echo -e "  ${CYAN}ℹ${NC}  $1"; }

ask() {
    local prompt="$1"
    local default="${2:-}"
    local answer
    if [ -n "$default" ]; then
        read -rp "  $(echo -e "${BOLD}${prompt}${NC}") [${default}]: " answer
        echo "${answer:-$default}"
    else
        read -rp "  $(echo -e "${BOLD}${prompt}${NC}"): " answer
        echo "$answer"
    fi
}

ask_secret() {
    local prompt="$1"
    local answer
    read -srp "  $(echo -e "${BOLD}${prompt}${NC}"): " answer
    echo ""
    echo "$answer"
}

confirm() {
    local prompt="${1:-Continuer ?}"
    local answer
    read -rp "  $(echo -e "${BOLD}${YELLOW}${prompt} [o/N]${NC} "): " answer
    [[ "$answer" =~ ^[oOyY]$ ]]
}

check_root() {
    if [ "$EUID" -ne 0 ]; then
        log_err "Ce script doit être exécuté en tant que root."
        echo -e "  Réessayez avec : ${CYAN}sudo bash install.sh${NC}"
        exit 1
    fi
}

detect_os() {
    if [ -f /etc/os-release ]; then
        . /etc/os-release
        OS=$ID
        OS_VERSION=$VERSION_ID
    else
        log_err "Système d'exploitation non reconnu."
        exit 1
    fi

    case "$OS" in
        ubuntu|debian)
            PKG_MANAGER="apt-get"
            PKG_UPDATE="apt-get update -qq"
            PKG_INSTALL="apt-get install -y -qq"
            ;;
        centos|rhel|rocky|almalinux)
            PKG_MANAGER="dnf"
            PKG_UPDATE="dnf check-update -q || true"
            PKG_INSTALL="dnf install -y -q"
            ;;
        fedora)
            PKG_MANAGER="dnf"
            PKG_UPDATE="dnf check-update -q || true"
            PKG_INSTALL="dnf install -y -q"
            ;;
        *)
            log_warn "Distribution non officiellement supportée : $OS"
            log_warn "Le script va continuer mais certaines étapes peuvent échouer."
            PKG_MANAGER="apt-get"
            PKG_UPDATE="apt-get update -qq"
            PKG_INSTALL="apt-get install -y -qq"
            ;;
    esac

    log_ok "Système détecté : ${BOLD}$OS $OS_VERSION${NC}"
}

version_ge() {
    # Retourne 0 si $1 >= $2
    printf '%s\n%s\n' "$2" "$1" | sort -V -C
}

check_dependencies() {
    log_step "Vérification des prérequis"

    local missing=()

    # Git
    if command -v git &>/dev/null; then
        log_ok "git $(git --version | awk '{print $3}')"
    else
        missing+=("git")
    fi

    # PHP
    if command -v php &>/dev/null; then
        PHP_VERSION=$(php -r "echo PHP_VERSION;")
        if version_ge "$PHP_VERSION" "$PHP_MIN"; then
            log_ok "PHP $PHP_VERSION"
        else
            log_err "PHP $PHP_VERSION détecté — PHP $PHP_MIN+ requis"
            missing+=("php")
        fi
    else
        missing+=("php")
    fi

    # Composer
    if command -v composer &>/dev/null; then
        log_ok "Composer $(composer --version --no-ansi 2>/dev/null | awk '{print $3}')"
    else
        missing+=("composer")
    fi

    # Node.js
    if command -v node &>/dev/null; then
        NODE_VERSION=$(node -v | sed 's/v//')
        MAJOR="${NODE_VERSION%%.*}"
        if [ "$MAJOR" -ge "$NODE_MIN" ]; then
            log_ok "Node.js v$NODE_VERSION"
        else
            log_err "Node.js v$NODE_VERSION détecté — Node $NODE_MIN+ requis"
            missing+=("nodejs")
        fi
    else
        missing+=("nodejs")
    fi

    # npm
    if command -v npm &>/dev/null; then
        log_ok "npm $(npm --version)"
    fi

    # MySQL / MariaDB
    if command -v mysql &>/dev/null; then
        log_ok "MySQL/MariaDB $(mysql --version | awk '{print $5}' | tr -d ',')"
    else
        log_warn "MySQL/MariaDB non trouvé — vous pourrez le configurer manuellement"
    fi

    # Redis
    if command -v redis-cli &>/dev/null; then
        log_ok "Redis $(redis-cli --version | awk '{print $2}')"
    else
        log_warn "Redis non trouvé — recommandé pour les performances"
    fi

    # curl / unzip
    for tool in curl unzip; do
        if command -v "$tool" &>/dev/null; then
            log_ok "$tool disponible"
        else
            missing+=("$tool")
        fi
    done

    if [ ${#missing[@]} -gt 0 ]; then
        echo ""
        log_warn "Dépendances manquantes : ${missing[*]}"
        if confirm "Installer automatiquement les dépendances manquantes ?"; then
            install_dependencies "${missing[@]}"
        else
            log_err "Installation annulée. Installez manuellement : ${missing[*]}"
            exit 1
        fi
    fi
}

install_dependencies() {
    log_step "Installation des dépendances système"

    local deps=("$@")

    $PKG_UPDATE

    for dep in "${deps[@]}"; do
        case "$dep" in
            php)
                install_php
                ;;
            composer)
                install_composer
                ;;
            nodejs)
                install_nodejs
                ;;
            git|curl|unzip|zip)
                $PKG_INSTALL "$dep"
                log_ok "$dep installé"
                ;;
        esac
    done
}

install_php() {
    log_info "Installation de PHP 8.4…"

    case "$OS" in
        ubuntu|debian)
            $PKG_INSTALL software-properties-common
            add-apt-repository ppa:ondrej/php -y
            $PKG_UPDATE
            $PKG_INSTALL \
                php8.4-fpm \
                php8.4-cli \
                php8.4-mysql \
                php8.4-pgsql \
                php8.4-mbstring \
                php8.4-xml \
                php8.4-curl \
                php8.4-zip \
                php8.4-gd \
                php8.4-intl \
                php8.4-bcmath \
                php8.4-redis \
                php8.4-opcache \
                php8.4-soap
            ;;
        centos|rhel|rocky|almalinux)
            $PKG_INSTALL epel-release
            $PKG_INSTALL https://rpms.remirepo.net/enterprise/remi-release-$(rpm -E %rhel).rpm
            dnf module enable php:remi-8.4 -y
            $PKG_INSTALL php php-fpm php-mysqlnd php-mbstring php-xml php-curl php-zip php-gd php-intl php-bcmath php-redis php-opcache
            ;;
    esac

    log_ok "PHP 8.4 installé"
}

install_composer() {
    log_info "Installation de Composer…"
    curl -sS https://getcomposer.org/installer | php
    mv composer.phar /usr/local/bin/composer
    chmod +x /usr/local/bin/composer
    log_ok "Composer installé"
}

install_nodejs() {
    log_info "Installation de Node.js 20…"
    curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
    $PKG_INSTALL nodejs
    log_ok "Node.js 20 installé"
}

collect_config() {
    log_step "Configuration de l'installation"

    echo -e "  ${CYAN}Remplissez les informations suivantes (Entrée = valeur par défaut)${NC}\n"

    # Répertoire
    INSTALL_DIR=$(ask "Répertoire d'installation" "/var/www/hostclient")

    # Branche / Tag
    BRANCH=$(ask "Branche ou version à installer" "main")

    echo ""
    echo -e "  ${BOLD}── Base de données ────────────────────────────────${NC}"
    DB_HOST=$(ask     "Hôte MySQL"              "127.0.0.1")
    DB_PORT=$(ask     "Port MySQL"              "3306")
    DB_NAME=$(ask     "Nom de la base"          "hostclient")
    DB_USER=$(ask     "Utilisateur MySQL"       "hostclient")
    DB_PASS=$(ask_secret "Mot de passe MySQL")
    DB_ROOT_PASS=$(ask_secret "Mot de passe root MySQL (pour créer la BDD)")

    echo ""
    echo -e "  ${BOLD}── Application ────────────────────────────────────${NC}"
    APP_URL=$(ask     "URL de l'application"    "http://localhost")
    APP_ENV=$(ask     "Environnement"           "production")

    echo ""
    echo -e "  ${BOLD}── Administrateur ─────────────────────────────────${NC}"
    ADMIN_NAME=$(ask      "Nom de l'administrateur"  "Administrateur")
    ADMIN_EMAIL=$(ask     "Email administrateur"     "admin@example.com")
    ADMIN_PASS=$(ask_secret "Mot de passe administrateur")

    echo ""
    echo -e "  ${BOLD}── Redis (optionnel) ───────────────────────────────${NC}"
    REDIS_HOST=$(ask  "Hôte Redis"              "127.0.0.1")
    REDIS_PORT=$(ask  "Port Redis"              "6379")
    REDIS_PASS=$(ask_secret "Mot de passe Redis (vide = aucun)")

    echo ""
    echo -e "  ${BOLD}── Email (SMTP) ────────────────────────────────────${NC}"
    MAIL_HOST=$(ask   "Hôte SMTP"               "smtp.mailtrap.io")
    MAIL_PORT=$(ask   "Port SMTP"               "587")
    MAIL_USER=$(ask   "Utilisateur SMTP"        "")
    MAIL_PASS=$(ask_secret "Mot de passe SMTP")
    MAIL_FROM=$(ask   "Email expéditeur"        "noreply@${APP_URL#*://}")
    MAIL_NAME=$(ask   "Nom expéditeur"          "HostClient")

    # Résumé
    echo ""
    echo -e "  ${BOLD}${YELLOW}── Résumé de la configuration ──────────────────────${NC}"
    echo -e "  Répertoire   : ${CYAN}$INSTALL_DIR${NC}"
    echo -e "  URL          : ${CYAN}$APP_URL${NC}"
    echo -e "  Environnement: ${CYAN}$APP_ENV${NC}"
    echo -e "  Base de données: ${CYAN}$DB_HOST:$DB_PORT/$DB_NAME${NC} (user: $DB_USER)"
    echo -e "  Redis        : ${CYAN}$REDIS_HOST:$REDIS_PORT${NC}"
    echo -e "  Admin        : ${CYAN}$ADMIN_EMAIL${NC}"
    echo ""

    if ! confirm "Confirmer et lancer l'installation ?"; then
        echo -e "  ${YELLOW}Installation annulée.${NC}"
        exit 0
    fi
}

clone_repository() {
    log_step "Téléchargement de HostClient depuis GitHub"

    if [ -d "$INSTALL_DIR" ]; then
        if confirm "Le répertoire $INSTALL_DIR existe déjà. Écraser ?"; then
            rm -rf "$INSTALL_DIR"
        else
            log_err "Installation annulée."
            exit 1
        fi
    fi

    log_info "Clonage depuis $REPO (branche: $BRANCH)…"
    git clone --branch "$BRANCH" --depth 1 "$REPO" "$INSTALL_DIR"
    log_ok "Code source téléchargé dans $INSTALL_DIR"
}

setup_env() {
    log_step "Configuration du fichier .env"

    cd "$INSTALL_DIR"

    cp .env.example .env

    # Remplacements dans .env
    sed_env() {
        sed -i "s|^${1}=.*|${1}=${2}|g" .env
    }

    sed_env "APP_NAME"       "HostClient"
    sed_env "APP_ENV"        "$APP_ENV"
    sed_env "APP_DEBUG"      "$([ "$APP_ENV" = 'production' ] && echo 'false' || echo 'true')"
    sed_env "APP_URL"        "$APP_URL"

    sed_env "DB_CONNECTION"  "mysql"
    sed_env "DB_HOST"        "$DB_HOST"
    sed_env "DB_PORT"        "$DB_PORT"
    sed_env "DB_DATABASE"    "$DB_NAME"
    sed_env "DB_USERNAME"    "$DB_USER"
    sed_env "DB_PASSWORD"    "$DB_PASS"

    sed_env "REDIS_HOST"     "$REDIS_HOST"
    sed_env "REDIS_PORT"     "$REDIS_PORT"
    sed_env "REDIS_PASSWORD" "${REDIS_PASS:-null}"

    sed_env "CACHE_DRIVER"   "redis"
    sed_env "SESSION_DRIVER" "redis"
    sed_env "QUEUE_CONNECTION" "redis"

    sed_env "MAIL_HOST"      "$MAIL_HOST"
    sed_env "MAIL_PORT"      "$MAIL_PORT"
    sed_env "MAIL_USERNAME"  "$MAIL_USER"
    sed_env "MAIL_PASSWORD"  "$MAIL_PASS"
    sed_env "MAIL_FROM_ADDRESS" "$MAIL_FROM"
    sed_env "MAIL_FROM_NAME" "$MAIL_NAME"

    log_ok "Fichier .env configuré"
}

setup_database() {
    log_step "Création de la base de données MySQL"

    if command -v mysql &>/dev/null; then
        log_info "Création de la base de données '$DB_NAME' et de l'utilisateur '$DB_USER'…"

        mysql -u root -p"$DB_ROOT_PASS" <<SQL 2>/dev/null || {
            log_warn "Impossible de créer la BDD automatiquement — faites-le manuellement."
            return
        }
CREATE DATABASE IF NOT EXISTS \`${DB_NAME}\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '${DB_USER}'@'${DB_HOST}' IDENTIFIED BY '${DB_PASS}';
GRANT ALL PRIVILEGES ON \`${DB_NAME}\`.* TO '${DB_USER}'@'${DB_HOST}';
FLUSH PRIVILEGES;
SQL
        log_ok "Base de données '$DB_NAME' créée avec succès"
    else
        log_warn "MySQL non trouvé localement — assurez-vous que la base de données existe."
    fi
}

install_php_deps() {
    log_step "Installation des dépendances PHP (Composer)"

    cd "$INSTALL_DIR"

    if [ "$APP_ENV" = "production" ]; then
        composer install --no-dev --optimize-autoloader --no-interaction --quiet
    else
        composer install --optimize-autoloader --no-interaction --quiet
    fi

    log_ok "Dépendances PHP installées"
}

install_node_deps() {
    log_step "Installation des dépendances Node.js et compilation des assets"

    cd "$INSTALL_DIR"

    npm install --silent
    npm run build

    log_ok "Assets compilés avec succès"
}

run_migrations() {
    log_step "Migrations et données initiales"

    cd "$INSTALL_DIR"

    # Générer la clé d'application
    php artisan key:generate --no-interaction --force
    log_ok "Clé d'application générée"

    # Migrations
    php artisan migrate --force --no-interaction
    log_ok "Migrations exécutées"

    # Seeder
    php artisan db:seed --force --no-interaction
    log_ok "Données initiales insérées"

    # Créer l'admin avec les infos saisies
    php artisan tinker --no-interaction <<PHP 2>/dev/null || true
\$user = \App\Models\User::updateOrCreate(
    ['email' => '${ADMIN_EMAIL}'],
    [
        'name'     => '${ADMIN_NAME}',
        'password' => \Illuminate\Support\Facades\Hash::make('${ADMIN_PASS}'),
        'status'   => 'active',
        'email_verified_at' => now(),
    ]
);
\$user->assignRole('admin');
exit;
PHP
    log_ok "Compte administrateur créé"
}

setup_storage() {
    log_step "Configuration du stockage et des permissions"

    cd "$INSTALL_DIR"

    # Storage link
    php artisan storage:link --no-interaction 2>/dev/null || true

    # Permissions
    chown -R www-data:www-data "$INSTALL_DIR" 2>/dev/null || \
    chown -R nginx:nginx "$INSTALL_DIR" 2>/dev/null || \
    log_warn "Impossible de changer le propriétaire — ajustez manuellement."

    chmod -R 755 "$INSTALL_DIR"
    chmod -R 775 "$INSTALL_DIR/storage"
    chmod -R 775 "$INSTALL_DIR/bootstrap/cache"

    log_ok "Permissions configurées"
}

optimize_app() {
    log_step "Optimisation de l'application"

    cd "$INSTALL_DIR"

    if [ "$APP_ENV" = "production" ]; then
        php artisan config:cache   --no-interaction
        php artisan route:cache    --no-interaction
        php artisan view:cache     --no-interaction
        php artisan event:cache    --no-interaction
        log_ok "Cache production activé"
    fi

    php artisan queue:restart --no-interaction 2>/dev/null || true
    log_ok "Application optimisée"
}

setup_nginx() {
    log_step "Configuration Nginx (optionnelle)"

    if ! command -v nginx &>/dev/null; then
        log_info "Nginx non trouvé — configuration ignorée."
        return
    fi

    local DOMAIN
    DOMAIN=$(echo "$APP_URL" | sed 's|https\?://||' | sed 's|/.*||')

    local NGINX_CONF="/etc/nginx/sites-available/hostclient"

    cat > "$NGINX_CONF" <<NGINX
server {
    listen 80;
    listen [::]:80;
    server_name ${DOMAIN};
    root ${INSTALL_DIR}/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";

    index index.php;
    charset utf-8;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.4-fpm.sock;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_read_timeout 300;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Gzip
    gzip on;
    gzip_types text/plain text/css application/json application/javascript text/xml application/xml application/xml+rss text/javascript;
    gzip_vary on;
}
NGINX

    if [ -d /etc/nginx/sites-enabled ]; then
        ln -sf "$NGINX_CONF" /etc/nginx/sites-enabled/hostclient
        # Désactiver le site par défaut si présent
        rm -f /etc/nginx/sites-enabled/default 2>/dev/null || true
    fi

    nginx -t && systemctl reload nginx
    log_ok "Nginx configuré pour ${DOMAIN}"
}

setup_supervisor() {
    log_step "Configuration Supervisor (Queue Workers)"

    if ! command -v supervisorctl &>/dev/null; then
        log_warn "Supervisor non trouvé — installation…"
        $PKG_INSTALL supervisor 2>/dev/null || {
            log_warn "Supervisor non installé — les queues ne tourneront pas automatiquement."
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

    log_ok "Supervisor configuré (2 queue workers + scheduler)"
}

setup_cron() {
    log_step "Configuration du Cron (fallback si pas de Supervisor)"

    if ! command -v supervisorctl &>/dev/null; then
        local CRON_LINE="* * * * * www-data php ${INSTALL_DIR}/artisan schedule:run >> /dev/null 2>&1"
        local CRON_FILE="/etc/cron.d/hostclient"

        echo "$CRON_LINE" > "$CRON_FILE"
        chmod 644 "$CRON_FILE"
        log_ok "Cron configuré dans $CRON_FILE"
    fi
}

print_success() {
    echo ""
    echo -e "${GREEN}${BOLD}"
    echo "  ╔══════════════════════════════════════════════════════════╗"
    echo "  ║                                                          ║"
    echo "  ║   🎉  HostClient installé avec succès !                  ║"
    echo "  ║                                                          ║"
    echo "  ╚══════════════════════════════════════════════════════════╝"
    echo -e "${NC}"
    echo -e "  ${BOLD}Accès à votre application :${NC}"
    echo -e "  🌐 Site       : ${CYAN}${APP_URL}${NC}"
    echo -e "  🔧 Admin      : ${CYAN}${APP_URL}/admin/dashboard${NC}"
    echo -e "  👤 Email      : ${CYAN}${ADMIN_EMAIL}${NC}"
    echo -e "  🔑 Mot de passe : ${YELLOW}(celui que vous avez saisi)${NC}"
    echo ""
    echo -e "  ${BOLD}Fichiers importants :${NC}"
    echo -e "  📁 Installation   : ${CYAN}${INSTALL_DIR}${NC}"
    echo -e "  📄 Configuration  : ${CYAN}${INSTALL_DIR}/.env${NC}"
    echo -e "  📝 Logs           : ${CYAN}${INSTALL_DIR}/storage/logs${NC}"
    echo -e "  🌐 Nginx config   : ${CYAN}/etc/nginx/sites-available/hostclient${NC}"
    echo ""
    echo -e "  ${BOLD}Commandes utiles :${NC}"
    echo -e "  ${CYAN}cd ${INSTALL_DIR}${NC}"
    echo -e "  ${CYAN}php artisan queue:work${NC}          # Démarrer les workers"
    echo -e "  ${CYAN}php artisan schedule:run${NC}        # Lancer les tâches planifiées"
    echo -e "  ${CYAN}php artisan horizon${NC}             # Monitorer les queues (Laravel Horizon)"
    echo -e "  ${CYAN}php artisan cache:clear${NC}         # Vider le cache"
    echo ""
    echo -e "  ${YELLOW}Documentation : https://docs.hostclient.io${NC}"
    echo -e "  ${YELLOW}Support       : https://github.com/hostclient/hostclient/issues${NC}"
    echo ""
}

# ── Point d'entrée ─────────────────────────────────────────────────────────────
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
