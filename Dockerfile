FROM php:8.2-apache
RUN docker-php-ext-install mysqli
COPY . /var/www/html/
COPY start.sh /start.sh
RUN chmod +x /start.sh
CMD ["/start.sh"]
