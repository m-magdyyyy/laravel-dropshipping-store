#!/bin/bash

echo "================================================"
echo "    Fix Permissions for Laravel on Server"
echo "================================================"
echo ""

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

print_success() {
    echo -e "${GREEN}✓ $1${NC}"
}

print_error() {
    echo -e "${RED}✗ $1${NC}"
}

print_info() {
    echo -e "${YELLOW}ℹ $1${NC}"
}

# Check if we're in the correct directory
if [ ! -f "artisan" ]; then
    print_error "Error: artisan file not found. Are you in the Laravel project root?"
    exit 1
fi

print_info "This script will fix common permission issues..."
echo ""

# Get the web server user
print_info "Detecting web server user..."
if id "www-data" &>/dev/null; then
    WEB_USER="www-data"
    print_success "Found www-data user"
elif id "apache" &>/dev/null; then
    WEB_USER="apache"
    print_success "Found apache user"
elif id "nginx" &>/dev/null; then
    WEB_USER="nginx"
    print_success "Found nginx user"
else
    print_error "Could not detect web server user"
    read -p "Enter your web server user (e.g., www-data, apache, nginx): " WEB_USER
fi
echo ""

# Fix storage permissions
print_info "Fixing storage directory permissions..."
chmod -R 775 storage
if [ -d "storage/framework/cache" ]; then
    chmod -R 775 storage/framework/cache
fi
if [ -d "storage/framework/sessions" ]; then
    chmod -R 775 storage/framework/sessions
fi
if [ -d "storage/framework/views" ]; then
    chmod -R 775 storage/framework/views
fi
if [ -d "storage/logs" ]; then
    chmod -R 775 storage/logs
fi
if [ -d "storage/app/public" ]; then
    chmod -R 775 storage/app/public
fi
print_success "Storage permissions fixed"
echo ""

# Fix bootstrap/cache permissions
print_info "Fixing bootstrap/cache directory permissions..."
chmod -R 775 bootstrap/cache
print_success "Bootstrap cache permissions fixed"
echo ""

# Change ownership (may require sudo)
print_info "Attempting to change ownership to $WEB_USER..."
if chown -R $USER:$WEB_USER storage bootstrap/cache 2>/dev/null; then
    print_success "Ownership changed successfully"
elif sudo chown -R $USER:$WEB_USER storage bootstrap/cache 2>/dev/null; then
    print_success "Ownership changed successfully (with sudo)"
else
    print_error "Could not change ownership. You may need to run this script with sudo:"
    echo "sudo ./fix-permissions.sh"
fi
echo ""

# Create necessary directories if they don't exist
print_info "Ensuring all required directories exist..."
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p storage/app/public
mkdir -p bootstrap/cache
print_success "All directories created"
echo ""

# Show current permissions
print_info "Current permissions:"
echo ""
echo "Storage directory:"
ls -ld storage/
echo ""
echo "Bootstrap cache directory:"
ls -ld bootstrap/cache/
echo ""

echo "================================================"
echo "       Permissions Fixed!"
echo "================================================"
echo ""
print_success "Permissions have been updated successfully!"
echo ""
echo "If you still have permission errors:"
echo "1. Try running this script with sudo: sudo ./fix-permissions.sh"
echo "2. Make sure SELinux is configured correctly (if enabled)"
echo "3. Check your web server error logs for specific permission errors"
echo ""
