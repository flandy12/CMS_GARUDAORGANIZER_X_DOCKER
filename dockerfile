FROM php:8.3.36-apache

# 1. Install library sistem dan ekstensi PHP

RUN apt-get update && apt-get install -y \
    curl \
    unzip \
    zip \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libicu-dev \
    libxml2-dev \
    libonig-dev \
    libssl-dev \
    libcurl4-openssl-dev \
    pkg-config \
    $PHPIZE_DEPS \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        pdo_mysql \
        mbstring \
        bcmath \
        exif \
        pcntl \
        gd \
        zip \
        intl \
        opcache \
    && curl -fsSL https://github.com/php/pie/releases/latest/download/pie.phar \
       -o /usr/local/bin/pie \
    && chmod +x /usr/local/bin/pie \
    && pie install mongodb/mongodb-extension:^1.21.5 \
    && docker-php-ext-enable mongodb \
    && php --ri mongodb \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# 2. Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 3. Aktifkan mod_rewrite
RUN a2enmod rewrite

# 4. Arahkan Apache ke public Laravel
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri \
    -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/000-default.conf \
    && sed -ri \
    -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/apache2.conf \
    && sed -ri \
    '/<Directory \$\{APACHE_DOCUMENT_ROOT\}>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' \
    /etc/apache2/apache2.conf

# 5. Folder kerja Laravel
WORKDIR /var/www/html

# 6. Salin source code Laravel
COPY . /var/www/html

# 7. Install dependency Laravel
RUN composer install \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts

# 8. Atur permission folder Laravel
RUN chown -R www-data:www-data \
        /var/www/html/storage \
        /var/www/html/bootstrap/cache \
    && chmod -R ug+rwX \
        /var/www/html/storage \
        /var/www/html/bootstrap/cache

# 9. Port Apache
EXPOSE 80

CMD ["apache2-foreground"]

