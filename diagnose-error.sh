#!/bin/bash

# Quick Diagnostics Script
# استخدم هذا لمعرفة السبب الحقيقي لخطأ 500

echo "================================================"
echo "    Laravel Error Diagnostics"
echo "================================================"
echo ""

cd /var/www/shop

echo "1. Checking Laravel Log (last 30 lines):"
echo "=========================================="
if [ -f "storage/logs/laravel.log" ]; then
    tail -30 storage/logs/laravel.log
else
    echo "⚠️  Laravel log file not found!"
fi
echo ""

echo "2. Checking Apache Error Log (last 20 lines):"
echo "=============================================="
sudo tail -20 /var/log/apache2/error.log
echo ""

echo "3. Checking .env file:"
echo "======================"
if [ -f ".env" ]; then
    echo "✓ .env file exists"
    echo "APP_ENV: $(grep '^APP_ENV=' .env)"
    echo "APP_DEBUG: $(grep '^APP_DEBUG=' .env)"
    echo "APP_KEY: $(grep '^APP_KEY=' .env | cut -c1-20)..."
else
    echo "✗ .env file not found!"
fi
echo ""

echo "4. Checking permissions:"
echo "========================"
echo "Storage:"
ls -ld storage/
echo ""
echo "Bootstrap/cache:"
ls -ld bootstrap/cache/
echo ""

echo "5. Checking if vendor exists:"
echo "============================="
if [ -d "vendor" ]; then
    echo "✓ vendor directory exists"
else
    echo "✗ vendor directory missing!"
fi
echo ""

echo "6. Testing PHP directly:"
echo "========================"
php artisan --version 2>&1 || echo "Error running artisan"
echo ""

echo "================================================"
echo "           Diagnostics Complete"
echo "================================================"
