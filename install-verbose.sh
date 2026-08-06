#!/bin/bash

# Script d'installation VERBOSE avec tous les logs
set -e  # Arrêt en cas d'erreur

echo "=========================================="
echo "  INSTALLATION HOSTCLIENT - MODE VERBOSE"
echo "=========================================="
echo ""

# Variables
DB_NAME="hostclient"
DB_USER="hostclient_user"
DB_PASSWORD=$(openssl rand -base64 20 | tr -d "=+/" | cut -c1-20)
ADMIN_EMAIL="$1"
ADMIN_PASSWORD="$2"
ADMIN_NAME="$3"
APP_URL="$4"

if [ -z "$ADMIN_EMAIL" ]; then
    read -p "Email admin: " ADMIN_EMAIL
fi
if [ -z "$ADMIN_PASSWORD" ]; then
    read -sp "Mot de passe admin: " ADMIN_PASSWORD
    echo ""
fi
if [ -z "$ADMIN_NAME" ]; then
    read -p "Nom admin: " ADMIN_NAME
fi
if [ -z "$APP_URL" ]; then
    read -p "URL (ex: https://panel.example.com): " APP_URL
fi

echo ""
read -sp "Mot de passe ROOT MySQL: " MYSQL_ROOT_PASSWORD
echo ""

echo ""
echo "=== Configuration MySQL ==="
echo "Vérification connexion MySQL..."
if mysql -u root -p"${MYSQL_ROOT_PASSWORD}" -e "SELECT 1" 2>&1; then
    echo "✓ MySQL répond"
    
    echo "Création base de données..."
    mysql -u root -p"${MYSQL_ROOT_PASSWORD}" <<EOF
CREATE DATABASE IF NOT EXISTS ${DB_NAME} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASSWORD}';
GRANT ALL PRIVILEGES ON ${DB_NAME}.* TO '${DB_USER}'@'localhost';
FLUSH PRIVILEGES;
SELECT 'Base de données créée' AS Status;
EOF
    echo "✓ Base de données configurée"
else
    echo "✗ ERREUR: MySQL - mot de passe root incorrect"
    exit 1
fi

echo ""
echo "=== Clonage du repository ==="
cd ~
if [ -d "hostclient" ]; then
    echo "Suppression ancien dossier..."
    rm -rf hostclient
fi

echo "Clonage depuis GitHub..."
git clone https://github.com/Nitrohebergeur/hostclient.git 2>&1
cd hostclient
echo "✓ Repository cloné dans: $(pwd)"

echo ""
echo "=== Installation Composer ==="
echo "Lancement de composer install..."
COMPOSER_ALLOW_SUPERUSER=1 composer install --no-dev --optimize-autoloader --no-interaction 2>&1 | tee /tmp/composer.log
echo "✓ Composer terminé (voir /tmp/composer.log pour détails)"

echo ""
echo "=== Configuration .env ==="
if [ ! -f ".env" ]; then
    cp .env.example .env
    echo "✓ .env créé"
else
    echo "✓ .env existe déjà"
fi

echo ""
echo "=== Configuration des variables ==="
sed -i "s|APP_NAME=.*|APP_NAME=\"Nitrohebergeur\"|" .env
sed -i "s|APP_URL=.*|APP_URL=${APP_URL}|" .env
sed -i "s|APP_ENV=.*|APP_ENV=production|" .env
sed -i "s|APP_DEBUG=.*|APP_DEBUG=false|" .env
sed -i "s|DB_DATABASE=.*|DB_DATABASE=${DB_NAME}|" .env
sed -i "s|DB_USERNAME=.*|DB_USERNAME=${DB_USER}|" .env
sed -i "s|DB_PASSWORD=.*|DB_PASSWORD=${DB_PASSWORD}|" .env
echo "✓ Variables configurées"

echo ""
echo "=== Génération clé application ==="
php artisan key:generate --force 2>&1
echo "✓ Clé générée"

echo ""
echo "=== Migrations base de données ==="
php artisan migrate --force 2>&1 | tee /tmp/migrate.log
echo "✓ Migrations terminées (voir /tmp/migrate.log)"

echo ""
echo "=== Création compte admin ==="
php artisan tinker <<EOF 2>&1 | tee /tmp/admin.log
\$user = App\Models\User::where('email', '${ADMIN_EMAIL}')->first();
if (!\$user) {
    \$user = new App\Models\User();
    \$user->name = '${ADMIN_NAME}';
    \$user->email = '${ADMIN_EMAIL}';
    \$user->password = bcrypt('${ADMIN_PASSWORD}');
    \$user->email_verified_at = now();
    \$user->save();
    echo "Admin créé: ${ADMIN_EMAIL}\n";
} else {
    echo "Admin existe déjà\n";
}
exit
EOF
echo "✓ Admin configuré"

echo ""
echo "=== Lien de stockage ==="
php artisan storage:link 2>&1
echo "✓ Lien créé"

echo ""
echo "=== Permissions ==="
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || chown -R nginx:nginx storage bootstrap/cache 2>/dev/null || echo "⚠ Impossible de changer le propriétaire (non bloquant)"
chmod -R 775 storage bootstrap/cache
echo "✓ Permissions définies"

echo ""
echo "=== Installation NPM ==="
npm install 2>&1 | tee /tmp/npm.log
echo "✓ NPM installé (voir /tmp/npm.log)"

echo ""
echo "=== Build assets ==="
npm run build 2>&1 | tee /tmp/build.log
echo "✓ Assets compilés (voir /tmp/build.log)"

echo ""
echo "=== Configuration Nginx ==="
NGINX_CONF="/etc/nginx/sites-available/hostclient"
DOMAIN=$(echo $APP_URL | sed 's/https\?:\/\///')

cat > $NGINX_CONF <<EOF
server {
    listen 80;
    server_name ${DOMAIN};
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
nginx -t 2>&1
systemctl restart nginx
echo "✓ Nginx configuré"

echo ""
echo "=== Configuration Cron ==="
(crontab -l 2>/dev/null | grep -v "hostclient"; echo "* * * * * cd $(pwd) && php artisan schedule:run >> /dev/null 2>&1") | crontab -
echo "✓ Cron configuré"

echo ""
echo "=== Sauvegarde identifiants ==="
cat > $(pwd)/CREDENTIALS.txt <<EOF
╔════════════════════════════════════════╗
║     INFORMATIONS D'INSTALLATION        ║
╚════════════════════════════════════════╝

URL: ${APP_URL}
Email admin: ${ADMIN_EMAIL}
Mot de passe admin: ${ADMIN_PASSWORD}

Base de données: ${DB_NAME}
User DB: ${DB_USER}
Password DB: ${DB_PASSWORD}

Emplacement: $(pwd)

⚠ Supprimez ce fichier après avoir noté les infos!
EOF
chmod 600 $(pwd)/CREDENTIALS.txt
echo "✓ Identifiants sauvegardés dans: $(pwd)/CREDENTIALS.txt"

echo ""
echo "=========================================="
echo "  ✓ INSTALLATION TERMINÉE!"
echo "=========================================="
echo ""
echo "Accédez à: ${APP_URL}"
echo "Connectez-vous avec: ${ADMIN_EMAIL}"
echo ""
echo "Logs disponibles dans:"
echo "  - /tmp/composer.log"
echo "  - /tmp/migrate.log"
echo "  - /tmp/admin.log"
echo "  - /tmp/npm.log"
echo "  - /tmp/build.log"
echo ""
