#!/bin/bash
# Docker entrypoint script for Yii2 prod container
set -e

echo "==> Starting Yii2 prod initialization..."

echo "==> Initialization complete! Starting Apache..."
echo ""
echo " Access your apps at:"
echo "   * API        - http://localhost:40083/"
echo "   * Backoffice - http://localhost:40081/"
echo "   * Frontpage  - http://localhost:40080/"
echo ""

# Start Apache in foreground
exec apache2-foreground
