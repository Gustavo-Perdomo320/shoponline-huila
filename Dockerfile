FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    default-mysql-server \
    && docker-php-ext-install mysqli \
    && rm -rf /var/lib/apt/lists/*

COPY . /var/www/html/
COPY start.sh /start.sh
RUN chmod +x /start.sh

CMD ["/start.sh"]
