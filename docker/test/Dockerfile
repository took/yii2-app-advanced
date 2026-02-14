FROM yiisoftware/yii2-php:8.2-apache

# Change document root for Apache TODO Ersetzte mit ganz neuer apache config die backoffice, frontpage und api configuriert
RUN sed -i -e 's|/app/web|/app/backend/web|g' /etc/apache2/sites-available/000-default.conf

# Enable Apache mod_rewrite for Yii2 URL routing
RUN a2enmod rewrite

# Configure Apache to allow .htaccess overrides
RUN sed -i '/<Directory \/app\/backend\/web>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/sites-available/000-default.conf \
    || echo '<Directory /app/backend/web>\n    Options Indexes FollowSymLinks\n    AllowOverride All\n    Require all granted\n</Directory>' >> /etc/apache2/sites-available/000-default.conf

# Install additional PHP extensions and tools (including mysql client for health checks)
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    default-mysql-client \
    && docker-php-ext-install pdo_mysql \
    && pecl install pcov \
    && docker-php-ext-enable pcov \
    && rm -rf /var/lib/apt/lists/* # TODO Keep this line only in prod and tests

# Set working directory
WORKDIR /app

# Install Codeception globally
RUN composer global require "codeception/codeception" --prefer-dist --no-interaction

# Add composer global bin to PATH
ENV PATH="${PATH}:/root/.composer/vendor/bin"

# Copy entrypoint script (relative to build context which is ./backend/)
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Set entrypoint
ENTRYPOINT ["docker-entrypoint.sh"]
