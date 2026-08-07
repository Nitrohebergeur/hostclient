#!/usr/bin/env bash
# =============================================================================
#  HostClient — Désinstallateur
# =============================================================================
set -euo pipefail

RED='\033[0;31m'; GREEN='\033[0;32m'; YELLOW='\033[1;33m'
BOLD='\033[1m'; NC='\033[0m'

INSTALL_DIR="${1:-/var/www/hostclient}"

echo -e "${RED}${BOLD}HostClient — Désinstallation${NC}\n"
echo -e "  ${YELLOW}⚠️  Cette opération va supprimer TOUT : fichiers, base de données, configuration Nginx/Supervisor.${NC}\n"

read -rp "  Tapez 'SUPPRIMER' pour confirmer : " confirm
[ "$confirm" != "SUPPRIMER" ] && echo "  Annulé." && exit 0

# Arrêter les workers
supervisorctl stop hostclient-queue:* 2>/dev/null || true
supervisorctl stop hostclient-scheduler 2>/dev/null || true
rm -f /etc/supervisor/conf.d/hostclient.conf 2>/dev/null || true
supervisorctl reread 2>/dev/null || true

# Nginx
rm -f /etc/nginx/sites-enabled/hostclient  2>/dev/null || true
rm -f /etc/nginx/sites-available/hostclient 2>/dev/null || true
nginx -t && systemctl reload nginx 2>/dev/null || true

# Cron
rm -f /etc/cron.d/hostclient 2>/dev/null || true

# Supprimer les fichiers
if [ -d "$INSTALL_DIR" ]; then
    # Lire les infos DB depuis .env avant suppression
    if [ -f "$INSTALL_DIR/.env" ]; then
        DB_NAME=$(grep ^DB_DATABASE "$INSTALL_DIR/.env" | cut -d= -f2)
        DB_USER=$(grep ^DB_USERNAME "$INSTALL_DIR/.env" | cut -d= -f2)
        read -rp "  Supprimer la base de données '$DB_NAME' ? [o/N] : " del_db
        if [[ "$del_db" =~ ^[oOyY]$ ]]; then
            read -srp "  Mot de passe root MySQL : " root_pass
            echo ""
            mysql -u root -p"$root_pass" -e "DROP DATABASE IF EXISTS \`${DB_NAME}\`; DROP USER IF EXISTS '${DB_USER}'@'%';" 2>/dev/null && \
                echo -e "  ${GREEN}✓${NC}  Base de données supprimée" || \
                echo -e "  ${YELLOW}⚠${NC}  Suppression BDD manuelle requise"
        fi
    fi
    rm -rf "$INSTALL_DIR"
    echo -e "  ${GREEN}✓${NC}  Fichiers supprimés"
fi

echo -e "\n${GREEN}${BOLD}✅ HostClient a été désinstallé.${NC}\n"
