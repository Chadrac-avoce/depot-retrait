# Utiliser l’image officielle PHP avec Apache
FROM php:8.2-apache

# Installer les extensions nécessaires à Laravel
RUN apt-get update && apt-get install -y \
    git zip unzip libpng-dev libjpeg-dev libfreetype6-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Activer mod_rewrite pour Apache
RUN a2enmod rewrite

# Copier les fichiers du projet dans le conteneur
COPY . /var/www/html

# Définir le répertoire de travail
WORKDIR /var/www/html

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Installer les dépendances Laravel
RUN composer install --no-dev --optimize-autoloader

# Donner les bons droits aux dossiers de cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Générer la clé Laravel
RUN php artisan key:generate

# Exposer le port 80 pour Apache
EXPOSE 80

# Lancer Apache
CMD ["apache2-foreground"]
