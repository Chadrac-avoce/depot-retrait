# Utilise l'image officielle PHP avec Apache
FROM php:8.2-apache

# Installe les extensions nécessaires à Laravel
RUN apt-get update && apt-get install -y \
    git unzip libpng-dev libonig-dev libxml2-dev zip curl && \
    docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Active le module Apache mod_rewrite
RUN a2enmod rewrite

# Copie le code source dans le conteneur
COPY . /var/www/html

# Définit le répertoire de travail
WORKDIR /var/www/html

# Installe Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Installe les dépendances Laravel
RUN composer install --no-dev --optimize-autoloader

# Définit les permissions nécessaires
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Définit la variable d'environnement pour Apache
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

# Change la configuration d’Apache
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf

# Expose le port 80
EXPOSE 80

# Commande de démarrage
CMD ["apache2-foreground"]
