FROM php:8.2-apache
RUN apt-get update && apt-get install -y libsqlite3-dev && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install pdo_sqlite
COPY . /var/www/html/
COPY start.sh /start.sh
RUN chmod +x /start.sh
CMD ["/start.sh"]
