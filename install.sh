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
        if confirm "Installer automatiquement les dependances manquantes ?"; then
            install_dependencies "${missing[@]}"
        else
            log_err "Installation annulee."
            exit 1
        fi
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
    DB_HOST=$(ask     "Hote MySQL"              "127.0.0.1")
    DB_PORT=$(ask     "Port MySQL"              "3306")
    DB_NAME=$(ask     "Nom de la base"          "hostclient")
    DB_USER=$(ask     "Utilisateur MySQL"       "hostclient")
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
    echo -e "  ── Email (SMTP) ──"
    MAIL_HOST=$(ask   "Hote SMTP"          "smtp.mailtrap.io")
    MAIL_PORT=$(ask   "Port SMTP"          "587")
    MAIL_USER=$(ask   "Utilisateur SMTP"   "")
    MAIL_PASS=$(ask_secret "Mot de passe SMTP")
    MAIL_FROM=$(ask   "Email expediteur"   "noreply@example.com")
    MAIL_NAME=$(ask   "Nom expediteur"     "HostClient")

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
        if confirm "Le repertoire $INSTALL_DIR existe deja. Ecraser ?"; then
            rm -rf "$INSTALL_DIR"
        else
            log_err "Installation annulee."
            exit 1
        fi
    fi

    log_info "Clonage depuis $REPO (branche: $BRANCH)..."
    git clone --branch "$BRANCH" --depth 1 "$REPO" "$INSTALL_DIR"
    log_ok "Code source telecharge dans $INSTALL_DIR"
}

setup_env() {
    log_step "Configuration du fichier .env"
    cd "$INSTALL_DIR"
    cp .env.example .env

    sed_env() { sed -i "s|^${1}=.*|${1}=${2}|g" .env; }

    sed_env "APP_NAME"          "HostClient"
    sed_env "APP_ENV"           "$APP_ENV"
    sed_env "APP_DEBUG"         "$([ "$APP_ENV" = 'production' ] && echo 'false' || echo 'true')"
    sed_env "APP_URL"           "$APP_URL"
    sed_env "DB_CONNECTION"     "mysql"
    sed_env "DB_HOST"           "$DB_HOST"
    sed_env "DB_PORT"           "$DB_PORT"
    sed_env "DB_DATABASE"       "$DB_NAME"
    sed_env "DB_USERNAME"       "$DB_USER"
    sed_env "DB_PASSWORD"       "$DB_PASS"
    sed_env "REDIS_HOST"        "$REDIS_HOST"
    sed_env "REDIS_PORT"        "$REDIS_PORT"
    sed_env "REDIS_PASSWORD"    "${REDIS_PASS:-null}"
    sed_env "CACHE_DRIVER"      "redis"
    sed_env "SESSION_DRIVER"    "redis"
    sed_env "QUEUE_CONNECTION"  "redis"
    sed_env "MAIL_HOST"         "$MAIL_HOST"
    sed_env "MAIL_PORT"         "$MAIL_PORT"
    sed_env "MAIL_USERNAME"     "$MAIL_USER"
    sed_env "MAIL_PASSWORD"     "$MAIL_PASS"
    sed_env "MAIL_FROM_ADDRESS" "$MAIL_FROM"
    sed_env "MAIL_FROM_NAME"    "$MAIL_NAME"

    log_ok "Fichier .env configure"
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
