FROM yiisoftware/yii2-php:8.5-apache-latest

# Update MOTD
RUN echo "Yii2 Advanced App - Tests" >> /etc/motd

# Prevent detection of dubious ownership at /app
RUN git config --global --add safe.directory /app

# Install additional PHP extensions and tools (including mysql client for health checks)
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    default-mysql-client \
    && docker-php-ext-install pdo_mysql \
    && pecl install pcov \
    && docker-php-ext-enable pcov \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Set working directory
WORKDIR /app

# Copy application source code
COPY . /app

# Clean up
RUN make clean-all

# Initialize Yii2 application
RUN ./init --env=Stage --overwrite=All

# Install composer dependencies
RUN rm -f composer.lock
RUN rm -rf vendor
RUN composer install --prefer-dist --no-interaction --no-progress --optimize-autoloader --ansi

# Copy entrypoint script
COPY docker/tests/docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Set entrypoint
ENTRYPOINT ["docker-entrypoint.sh"]
