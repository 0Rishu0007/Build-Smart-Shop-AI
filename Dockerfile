# Stage 1: Build frontend assets using Node.js
FROM node:20-alpine AS frontend-builder
WORKDIR /app

# Copy dependency definitions and lockfile
COPY package*.json ./
RUN npm ci

# Copy codebase and compile assets via Vite
COPY . .
RUN npm run build

# Stage 2: Compile PHP dependencies & run web server
FROM richarvey/nginx-php-fpm:latest

# Set working directory
WORKDIR /var/www/html

# Copy all application files
COPY . .

# Copy compiled frontend assets from Stage 1
COPY --from=frontend-builder /app/public/build ./public/build

# Install PHP dependencies at build time for faster deployment startup
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Configure NGINX web root to point to Laravel's public directory
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1

# Ensure storage and bootstrap folders are writeable
RUN chown -R nginx:nginx /var/www/html/storage /var/www/html/bootstrap/cache

# Disable automatic composer install on startup since we already did it
ENV SKIP_COMPOSER 1
