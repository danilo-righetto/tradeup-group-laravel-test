#!/bin/bash

chown www-data:www-data bootstrap/cache && chown www-data:www-data /var/www/app/storage -R
echo "Configurando dependências\n"
php dependencies.php
"$@"
