#!/bin/bash

# Complete Server Fix Script - Auto Fix Everything
# استخدم هذا السكريبت لإصلاح كل المشاكل أوتوماتيكياً

echo "================================================"
echo "    Complete Server Fix - Auto Setup"
echo "================================================"
echo ""

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
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

print_step() {
    echo -e "${BLUE}▶ $1${NC}"
}

# Check if running as root or with sudo
if [ "$EUID" -ne 0 ]; then 
    print_error "This script needs sudo privileges. Please run with sudo:"
    echo "  sudo ./complete-server-fix.sh"
    exit 1
fi

# Get the actual user (not root)
ACTUAL_USER=${SUDO_USER:-$USER}
print_info "Running as: root (for $ACTUAL_USER)"
echo ""

# Detect PHP version for Apache
print_step "Step 1: Detecting PHP version..."
if [ -d "/etc/php/8.4/apache2" ]; then
    PHP_VERSION="8.4"
    print_success "Found PHP 8.4 for Apache"
elif [ -d "/etc/php/8.3/apache2" ]; then
    PHP_VERSION="8.3"
    print_success "Found PHP 8.3 for Apache"
elif [ -d "/etc/php/8.2/apache2" ]; then
    PHP_VERSION="8.2"
    print_success "Found PHP 8.2 for Apache"
elif [ -d "/etc/php/8.1/apache2" ]; then
    PHP_VERSION="8.1"
    print_success "Found PHP 8.1 for Apache"
else
    print_error "Could not find PHP for Apache"
    print_info "Available PHP versions:"
    ls -d /etc/php/*/apache2 2>/dev/null || echo "No PHP for Apache found"
    exit 1
fi
echo ""

# Step 2: Update PHP settings
print_step "Step 2: Updating PHP settings..."
PHP_INI="/etc/php/$PHP_VERSION/apache2/php.ini"

if [ -f "$PHP_INI" ]; then
    # Backup original file
    cp "$PHP_INI" "$PHP_INI.backup.$(date +%Y%m%d_%H%M%S)"
    print_success "Backup created: $PHP_INI.backup.*"
    
    # Update settings
    sed -i 's/^upload_max_filesize = .*/upload_max_filesize = 100M/' "$PHP_INI"
    sed -i 's/^post_max_size = .*/post_max_size = 100M/' "$PHP_INI"
    sed -i 's/^memory_limit = .*/memory_limit = 512M/' "$PHP_INI"
    sed -i 's/^max_execution_time = .*/max_execution_time = 300/' "$PHP_INI"
    sed -i 's/^max_input_time = .*/max_input_time = 300/' "$PHP_INI"
    
    print_success "PHP settings updated in $PHP_INI"
else
    print_error "PHP ini file not found: $PHP_INI"
    exit 1
fi
echo ""

# Step 3: Restart Apache
print_step "Step 3: Restarting Apache..."
systemctl restart apache2
if [ $? -eq 0 ]; then
    print_success "Apache restarted successfully"
else
    print_error "Failed to restart Apache"
    exit 1
fi
echo ""

# Step 4: Navigate to project directory
PROJECT_DIR="/var/www/shop"
if [ ! -d "$PROJECT_DIR" ]; then
    print_error "Project directory not found: $PROJECT_DIR"
    exit 1
fi

cd "$PROJECT_DIR"
print_success "Changed to project directory: $PROJECT_DIR"
echo ""

# Step 5: Pull latest changes
print_step "Step 5: Pulling latest changes from GitHub..."
sudo -u $ACTUAL_USER git pull origin main
print_success "Code updated"
echo ""

# Step 6: Clear all cache
print_step "Step 6: Clearing Laravel cache..."
php artisan optimize:clear 2>/dev/null
php artisan config:clear 2>/dev/null
php artisan cache:clear 2>/dev/null
php artisan route:clear 2>/dev/null
php artisan view:clear 2>/dev/null
print_success "Cache cleared"
echo ""

# Step 7: Fix permissions
print_step "Step 7: Fixing permissions..."
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
chown -R www-data:www-data public
print_success "Permissions fixed"
echo ""

# Step 8: Create missing directories
print_step "Step 8: Creating missing directories..."
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p storage/app/public
mkdir -p bootstrap/cache
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
print_success "Directories created"
echo ""

# Step 9: Check and generate APP_KEY
print_step "Step 9: Checking APP_KEY..."
if [ ! -f ".env" ]; then
    print_info "Creating .env from .env.example..."
    cp .env.example .env
    chown $ACTUAL_USER:$ACTUAL_USER .env
fi

if grep -q "APP_KEY=$" .env || ! grep -q "APP_KEY=base64:" .env; then
    print_info "Generating APP_KEY..."
    php artisan key:generate --force
    print_success "APP_KEY generated"
else
    print_success "APP_KEY already set"
fi
echo ""

# Step 10: Install/Update dependencies (if needed)
print_step "Step 10: Checking dependencies..."
if [ ! -d "vendor" ]; then
    print_info "Installing Composer dependencies..."
    sudo -u $ACTUAL_USER composer install --optimize-autoloader --no-dev
    print_success "Dependencies installed"
else
    print_info "Updating autoloader..."
    sudo -u $ACTUAL_USER composer dump-autoload --optimize
    print_success "Autoloader updated"
fi
echo ""

# Step 11: Storage link
print_step "Step 11: Creating storage link..."
php artisan storage:link 2>/dev/null || print_info "Storage link already exists"
print_success "Storage link checked"
echo ""

# Step 12: Rebuild cache
print_step "Step 12: Rebuilding cache..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
print_success "Cache rebuilt"
echo ""

# Step 13: Run migrations (optional - commented out for safety)
# print_step "Step 13: Running migrations..."
# php artisan migrate --force
# print_success "Migrations completed"
# echo ""

# Step 14: Final diagnostics
print_step "Step 14: Final diagnostics..."
echo ""
echo "PHP Settings:"
php -i | grep -E 'upload_max_filesize|post_max_size|memory_limit' | head -3
echo ""
echo "Laravel Version:"
php artisan --version
echo ""
echo "Storage Permissions:"
ls -ld storage/
echo ""
echo "Bootstrap Cache Permissions:"
ls -ld bootstrap/cache/
echo ""

# Summary
echo "================================================"
echo "           Setup Complete! ✅"
echo "================================================"
echo ""
print_success "All steps completed successfully!"
echo ""
echo "Next steps:"
echo "1. Open your website: http://fekra-store.shop"
echo "2. Try uploading a product image"
echo "3. If you see any errors, check logs:"
echo "   tail -50 storage/logs/laravel.log"
echo ""
print_info "Upload limits are now:"
echo "  - upload_max_filesize: 100M"
echo "  - post_max_size: 100M"
echo "  - memory_limit: 512M"
echo ""
print_success "🎉 Your server is ready!"
echo ""
