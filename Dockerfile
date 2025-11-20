FROM php:8.2-apache

# Installation des extensions PHP utiles
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Active le module Apache rewrite
RUN a2enmod rewrite

# Correction Apache : autoriser l'accès
RUN printf "<Directory /var/www/html/>\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>\n" > /etc/apache2/conf-available/access.conf \
    && a2enconf access

# Copier le FRONT dans le dossier public web
COPY ./front /var/www/html/

# Copier le BACK (API) à la racine web
COPY ./back /var/www/html/back/

EXPOSE 80
