# Guide d'installation HostClient

Ce guide vous accompagne dans l'installation complète de HostClient sur votre serveur.

## Prérequis

### Système requis

- **Serveur Web** : Apache 2.4+ ou Nginx 1.18+
- **PHP** : 8.4 ou supérieur
- **Base de données** : MariaDB 10.6+ ou MySQL 8.0+
- **Node.js** : 20.x ou supérieur
- **Composer** : 2.x
- **Extensions PHP** : BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, PDO_MySQL, Tokenizer, XML, cURL, GD, ZIP

### Vérification de PHP

```bash
php -v
php -m  # Liste des extensions installées
```

## Installation pas à pas

### 1. Installation de PHP 8.4

#### Windows

1. Téléchargez PHP 8.4 depuis [windows.php.net](https://windows.php.net/download/)
2. Choisissez la version **Thread Safe** si vous utilisez Apache, **Non Thread Safe** pour Nginx/IIS
3. Extrayez l'archive dans `C:\php84`
4. Ajoutez `C:\php84` au PATH système :
   - Panneau de configuration → Système → Paramètres système avancés
   - Variables d'environnement → Path → Modifier → Nouveau → `C:\php84`
5. Copiez `php.ini-development` vers `php.ini`
6. Éditez `php.ini` et activez les extensions :

```ini
extension=curl
extension=fileinfo
extension=gd
extension=mbstring
extension=openssl
extension=pdo_mysql
extension=zip
extension=bcmath

; Augmentez les limites
upload_max_filesize = 50M
post_max_size = 50M
max_execution_time = 300
memory_limit = 256M
```

#### Linux (Ubuntu/Debian)

```bash
sudo add-apt-repository ppa:ondrej/php
sudo apt update
sudo apt install php8.4 php8.4-cli php8.4-fpm php8.4-mysql php8.4-xml php8.4-mbstring php8.4-curl php8.4-gd php8.4-zip php8.4-bcmath
```

### 2. Installation de Composer

#### Windows

Téléchargez et installez depuis [getcomposer.org](https://getcomposer.org/download/)

#### Linux

```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer
```

### 3. Installation de MariaDB/MySQL

#### Windows

Téléchargez et installez [XAMPP](https://www.apachefriends.org/) ou [MariaDB standalone](https://mariadb.org/download/)

#### Linux

```bash
sudo apt install mariadb-server
sudo mysql_secure_installation
```

### 4. Installation de Node.js

Téléchargez depuis [nodejs.org](https://nodejs.org/) (version LTS recommandée)

Vérifiez l'installation :

```bash
node -v
npm -v
```

### 5. Installation de HostClient

#### Téléchargement

```bash
# Cloner depuis Git
git clone https://github.com/votre-username/hostclient.git
cd hostclient

# Ou télécharger et extraire l'archive ZIP
```

#### Installation des dépendances

```bash
# Dépendances PHP
composer install --no-dev --optimize-autoloader

# Dépendances JavaScript
npm install
```

#### Configuration

```bash
# Copier le fichier d'environnement
copy .env.example .env  # Windows
# ou
cp .env.example .env    # Linux

# Générer la clé d'application
php artisan key:generate
```

#### Configuration de la base de données

Éditez le fichier `.env` :

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hostclient
DB_USERNAME=root
DB_PASSWORD=votre_mot_de_passe
```

Créez la base de données :

```sql
CREATE DATABASE hostclient CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

#### Migration de la base de données

```bash
php artisan migrate --seed
```

#### Compilation des assets

```bash
# Développement
npm run dev

# Production
npm run build
```

#### Permissions (Linux uniquement)

```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### 6. Configuration du serveur Web

#### Apache

Créez un VirtualHost dans `httpd-vhosts.conf` :

```apache
<VirtualHost *:80>
    ServerName hostclient.local
    DocumentRoot "C:/path/to/hostclient/public"
    
    <Directory "C:/path/to/hostclient/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

#### Nginx

```nginx
server {
    listen 80;
    server_name hostclient.local;
    root /path/to/hostclient/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### 7. Assistant d'installation

Lancez l'assistant d'installation :

```bash
php artisan hostclient:install
```

L'assistant vous guidera à travers :
- Vérification des prérequis
- Configuration de la base de données
- Création du compte administrateur
- Configuration initiale
- Installation des modules de base

### 8. Accès à l'application

**Développement** :

```bash
php artisan serve
```

Accédez à http://localhost:8000

**Production** : Configurez votre serveur web pour pointer vers le dossier `public/`

## Installation avec Docker

Pour une installation simplifiée avec Docker :

```bash
# Copier .env
copy .env.example .env

# Éditer .env et configurer :
DB_HOST=db
REDIS_HOST=redis

# Démarrer les containers
docker-compose up -d

# Installer les dépendances
docker-compose exec app composer install
docker-compose exec app npm install && npm run build

# Migration
docker-compose exec app php artisan migrate --seed

# Créer un administrateur
docker-compose exec app php artisan hostclient:create-admin
```

Accédez à http://localhost:8000

## Post-installation

### Planificateur de tâches (Cron)

Ajoutez cette ligne au crontab :

```bash
* * * * * cd /path/to/hostclient && php artisan schedule:run >> /dev/null 2>&1
```

### Queue Worker

Pour traiter les tâches en arrière-plan :

```bash
php artisan queue:work --daemon
```

Ou configurez un superviseur (recommandé en production).

### Configuration du mail

Éditez `.env` :

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=votre_username
MAIL_PASSWORD=votre_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@votre-domaine.com
MAIL_FROM_NAME="${APP_NAME}"
```

### Sauvegardes automatiques

Configurez les sauvegardes dans `config/backup.php` puis :

```bash
php artisan backup:run
```

## Dépannage

### Erreur 500

Vérifiez les logs :
```bash
tail -f storage/logs/laravel.log
```

### Erreur de permissions

```bash
# Linux
sudo chown -R www-data:www-data storage bootstrap/cache

# Windows : Assurez-vous que l'utilisateur a les droits d'écriture
```

### Base de données inaccessible

Vérifiez les credentials dans `.env` et que MySQL/MariaDB est démarré.

### Extensions PHP manquantes

```bash
php -m | grep extension_name
```

## Support

- Documentation : [docs/](.)
- Issues : GitHub Issues
- Discord : [Rejoindre](https://discord.gg/votre-serveur)

## Prochaines étapes

1. [Configuration](configuration.md) - Configurez votre instance
2. [Modules](modules.md) - Installez des modules
3. [Thèmes](themes.md) - Personnalisez l'apparence
4. [API](api.md) - Intégrez avec l'API
