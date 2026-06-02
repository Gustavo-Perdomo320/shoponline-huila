#!/bin/bash
set -e

# Iniciar MySQL en segundo plano
mysqld_safe --skip-grant-tables &
sleep 6

# Crear base de datos e importar esquema con datos de demo
mysql -u root <<EOF
CREATE DATABASE IF NOT EXISTS shoponline_huila CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE shoponline_huila;
SOURCE /var/www/html/schema.sql;
EOF

echo "Base de datos inicializada correctamente."

# Configurar Apache en el puerto que asigna Render
PORT=${PORT:-10000}
echo "Listen $PORT" > /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost *:$PORT>/" /etc/apache2/sites-enabled/000-default.conf
echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Iniciar Apache en primer plano
apache2-foreground
