FROM php:8.2-apache

WORKDIR /var/www/html

RUN docker-php-ext-install pdo pdo_mysql \
    && a2enmod rewrite headers \
    && echo "ServerName localhost" >> /etc/apache2/apache2.conf

COPY . /var/www/html

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf \
    && printf '<Directory /var/www/html/public>\nAllowOverride All\nRequire all granted\n</Directory>\n' \
    > /etc/apache2/conf-available/evartalap.conf \
    && a2enconf evartalap \
    && mkdir -p /var/www/html/public/uploads/photos \
    && chown -R www-data:www-data /var/www/html/public/uploads \
    && chmod +x /var/www/html/docker-entrypoint-render.sh

EXPOSE 80

CMD ["/var/www/html/docker-entrypoint-render.sh"]
