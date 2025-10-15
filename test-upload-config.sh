#!/bin/bash

echo "================================================"
echo "    Testing PHP Upload Configuration"
echo "================================================"
echo ""

# Colors
GREEN='\033[0;32m'
RED='\033[0;31m'
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

# Check if php.ini exists
if [ -f "php.ini" ]; then
    print_success "php.ini file found"
else
    print_error "php.ini file not found!"
    exit 1
fi

echo ""
echo "Testing PHP configuration with php.ini:"
echo "----------------------------------------"

# Test with php.ini
if command -v php &> /dev/null; then
    UPLOAD_MAX=$(php -c php.ini -r "echo ini_get('upload_max_filesize');")
    POST_MAX=$(php -c php.ini -r "echo ini_get('post_max_size');")
    MEMORY=$(php -c php.ini -r "echo ini_get('memory_limit');")
    MAX_EXEC=$(php -c php.ini -r "echo ini_get('max_execution_time');")
    MAX_FILES=$(php -c php.ini -r "echo ini_get('max_file_uploads');")
    
    echo "upload_max_filesize: $UPLOAD_MAX"
    echo "post_max_size: $POST_MAX"
    echo "memory_limit: $MEMORY"
    echo "max_execution_time: $MAX_EXEC seconds"
    echo "max_file_uploads: $MAX_FILES"
    echo ""
    
    # Check if values are correct
    if [ "$UPLOAD_MAX" = "100M" ]; then
        print_success "upload_max_filesize is correctly set to 100M"
    else
        print_error "upload_max_filesize is $UPLOAD_MAX, expected 100M"
    fi
    
    if [ "$POST_MAX" = "100M" ]; then
        print_success "post_max_size is correctly set to 100M"
    else
        print_error "post_max_size is $POST_MAX, expected 100M"
    fi
    
    if [ "$MEMORY" = "512M" ]; then
        print_success "memory_limit is correctly set to 512M"
    else
        print_error "memory_limit is $MEMORY, expected 512M"
    fi
    
else
    print_error "PHP is not installed"
    exit 1
fi

echo ""
echo "================================================"
echo "           Configuration Test Complete"
echo "================================================"
echo ""

print_info "To start the development server with these settings:"
echo "  ./start-dev.sh"
echo "  or"
echo "  npm run dev"
echo ""
