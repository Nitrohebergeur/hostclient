#!/usr/bin/env bash
# =============================================================================
#  HostClient — Script de mise à jour
#  https://github.com/Nitrohebergeur/hostclient
# =============================================================================
set -euo pipefail

RED='\033[0;31m'; GREEN='\033[0;32m'; YELLOW='\033[1;33m'
BLUE='\033[0;34m'; CYAN='\033[0;36m'; BOLD='\033[1m'; NC='\033[0m'

INSTALL_DIR="${1:-/var/www/hostclient}"

log_step() { echo -e "\n${BOLD}${BLUE}▶  $1${NC}\n"; }
log_ok()   { echo -e "  ${GREEN}✓${NC}  $1"; }
log_warn() { echo -e "  ${YELLOW}⚠${NC}  $1"; }
log_err()  { echo -e "  ${RED}✗${NC}  $1"; exit 1; }
log_info() { echo -e "  ${CYAN}ℹ${NC}  $1"; }

echo -e "${CYAN}${BOLD}HostClient — Mise à jour${NC}\n"

[ ! -d "$INSTALL_DIR" ] && log_err "Répertoire $INSTALL_DIR introuvable."
cd "$INSTALL_DIR"

# Sauvegarder .env
log_step "Sauvegarde de la configuration"
cp .env .env.backup.$(date +%Y%m%d%H%M%S)
log_ok ".env sauvegardé"

# Mode maintenance ON
log_step "Activation du mode maintenance"
php artisan down --render="errors.503" --retry=60
log_ok "Mode maintenance activé"

# Pull
log_step "Téléchargement de la dernière version"
git fetch origin
git pull origin main
log_ok "Code source mis à jour"

# Composer
log_step "Mise à jour des dépendances PHP"
composer install --no-dev --optimize-autoloader --no-interaction --quiet
log_ok "Dépendances PHP mises à jour"

# npm + build
log_step "Recompilation des assets"
npm install --silent
npm run build
log_ok "Assets compilés"

# Migrations
log_step "Migrations de base de données"
php artisan migrate --force --no-interaction
log_ok "Migrations exécutées"

# Cache
log_step "Nettoyage et recréation du cache"
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
log_ok "Cache production reconstruit"

# Queues
php artisan queue:restart --no-interaction
supervisorctl restart hostclient-queue:* 2>/dev/null || true
log_ok "Workers redémarrés"

# Mode maintenance OFF
log_step "Désactivation du mode maintenance"
php artisan up
log_ok "Application en ligne"

echo -e "\n${GREEN}${BOLD}✅ Mise à jour terminée avec succès !${NC}\n"
