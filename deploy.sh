#!/bin/bash

echo "================================================"
echo "       Laravel Deployment Script"
echo "================================================"
echo ""

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to print colored messages
print_success() {
    echo -e "${GREEN}✓ $1${NC}"
}

print_error() {
    echo -e "${RED}✗ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠ $1${NC}"
}

print_info() {
    echo -e "${YELLOW}ℹ $1${NC}"
}

# Check if we're in the correct directory
if [ ! -f "artisan" ]; then
    print_error "Error: artisan file not found. Are you in the Laravel project root?"
    exit 1
fi

print_info "Starting deployment process..."
echo ""

# Step 1: Install Composer dependencies
print_info "Step 1: Installing Composer dependencies..."
if composer install --optimize-autoloader --no-dev; then
    print_success "Composer dependencies installed"
else
    print_error "Failed to install Composer dependencies"
    exit 1
fi
echo ""

# Step 2: Install NPM dependencies
print_info "Step 2: Installing NPM dependencies..."
if npm install; then
    print_success "NPM dependencies installed"
else
    print_error "Failed to install NPM dependencies"
    exit 1
fi
echo ""

# Step 3: Build assets
print_info "Step 3: Building assets..."
if npm run build; then
    print_success "Assets built successfully"
else
    print_error "Failed to build assets"
    exit 1
fi
echo ""

# Step 4: Check .env file
print_info "Step 4: Checking .env file..."
if [ ! -f ".env" ]; then
    print_warning ".env file not found!"
    if [ -f ".env.production" ]; then
        print_info "Copying .env.production to .env..."
        cp .env.production .env
        print_success ".env file created from .env.production"
    elif [ -f ".env.example" ]; then
        print_info "Copying .env.example to .env..."
        cp .env.example .env
        print_success ".env file created from .env.example"
    else
        print_error "No .env template found!"
        exit 1
    fi
else
    print_success ".env file exists"
fi
echo ""

# Step 5: Generate application key if needed
print_info "Step 5: Checking application key..."
if grep -q "APP_KEY=$" .env || ! grep -q "APP_KEY=" .env; then
    print_info "Generating application key..."
    php artisan key:generate --force
    print_success "Application key generated"
else
    print_success "Application key already set"
fi
echo ""

# Step 6: Set correct permissions
print_info "Step 6: Setting correct permissions..."
chmod -R 775 storage bootstrap/cache
print_success "Permissions set for storage and bootstrap/cache"
echo ""

# Step 7: Create storage link
print_info "Step 7: Creating storage link..."
if php artisan storage:link; then
    print_success "Storage link created"
else
    print_warning "Storage link may already exist"
fi
echo ""

# Step 8: Run migrations
print_info "Step 8: Running migrations..."
read -p "Do you want to run migrations? (y/n): " -n 1 -r
echo ""
if [[ $REPLY =~ ^[Yy]$ ]]; then
    if php artisan migrate --force; then
        print_success "Migrations completed"
    else
        print_error "Migrations failed"
        exit 1
    fi
else
    print_warning "Skipped migrations"
fi
echo ""

# Step 9: Optimize application
print_info "Step 9: Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
composer dump-autoload --optimize
print_success "Application optimized"
echo ""

# Step 10: Final checks
print_info "Step 10: Running final checks..."
echo ""

print_info "Checking file permissions..."
ls -la storage/ | head -n 5
ls -la bootstrap/cache/ | head -n 5
echo ""

print_info "Checking .env configuration..."
grep "APP_ENV=" .env
grep "APP_DEBUG=" .env
grep "APP_URL=" .env
echo ""

# Summary
echo "================================================"
echo "       Deployment Complete!"
echo "================================================"
echo ""
print_success "Your Laravel application has been deployed successfully!"
echo ""
echo "Next steps:"
echo "1. Update your .env file with production settings:"
echo "   - Set APP_URL to your domain"
echo "   - Set APP_DEBUG=false"
echo "   - Set APP_ENV=production"
echo "   - Configure your database settings"
echo ""
echo "2. If using Apache/Nginx, configure your web server"
echo "   (see DEPLOYMENT_GUIDE.md for details)"
echo ""
echo "3. Test your application thoroughly"
echo ""
echo "4. Monitor logs: tail -f storage/logs/laravel.log"
echo ""
print_warning "IMPORTANT: Make sure to set APP_DEBUG=false in production!"
echo ""
