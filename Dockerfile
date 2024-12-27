# Utiliser l'image PHP 8.1-FPM comme base
FROM php:8.1-fpm

# Installer les dépendances nécessaires
RUN apt-get update && apt-get install -y \
    curl \
    git \
    unzip \
    libmariadb-dev-compat \
    libmariadb-dev \
    locales \
    && docker-php-ext-install pdo pdo_mysql \
    && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Générer les locales
RUN echo "en_US.UTF-8 UTF-8" > /etc/locale.gen && locale-gen

# Installer Symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash && \
    mv ~/.symfony*/bin/symfony /usr/local/bin/symfony && \
    chmod +x /usr/local/bin/symfony

# Installer Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    composer --version
