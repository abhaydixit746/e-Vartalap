#!/bin/sh
set -e

if [ "${AUTO_INIT_DB:-true}" = "true" ]; then
  php /var/www/html/scripts/init-db.php
fi

exec apache2-foreground
