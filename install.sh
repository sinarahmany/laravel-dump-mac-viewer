#!/bin/bash

# Laravel Dump macOS Viewer - Zero Configuration Installer
# This script sets up the Laravel Dump integration with zero configuration

set -e

echo "🚀 Laravel Dump macOS Viewer - Zero Configuration Setup"
echo "========================================================"
echo ""

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${GREEN}✅ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

print_error() {
    echo -e "${RED}❌ $1${NC}"
}

print_info() {
    echo -e "${BLUE}ℹ️  $1${NC}"
}

# Check if we're in a Laravel project
if [ ! -f "artisan" ]; then
    print_error "This doesn't appear to be a Laravel project (no artisan file found)"
    print_info "Please run this script from your Laravel project root directory"
    exit 1
fi

print_status "Found Laravel project"

# Check if Composer is available
if ! command -v composer &> /dev/null; then
    print_error "Composer is not installed or not in PATH"
    print_info "Please install Composer first: https://getcomposer.org/download/"
    exit 1
fi

print_status "Composer is available"

# Install the package
print_info "Installing Laravel Dump macOS Viewer package..."
composer require laravel-dump/mac-viewer --dev

if [ $? -eq 0 ]; then
    print_status "Package installed successfully"
else
    print_error "Failed to install package"
    exit 1
fi

# Check if the macOS app is running
print_info "Checking if Laravel Dump macOS app is running..."

PORTS=(9999 9998 9997)
FOUND_PORT=""

for port in "${PORTS[@]}"; do
    if nc -z localhost $port 2>/dev/null; then
        FOUND_PORT=$port
        break
    fi
done

if [ -n "$FOUND_PORT" ]; then
    print_status "Found Laravel Dump app on port $FOUND_PORT"
else
    print_warning "Laravel Dump macOS app is not running"
    print_info "Please:"
    print_info "1. Open the Laravel Dump macOS app"
    print_info "2. Make sure it's listening on a port"
    print_info "3. Run 'php artisan laravel-dump:setup' to configure"
fi

# Run the setup command
print_info "Running Laravel Dump setup command..."
php artisan laravel-dump:setup --test

if [ $? -eq 0 ]; then
    print_status "Setup completed successfully!"
    echo ""
    print_info "You can now use these functions in your Laravel code:"
    print_info "• app_dump(\$data) - Dump data to macOS app"
    print_info "• app_dd(\$data) - Dump and die"
    print_info "• mac_dump(\$data) - Short alias"
    print_info "• mac_dd(\$data) - Short alias"
    echo ""
    print_info "Example usage:"
    print_info "app_dump(['user' => 'John', 'age' => 30]);"
    print_info "mac_dd(\$request->all());"
else
    print_warning "Setup completed with warnings"
    print_info "You can run 'php artisan laravel-dump:setup' manually to configure"
fi

echo ""
print_status "Laravel Dump macOS Viewer setup complete!"
print_info "Start dumping in your Laravel code and watch the magic happen! 🎉"
