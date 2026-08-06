#!/bin/bash

# HostClient Complete Auto-Installation Script
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
DB_ROOT_PASSWORD=""
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
    echo "║       HostClient Auto-Installer v2.0                   ║"
    echo "║       Installation Automatique Complète                ║"
    echo "║       https://github.com/Nitrohebergeur                ║"
    echo "╚════════════════════════════════════════════════════════╝"
    echo -e "${NC}"
}

command_exists() {
    command -v "$1" >/dev/null 2>&1
}

generate_password() {
    openssl rand -base64 32 | tr -d "=+/" | cut -c1-25
}

# Détecter l'OS
detect_os() {
    if [ -f /etc/os-release ]; then
        . /etc/os-release
        OS=$ID
        OS_VERSION=$VERSION_ID
    else
        print_error "Système d'exploitation non supporté"
        exit 1
    fi
    print_info "Système: $OS $OS_VERSION"
}

# Collecter les informations utilisateur
collect_user_info() {
    print_step "Configuration initiale"
    echo ""
    
    # Nom de l'entreprise
    read -p "$(echo -e ${CYAN}Nom de votre entreprise:${NC} )" COMPANY_NAME
    COMPANY_NAME=${COMPANY_NAME:-"Mon Entreprise"}
    
    # URL de l'application
    read -p "$(echo -e ${CYAN}URL de l\'application \(ex: https://panel.example.com\):${NC} )" APP_URL
    APP_URL=${APP_URL:-"http://localhost"}
    
    echo ""
    print_step "Informations du compte administrateur"
    
    # Nom admin
    read -p "$(echo -e ${CYAN}Nom complet de l\'administrateur:${NC} )" ADMIN_NAME
    ADMIN_NAME=${ADMIN_NAME:-"Admin"}
    
    # Email admin
    while [[ ! "$ADMIN_EMAIL" =~ ^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$ ]]; do
        read -p "$(echo -e ${CYAN}Email administrateur:${NC} )" ADMIN_EMAIL
        if [[ ! "$ADMIN_EMAIL" =~ ^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$ ]]; then
            print_error "Email invalide, réessayez"
        fi
    done
    
    # Mot de passe admin
    while [ -z "$ADMIN_PASSWORD" ] || [ ${#ADMIN_PASSWORD} -lt 8 ]; do
        read -sp "$(echo -e ${CYAN}Mot de passe admin \(min 8 caractères\):${NC} )" ADMIN_PASSWORD
        echo ""
        if [ ${#ADMIN_PASSWORD} -lt 8 ]; then
            print_error "Le mot de passe doit contenir au moins 8 caractères"
        fi
    done
    
    # Générer les mots de passe DB
    DB_PASSWORD=$(generate_password)
    DB_ROOT_PASSWORD=$(generate_password)
    
    echo ""
    print_success "Configuration collectée avec succès"
}

# Installer les dépendances système
install_dependencies() {
    print_step "Installation des dépendances système..."
    
    export DEBIAN_FRONTEND=noninteractive
    
    # Update
    print_info "Mise à jour des paquets..."
    apt-get update -qq || {
        print_error "Échec de apt-get update"
        exit 1
    }
    
    # Installer les outils de base
    print_info "Installation des outils de base..."
    apt-get install -y software-properties-common curl wget git unzip ca-certificates apt-transport-https lsb-release gnupg2 || {
        print_error "Échec de l'installation des outils de base"
        exit 1
    }
    print_success "Outils de base installés"
    
    # Ajouter le repo PHP pour Debian
    print_info "Ajout du repository PHP..."
    if [ "$OS" = "debian" ]; then
        wget -q https://packages.sury.org/php/apt.gpg -O- | apt-key add - || {
            print_warning "Impossible d'ajouter la clé GPG avec apt-key, essai avec keyrings..."
            curl -sSLo /usr/share/keyrings/deb.sury.org-php.gpg https://packages.sury.org/php/apt.gpg
            echo "deb [signed-by=/usr/share/keyrings/deb.sury.org-php.gpg] https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list
        }
        apt-get update -qq || {
            print_error "Échec de la mise à jour après ajout du repo PHP"
            exit 1
        }
    else
        add-apt-repository -y ppa:ondrej/php
        apt-get update -qq
    fi
    print_success "Repository PHP ajouté"
    
    # Installer PHP 8.2
    print_info "Installation de PHP 8.2 et extensions..."
    apt-get install -y php8.2 php8.2-cli php8.2-fpm php8.2-mysql php8.2-xml php8.2-mbstring \
        php8.2-curl php8.2-zip php8.2-gd php8.2-bcmath php8.2-intl \
        php8.2-soap php8.2-gmp || {
        print_error "Échec de l'installation de PHP"
        exit 1
    }
    print_success "PHP 8.2 installé"
    
    # Installer Composer
    if ! command_exists composer; then
        print_info "Installation de Composer..."
        curl -sS https://getcomposer.org/installer -o composer-setup.php || {
            print_error "Échec du téléchargement de Composer"
            exit 1
        }
        php composer-setup.php --quiet --install-dir=/usr/local/bin --filename=composer || {
            print_error "Échec de l'installation de Composer"
            exit 1
        }
        rm composer-setup.php
        print_success "Composer installé"
    else
        print_success "Composer déjà installé"
    fi
    
    # Installer Node.js
    if ! command_exists node; then
        print_info "Installation de Node.js 20..."
        curl -fsSL https://deb.nodesource.com/setup_20.x | bash - || {
            print_error "Échec de l'ajout du repo Node.js"
            exit 1
        }
        apt-get install -y nodejs || {
            print_error "Échec de l'installation de Node.js"
            exit 1
        }
        print_success "Node.js installé"
    else
        print_success "Node.js déjà installé"
    fi
    
    print_success "Toutes les dépendances sont installées"
}

# Installer et configurer MySQL
install_mysql() {
    print_step "Installation et configuration de MySQL..."
    
    if command_exists mysql; then
        print_success "MySQL déjà installé"
    else
        # Préconfigurer MySQL
        echo "mysql-server mysql-server/root_password password $DB_ROOT_PASSWORD" | debconf-set-selections
        echo "mysql-server mysql-server/root_password_again password $DB_ROOT_PASSWORD" | debconf-set-selections
        
        # Installer MySQL
        print_info "Installation de MySQL..."
        apt-get install -y default-mysql-server 2>&1 | grep -v "^Selecting\|^Preparing\|^Unpacking\|^Setting up\|^Processing"
        print_success "MySQL installé"
    fi
    
    # Démarrer MySQL
    systemctl start mysql 2>/dev/null || systemctl start mariadb 2>/dev/null
    systemctl enable mysql > /dev/null 2>&1 || systemctl enable mariadb > /dev/null 2>&1
    
    # Attendre que MySQL soit prêt
    sleep 3
    
    # Créer la base de données et l'utilisateur
    print_info "Configuration de la base de données..."
    
    # Essayer avec root sans mot de passe d'abord (Debian par défaut)
    if mysql -u root -e "SELECT 1" > /dev/null 2>&1; then
        mysql -u root <<-EOSQL 2>/dev/null
            ALTER USER 'root'@'localhost' IDENTIFIED BY '${DB_ROOT_PASSWORD}';
            CREATE DATABASE IF NOT EXISTS ${DB_NAME} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
            CREATE USER IF NOT EXISTS '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASSWORD}';
            GRANT ALL PRIVILEGES ON ${DB_NAME}.* TO '${DB_USER}'@'localhost';
            FLUSH PRIVILEGES;
EOSQL
    else
        mysql -u root -p"$DB_ROOT_PASSWORD" <<-EOSQL 2>/dev/null
            CREATE DATABASE IF NOT EXISTS ${DB_NAME} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
            CREATE USER IF NOT EXISTS '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASSWORD}';
            GRANT ALL PRIVILEGES ON ${DB_NAME}.* TO '${DB_USER}'@'localhost';
            FLUSH PRIVILEGES;
EOSQL
    fi
    
    print_success "Base de données configurée"
}

# Cloner le repository
clone_repository() {
    print_step "Téléchargement de HostClient..."
    
    if [ -d "hostclient" ]; then
        print_warning "Le dossier hostclient existe déjà"
        rm -rf hostclient
    fi
    
    git clone -q https://github.com/Nitrohebergeur/hostclient.git
    cd hostclient
    print_success "Repository cloné"
}

# Installer les dépendances de l'application
install_app_dependencies() {
    print_step "Installation des dépendances de l'application..."
    
    # Composer
    print_info "Installation des packages PHP..."
    COMPOSER_ALLOW_SUPERUSER=1 composer install --no-dev --optimize-autoloader --no-interaction -q
    print_success "Packages PHP installés"
    
    # NPM
    if command_exists npm; then
        print_info "Installation des packages JavaScript..."
        npm install --silent > /dev/null 2>&1
        print_success "Packages JavaScript installés"
        
        print_info "Compilation des assets..."
        npm run build > /dev/null 2>&1
        print_success "Assets compilés"
    fi
}

# Configurer l'environnement
setup_environment() {
    print_step "Configuration de l'environnement..."
    
    # Copier .env
    cp .env.example .env
    
    # Générer la clé
    php artisan key:generate --force > /dev/null 2>&1
    
    # Configuration du .env
    sed -i "s|APP_NAME=.*|APP_NAME=\"$COMPANY_NAME\"|" .env
    sed -i "s|APP_URL=.*|APP_URL=$APP_URL|" .env
    sed -i "s|APP_ENV=.*|APP_ENV=production|" .env
    sed -i "s|APP_DEBUG=.*|APP_DEBUG=false|" .env
    
    sed -i "s|DB_CONNECTION=.*|DB_CONNECTION=mysql|" .env
    sed -i "s|DB_HOST=.*|DB_HOST=127.0.0.1|" .env
    sed -i "s|DB_PORT=.*|DB_PORT=3306|" .env
    sed -i "s|DB_DATABASE=.*|DB_DATABASE=$DB_NAME|" .env
    sed -i "s|DB_USERNAME=.*|DB_USERNAME=$DB_USER|" .env
    sed -i "s|DB_PASSWORD=.*|DB_PASSWORD=$DB_PASSWORD|" .env
    
    print_success "Environnement configuré"
}

# Configurer la base de données
setup_database() {
    print_step "Configuration de la base de données..."
    
    # Migrations
    print_info "Exécution des migrations..."
    php artisan migrate --force > /dev/null 2>&1
    print_success "Migrations exécutées"
    
    # Créer l'utilisateur admin
    print_info "Création du compte administrateur..."
    php artisan tinker <<EOF > /dev/null 2>&1
\$user = new App\Models\User();
\$user->name = '$ADMIN_NAME';
\$user->email = '$ADMIN_EMAIL';
\$user->password = bcrypt('$ADMIN_PASSWORD');
\$user->email_verified_at = now();
\$user->save();
\$user->assignRole('admin');
echo "Admin created\n";
EOF
    print_success "Compte administrateur créé"
}

# Configurer les permissions
set_permissions() {
    print_step "Configuration des permissions..."
    
    # Créer le lien de stockage
    php artisan storage:link > /dev/null 2>&1
    
    # Permissions
    chown -R www-data:www-data storage bootstrap/cache
    chmod -R 775 storage bootstrap/cache
    
    print_success "Permissions configurées"
}

# Installer Nginx
install_nginx() {
    print_step "Installation de Nginx..."
    
    if command_exists nginx; then
        print_success "Nginx déjà installé"
    else
        apt-get install -y nginx 2>&1 | grep -v "^Selecting\|^Preparing\|^Unpacking\|^Setting up\|^Processing"
        print_success "Nginx installé"
    fi
    
    # Configuration Nginx
    local DOMAIN=$(echo $APP_URL | sed 's/https\?:\/\///')
    local NGINX_CONF="/etc/nginx/sites-available/hostclient"
    
    cat > $NGINX_CONF <<EOF
server {
    listen 80;
    server_name $DOMAIN;
    root $(pwd)/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

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
EOF
    
    ln -sf $NGINX_CONF /etc/nginx/sites-enabled/hostclient
    rm -f /etc/nginx/sites-enabled/default
    
    nginx -t > /dev/null 2>&1
    systemctl restart nginx
    systemctl enable nginx > /dev/null 2>&1
    
    print_success "Nginx configuré"
}

# Configurer le cron
setup_cron() {
    print_step "Configuration des tâches planifiées..."
    
    (crontab -l 2>/dev/null | grep -v "hostclient"; echo "* * * * * cd $(pwd) && php artisan schedule:run >> /dev/null 2>&1") | crontab -
    
    print_success "Tâches planifiées configurées"
}

# Sauvegarder les informations
save_credentials() {
    local CREDS_FILE="$(pwd)/CREDENTIALS.txt"
    
    cat > $CREDS_FILE <<EOF
╔════════════════════════════════════════════════════════╗
║           INFORMATIONS D'INSTALLATION                  ║
╚════════════════════════════════════════════════════════╝

📅 Date d'installation: $(date)

🌐 APPLICATION
   URL: $APP_URL
   Entreprise: $COMPANY_NAME

👤 COMPTE ADMINISTRATEUR
   Email: $ADMIN_EMAIL
   Mot de passe: $ADMIN_PASSWORD

🗄️  BASE DE DONNÉES
   Nom: $DB_NAME
   Utilisateur: $DB_USER
   Mot de passe: $DB_PASSWORD
   
🔐 MYSQL ROOT
   Mot de passe: $DB_ROOT_PASSWORD

⚠️  IMPORTANT: Conservez ce fichier en lieu sûr et supprimez-le ensuite!
   Pour supprimer: rm $(pwd)/CREDENTIALS.txt

EOF
    
    chmod 600 $CREDS_FILE
    print_success "Identifiants sauvegardés dans CREDENTIALS.txt"
}

# Afficher le résumé final
show_final_summary() {
    echo ""
    echo -e "${GREEN}╔════════════════════════════════════════════════════════╗${NC}"
    echo -e "${GREEN}║          Installation terminée avec succès! 🎉         ║${NC}"
    echo -e "${GREEN}╚════════════════════════════════════════════════════════╝${NC}"
    echo ""
    echo -e "${CYAN}📋 Résumé:${NC}"
    echo -e "   ${BLUE}•${NC} URL: ${YELLOW}$APP_URL${NC}"
    echo -e "   ${BLUE}•${NC} Admin: ${YELLOW}$ADMIN_EMAIL${NC}"
    echo -e "   ${BLUE}•${NC} Mot de passe: ${YELLOW}$ADMIN_PASSWORD${NC}"
    echo ""
    echo -e "${CYAN}📁 Emplacement:${NC} $(pwd)"
    echo -e "${CYAN}📄 Identifiants:${NC} $(pwd)/CREDENTIALS.txt"
    echo ""
    echo -e "${YELLOW}⚠️  Prochaines étapes:${NC}"
    echo -e "   ${BLUE}1.${NC} Configurez votre DNS pour pointer vers ce serveur"
    echo -e "   ${BLUE}2.${NC} Installez un certificat SSL (recommandé avec certbot)"
    echo -e "   ${BLUE}3.${NC} Accédez à ${YELLOW}$APP_URL${NC} et connectez-vous"
    echo -e "   ${BLUE}4.${NC} Supprimez le fichier CREDENTIALS.txt après avoir noté les informations"
    echo ""
    echo -e "${GREEN}✨ Votre panel est prêt à l'emploi!${NC}"
    echo ""
}

# Main installation
main() {
    print_header
    
    # Vérifier si root
    if [[ $EUID -ne 0 ]]; then
        print_error "Ce script doit être exécuté en tant que root"
        echo "Utilisez: sudo bash install.sh"
        exit 1
    fi
    
    detect_os
    echo ""
    
    collect_user_info
    echo ""
    
    print_step "Début de l'installation automatique..."
    echo ""
    
    install_dependencies
    install_mysql
    clone_repository
    install_app_dependencies
    setup_environment
    setup_database
    set_permissions
    install_nginx
    setup_cron
    save_credentials
    
    show_final_summary
}

# Exécuter l'installation
main
