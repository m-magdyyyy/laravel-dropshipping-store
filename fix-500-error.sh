#!/bin/bash

# Server Quick Fix Script
# استخدم هذا السكريبت على السيرفر لحل مشكلة 500

echo "================================================"
echo "    Laravel 500 Error - Quick Fix Script"
echo "================================================"
echo ""

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

print_success() {
    echo -e "${GREEN}✓ $1${NC}"
}

print_error() {
    echo -e "${RED}✗ $1${NC}"
}

print_info() {
    echo -e "${YELLOW}ℹ $1${NC}"
}

# Check if we're in Laravel project
if [ ! -f "artisan" ]; then
    print_error "Error: Not in Laravel project directory!"
    exit 1
fi

print_info "Starting error fix process..."
echo ""

# Step 1: Clear all cache
print_info "Step 1: Clearing cache..."
php artisan optimize:clear 2>/dev/null
php artisan config:clear 2>/dev/null
php artisan cache:clear 2>/dev/null
php artisan route:clear 2>/dev/null
php artisan view:clear 2>/dev/null
print_success "Cache cleared"
echo ""

# Step 2: Fix permissions
print_info "Step 2: Fixing permissions..."
chmod -R 775 storage 2>/dev/null
chmod -R 775 bootstrap/cache 2>/dev/null
print_success "Permissions fixed"
echo ""

# Step 3: Create missing directories
print_info "Step 3: Creating missing directories..."
mkdir -p storage/framework/cache/data 2>/dev/null
mkdir -p storage/framework/sessions 2>/dev/null
mkdir -p storage/framework/views 2>/dev/null
mkdir -p storage/logs 2>/dev/null
mkdir -p storage/app/public 2>/dev/null
mkdir -p bootstrap/cache 2>/dev/null
print_success "Directories created"
echo ""

# Step 4: Check .env file
print_info "Step 4: Checking .env file..."
if [ ! -f ".env" ]; then
    print_error ".env file not found!"
    if [ -f ".env.example" ]; then
        print_info "Copying .env.example to .env..."
        cp .env.example .env
        print_success ".env created"
    fi
else
    print_success ".env file exists"
fi
echo ""

# Step 5: Check APP_KEY
print_info "Step 5: Checking APP_KEY..."
if grep -q "APP_KEY=$" .env || ! grep -q "APP_KEY=base64:" .env; then
    print_info "Generating APP_KEY..."
    php artisan key:generate --force
    print_success "APP_KEY generated"
else
    print_success "APP_KEY already set"
fi
echo ""

# Step 6: Rebuild autoloader
print_info "Step 6: Rebuilding autoloader..."
if command -v composer &> /dev/null; then
    composer dump-autoload --optimize 2>/dev/null
    print_success "Autoloader rebuilt"
else
    print_error "Composer not found, skipping..."
fi
echo ""

# Step 7: Check storage link
print_info "Step 7: Creating storage link..."
php artisan storage:link 2>/dev/null
print_success "Storage link checked"
echo ""

# Step 8: Rebuild cache
print_info "Step 8: Rebuilding cache..."
php artisan config:cache 2>/dev/null
php artisan route:cache 2>/dev/null
php artisan view:cache 2>/dev/null
print_success "Cache rebuilt"
echo ""

# Step 9: Check owner (may need sudo)
print_info "Step 9: Attempting to fix ownership..."
if chown -R www-data:www-data storage bootstrap/cache 2>/dev/null; then
    print_success "Ownership fixed"
else
    print_info "Could not change ownership. You may need to run:"
    echo "  sudo chown -R www-data:www-data storage bootstrap/cache"
fi
echo ""

# Step 10: Show diagnostics
print_info "Step 10: Running diagnostics..."
echo ""
echo "PHP Version:"
php -v | head -1
echo ""

echo ".env file exists:"
ls -la .env 2>/dev/null || echo "Not found"
echo ""

echo "Storage permissions:"
ls -ld storage/ 2>/dev/null || echo "Not found"
echo ""

echo "Bootstrap cache permissions:"
ls -ld bootstrap/cache/ 2>/dev/null || echo "Not found"
echo ""

if [ -f "storage/logs/laravel.log" ]; then
    echo "Last 10 lines of Laravel log:"
    tail -10 storage/logs/laravel.log
else
    print_error "Laravel log file not found"
fi
echo ""

# Summary
echo "================================================"
echo "           Quick Fix Complete!"
echo "================================================"
echo ""

print_success "All steps completed!"
echo ""
echo "Next steps:"
echo "1. Try accessing your website again"
echo "2. If still showing 500 error, check the logs above"
echo "3. Read SERVER_ERROR_500_FIX.md for detailed troubleshooting"
echo ""

print_info "To view detailed error, temporarily set in .env:"
echo "  APP_DEBUG=true"
echo "  APP_ENV=local"
echo ""
print_error "⚠️  Don't forget to set APP_DEBUG=false after fixing!"
echo ""
