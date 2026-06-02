#!/bin/bash

echo "[1/4] Iniciando MySQL..."
service mysql start

# Esperar hasta que MySQL responda (máx 30s)
for i in $(seq 1 30); do
    if mysqladmin ping -h 127.0.0.1 -u root --silent 2>/dev/null; then
        echo "    MySQL listo (${i}s)"
        break
    fi
    sleep 1
done

echo "[2/4] Importando esquema y datos de demo..."
mysql -h 127.0.0.1 -u root -e "CREATE DATABASE IF NOT EXISTS shoponline_huila CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -h 127.0.0.1 -u root shoponline_huila < /var/www/html/schema.sql
echo "    Hecho."

echo "[3/4] Configurando Apache..."
PORT=${PORT:-10000}
echo "Listen $PORT" > /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost *:$PORT>/" /etc/apache2/sites-enabled/000-default.conf
echo "ServerName localhost" >> /etc/apache2/apache2.conf

echo "[4/4] Iniciando Apache en puerto $PORT..."
exec apache2-foreground
