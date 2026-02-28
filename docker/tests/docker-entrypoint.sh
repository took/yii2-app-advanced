#!/bin/bash
# Docker entrypoint script for Yii2 stage container
set -e

echo "==> Starting Yii2 tests initialization..."

# Wait for MySQL to be ready
echo "==> Waiting for MySQL to be ready..."
until mysql -h mysql-yii2advanced-tests -u yii2advanced-stage -psecret --skip-ssl -e "SELECT 1" &> /dev/null
do
    echo "    MySQL not ready yet, waiting..."
    sleep 2
done
echo "    MySQL is ready!"

sleep 2

# Create database for tests if it does not exist
echo "==> Create database 'yii2advanced_stage_test' if it does not exist..."
mysql -h mysql-yii2advanced-tests -u root -pverysecret --skip-ssl -e "CREATE DATABASE IF NOT EXISTS yii2advanced_stage_test;"

# Initialize Yii2 application
cd /app
echo "==> Apply migrations..."
./yii_test migrate/fresh --interactive=0

echo "==> Initialization complete! Running tests..."

# Run all Codeception tests inside the container
echo "==> Running Codeception tests..."
./vendor/bin/codecept run --no-interaction

echo "==> Tests complete."
