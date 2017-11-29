#! /bin/bash
set -e

if [ "$1" = "" ]; then
  php /var/www/happy2/websocket-server/server.php &
  exec httpd -DFOREGROUND
fi

exec "$@"

