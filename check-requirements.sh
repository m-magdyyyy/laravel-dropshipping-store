#!/bin/bash

echo "================================================"
echo "    Laravel Server Requirements Checker"
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

print_warning() {
    echo -e "${YELLOW}⚠ $1${NC}"
}

print_info() {
    echo -e "${YELLOW}ℹ $1${NC}"
}

ERRORS=0
WARNINGS=0

# Check PHP version
echo "Checking PHP requirements..."
echo "----------------------------"

if command -v php &> /dev/null; then
    PHP_VERSION=$(php -r "echo PHP_VERSION;")
    PHP_MAJOR=$(php -r "echo PHP_MAJOR_VERSION;")
    PHP_MINOR=$(php -r "echo PHP_MINOR_VERSION;")
    
    if [ "$PHP_MAJOR" -ge 8 ] && [ "$PHP_MINOR" -ge 2 ]; then
        print_success "PHP version: $PHP_VERSION (Required: >= 8.2)"
    else
        print_error "PHP version: $PHP_VERSION (Required: >= 8.2)"
        ERRORS=$((ERRORS+1))
    fi
else
    print_error "PHP is not installed"
    ERRORS=$((ERRORS+1))
fi
echo ""

# Check PHP extensions
echo "Checking PHP extensions..."
echo "--------------------------"

REQUIRED_EXTENSIONS=(
    "ctype"
    "curl"
    "dom"
    "fileinfo"
    "filter"
    "hash"
    "mbstring"
    "openssl"
    "pcre"
    "pdo"
    "session"
    "tokenizer"
    "xml"
    "zip"
)

for ext in "${REQUIRED_EXTENSIONS[@]}"; do
    if php -m | grep -q "^$ext$"; then
        print_success "Extension: $ext"
    else
        print_error "Extension: $ext (MISSING)"
        ERRORS=$((ERRORS+1))
    fi
done
echo ""

# Check Composer
echo "Checking Composer..."
echo "--------------------"
if command -v composer &> /dev/null; then
    COMPOSER_VERSION=$(composer --version --no-ansi | head -n 1 | cut -d " " -f 3)
    print_success "Composer version: $COMPOSER_VERSION"
else
    print_error "Composer is not installed"
    ERRORS=$((ERRORS+1))
fi
echo ""

# Check Node.js
echo "Checking Node.js..."
echo "-------------------"
if command -v node &> /dev/null; then
    NODE_VERSION=$(node --version)
    print_success "Node.js version: $NODE_VERSION"
else
    print_error "Node.js is not installed"
    ERRORS=$((ERRORS+1))
fi
echo ""

# Check NPM
echo "Checking NPM..."
echo "---------------"
if command -v npm &> /dev/null; then
    NPM_VERSION=$(npm --version)
    print_success "NPM version: $NPM_VERSION"
else
    print_error "NPM is not installed"
    ERRORS=$((ERRORS+1))
fi
echo ""

# Check web server
echo "Checking web server..."
echo "----------------------"
WEB_SERVER_FOUND=0

if command -v apache2 &> /dev/null || command -v httpd &> /dev/null; then
    print_success "Apache web server detected"
    WEB_SERVER_FOUND=1
fi

if command -v nginx &> /dev/null; then
    print_success "Nginx web server detected"
    WEB_SERVER_FOUND=1
fi

if [ $WEB_SERVER_FOUND -eq 0 ]; then
    print_warning "No web server detected (Apache or Nginx)"
    WARNINGS=$((WARNINGS+1))
fi
echo ""

# Check if we're in Laravel project
if [ -f "artisan" ]; then
    echo "Checking Laravel project..."
    echo "---------------------------"
    
    # Check .env file
    if [ -f ".env" ]; then
        print_success ".env file exists"
        
        # Check APP_KEY
        if grep -q "APP_KEY=base64:" .env; then
            print_success "APP_KEY is set"
        else
            print_warning "APP_KEY is not set (run: php artisan key:generate)"
            WARNINGS=$((WARNINGS+1))
        fi
    else
        print_warning ".env file not found"
        WARNINGS=$((WARNINGS+1))
    fi
    
    # Check storage permissions
    if [ -d "storage" ]; then
        if [ -w "storage" ]; then
            print_success "storage/ directory is writable"
        else
            print_error "storage/ directory is NOT writable"
            ERRORS=$((ERRORS+1))
        fi
    fi
    
    # Check bootstrap/cache permissions
    if [ -d "bootstrap/cache" ]; then
        if [ -w "bootstrap/cache" ]; then
            print_success "bootstrap/cache/ directory is writable"
        else
            print_error "bootstrap/cache/ directory is NOT writable"
            ERRORS=$((ERRORS+1))
        fi
    fi
    
    # Check vendor directory
    if [ -d "vendor" ]; then
        print_success "vendor/ directory exists (dependencies installed)"
    else
        print_warning "vendor/ directory not found (run: composer install)"
        WARNINGS=$((WARNINGS+1))
    fi
    
    # Check node_modules directory
    if [ -d "node_modules" ]; then
        print_success "node_modules/ directory exists (dependencies installed)"
    else
        print_warning "node_modules/ directory not found (run: npm install)"
        WARNINGS=$((WARNINGS+1))
    fi
    
    echo ""
fi

# Summary
echo "================================================"
echo "                 Summary"
echo "================================================"
echo ""

if [ $ERRORS -eq 0 ] && [ $WARNINGS -eq 0 ]; then
    print_success "All checks passed! Your server is ready for Laravel."
elif [ $ERRORS -eq 0 ]; then
    print_warning "Found $WARNINGS warning(s). Server should work but may need attention."
else
    print_error "Found $ERRORS error(s) and $WARNINGS warning(s)."
    echo ""
    echo "Please fix the errors before deploying your Laravel application."
fi

echo ""

# Installation suggestions
if [ $ERRORS -gt 0 ]; then
    echo "Installation suggestions:"
    echo "========================="
    echo ""
    
    echo "For Ubuntu/Debian:"
    echo "  sudo apt update"
    echo "  sudo apt install php8.2 php8.2-cli php8.2-common php8.2-mysql php8.2-zip"
    echo "  sudo apt install php8.2-gd php8.2-mbstring php8.2-curl php8.2-xml php8.2-bcmath"
    echo "  sudo apt install composer nodejs npm"
    echo ""
    
    echo "For CentOS/RHEL:"
    echo "  sudo yum install php php-cli php-common php-mysql php-zip"
    echo "  sudo yum install php-gd php-mbstring php-curl php-xml php-bcmath"
    echo "  sudo yum install composer nodejs npm"
    echo ""
fi

exit $ERRORS
