#!/usr/bin/env bash
set -euo pipefail

PROJECT_DIR="/home/odilon/Téléchargements/mobile-hub"
SITE_DIR="/var/www/mobile-hub"
SITE_CONF="/etc/apache2/sites-available/mobile-hub.conf"

rm -rf "$SITE_DIR"
mkdir -p "$SITE_DIR"
cp -a "$PROJECT_DIR"/. "$SITE_DIR"/
rm -rf "$SITE_DIR/.git" "$SITE_DIR/.agents" "$SITE_DIR/.codex"

chown -R root:root "$SITE_DIR"
chown -R www-data:www-data "$SITE_DIR/data"
find "$SITE_DIR" -type d -exec chmod 755 {} \;
find "$SITE_DIR" -type f -exec chmod 644 {} \;
find "$SITE_DIR/data" -type d -exec chmod 775 {} \;
find "$SITE_DIR/data" -type f -exec chmod 664 {} \;

cat > "$SITE_CONF" <<'APACHE'
<VirtualHost *:80>
    ServerName mobile-hub.local
    ServerAlias localhost
    DocumentRoot /var/www/mobile-hub

    <Directory /var/www/mobile-hub>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/mobile-hub-error.log
    CustomLog ${APACHE_LOG_DIR}/mobile-hub-access.log combined
</VirtualHost>
APACHE

if ! grep -qE '^[[:space:]]*127\.0\.0\.1[[:space:]].*\bmobile-hub\.local\b' /etc/hosts; then
    printf '\n127.0.0.1 mobile-hub.local\n' >> /etc/hosts
fi

a2dissite 000-default.conf >/dev/null || true
a2ensite mobile-hub.conf >/dev/null
apache2ctl configtest
systemctl reload apache2 || systemctl start apache2
