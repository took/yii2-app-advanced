FROM yiisoftware/yii2-php:8.5-apache-latest

# Update MOTD
RUN echo "Yii2 Advanced App - Development" >> /etc/motd

# Prevent detection of dubious ownership at /app
RUN git config --global --add safe.directory /app

# Enable Apache mod_rewrite for Yii2 URL routing
RUN a2enmod rewrite

# Configure Apache
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf
RUN echo "Listen 8081" >> /etc/apache2/ports.conf
RUN echo "Listen 8082" >> /etc/apache2/ports.conf

# Install additional PHP extensions and tools (including mysql client for health checks)
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    default-mysql-client \
    && docker-php-ext-install pdo_mysql \
    && pecl install pcov \
    && docker-php-ext-enable pcov

# Set working directory
WORKDIR /app

# Do not copy application source code for Docker dev setup - local code will be mounted into the container instead
#COPY . /app

# Copy entrypoint script
COPY docker/dev/docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Set entrypoint
ENTRYPOINT ["docker-entrypoint.sh"]
