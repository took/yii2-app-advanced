#!/bin/bash
# Docker entrypoint script for Yii2 stage container
set -e

echo "==> Starting Yii2 stage initialization..."

# Wait for MySQL to be ready
echo "==> Waiting for MySQL to be ready..."
until mysql -h mysql-yii2advanced-stage -u yii2advanced-stage -psecret --skip-ssl -e "SELECT 1" &> /dev/null
do
    echo "    MySQL not ready yet, waiting..."
    sleep 2
done
echo "    MySQL is ready!"

sleep 2

# Initialize Yii2 application
cd /app
echo "==> Apply migrations..."
./yii migrate/fresh --interactive=0
./yii_test migrate/fresh --interactive=0

echo "==> Create default BackofficeUser..."
./yii create-default-backoffice-user

echo "==> Initialize example tables with some random data..."
./yii add-example-data

echo "==> Initialization complete! Starting Apache..."
echo ""
echo " Access your apps at:"
echo "   * API        - http://localhost:30083/"
echo "   * Backoffice - http://localhost:30081/ (User admin/password: admin123)"
echo "   * Frontpage  - http://localhost:30080/"
echo ""
echo " MySQL is available at:"
echo "   * Host       - localhost"
echo "   * Port       - 33306"
echo "   * Username   - yii2advanced-stage"
echo "   * Password   - secret"
echo "   * Database   - yii2advanced_stage"
echo ""

# Start Apache in foreground
exec apache2-foreground
