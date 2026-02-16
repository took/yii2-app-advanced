#!/bin/bash
# Docker entrypoint script for Yii2 dev container
set -e

echo "==> Starting Yii2 dev initialization..."

# Wait for MySQL to be ready
echo "==> Waiting for MySQL to be ready..."
until mysql -h mysql-yii2advanced-dev -u yii2advanced-dev -psecret --skip-ssl -e "SELECT 1" &> /dev/null
do
    echo "    MySQL not ready yet, waiting..."
    sleep 2
done
echo "    MySQL is ready!"

sleep 2

# Create database for tests if it does not exist
echo "==> Create database 'yii2advanced_dev_test' if it does not exist..."
mysql -h mysql-yii2advanced-dev -u root -pverysecret --skip-ssl -e "CREATE DATABASE IF NOT EXISTS yii2advanced_dev_test;"

# Set proper permissions for runtime directories
echo "==> Setting permissions for runtime directories..."
chmod -R 777 /app/api/runtime /app/api/web/assets 2>/dev/null || true
chmod -R 777 /app/backoffice/runtime /app/backoffice/web/assets 2>/dev/null || true
chmod -R 777 /app/console/runtime 2>/dev/null || true
chmod -R 777 /app/frontpage/runtime /app/frontpage/web/assets 2>/dev/null || true
echo "    Permissions set!"

echo "==> Initialization complete! Starting Apache..."
echo ""
echo " -> To run composer:"
echo "       docker exec -it <container_name> composer update"
echo ""
echo " -> To initialize your apps, run:"
echo "       docker exec -it <container_name> /app/init --env=Development"
echo ""
echo " -> To run migrations:"
echo "       docker exec -it <container_name> php /app/yii migrate --interactive=0"
echo "       docker exec -it <container_name> php /app/yii_test migrate --interactive=0"
echo ""
echo " -> To create an admin user, run:"
echo "       docker exec -it <container_name> php /app/yii create-default-backoffice-user"
echo ""
echo " Access your apps at:"
echo "   * API        - http://localhost:20082/"
echo "   * Backoffice - http://localhost:20081/"
echo "   * Frontpage  - http://localhost:20080/"
echo "   * Frontpage  - http://localhost:20080/"
echo ""
echo " MySQL is available at:"
echo "   * Host       - localhost"
echo "   * Port       - 23306"
echo "   * Username   - yii2advanced-dev"
echo "   * Password   - secret"
echo "   * Database   - yii2advanced_dev"
echo ""

# Start Apache in foreground
exec apache2-foreground
