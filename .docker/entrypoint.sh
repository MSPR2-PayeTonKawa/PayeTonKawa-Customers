#!/bin/bash

# Créer les répertoires nécessaires
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/framework/cache
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/bootstrap/cache

# Définir les permissions correctes
chown -R www-data:www-data /var/www/html
chmod -R 777 /var/www/html/storage
chmod -R 777 /var/www/html/bootstrap/cache

# Générer la clé d'application si nécessaire
php artisan key:generate --no-interaction --force

# Démarrer Apache en premier plan
exec apache2-foreground
