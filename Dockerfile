# 1️⃣ Image PHP avec FPM et extensions nécessaires
FROM php:8.2-fpm

# 2️⃣ Installer les dépendances système et PHP
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    libonig-dev \
    curl \
    npm \
    && docker-php-ext-install pdo pdo_pgsql mbstring zip bcmath opcache

# 3️⃣ Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 4️⃣ Créer le répertoire du projet
WORKDIR /var/www/html

# 5️⃣ Copier les fichiers du projet
COPY . .

# 6️⃣ Installer les dépendances PHP et Node.js
RUN composer install --no-dev --optimize-autoloader
RUN npm install
RUN npm run prod

# 7️⃣ Permissions pour Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 8️⃣ Cache Laravel
RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache

# 9️⃣ Exposer le port de l’application
EXPOSE 8000

# 10️⃣ Commande pour démarrer Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
