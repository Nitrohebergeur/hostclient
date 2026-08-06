#!/bin/bash

# HostClient Installation Script
# Usage: bash <(curl -sSL https://raw.githubusercontent.com/Nitrohebergeur/hostclient/main/install.sh)

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Functions
print_success() {
    echo -e "${GREEN}✓ $1${NC}"
}

print_error() {
    echo -e "${RED}✗ $1${NC}"
}

print_info() {
    echo -e "${BLUE}ℹ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠ $1${NC}"
}

print_header() {
    echo -e "${BLUE}"
    echo "╔════════════════════════════════════════╗"
    echo "║      HostClient Installer v1.0         ║"
    echo "║   https://github.com/Nitrohebergeur    ║"
    echo "╚════════════════════════════════════════╝"
    echo -e "${NC}"
}

# Check if command exists
command_exists() {
    command -v "$1" >/dev/null 2>&1
}

# Check system requirements
check_requirements() {
    print_info "Checking system requirements..."
    
    local missing_requirements=()
    
    # Check PHP
    if ! command_exists php; then
        missing_requirements+=("PHP 8.2+")
    else
        PHP_VERSION=$(php -r "echo PHP_VERSION;")
        print_success "PHP $PHP_VERSION installed"
    fi
    
    # Check Composer
    if ! command_exists composer; then
        missing_requirements+=("Composer")
    else
        print_success "Composer installed"
    fi
    
    # Check Git
    if ! command_exists git; then
        missing_requirements+=("Git")
    else
        print_success "Git installed"
    fi
    
    # Check Node.js
    if ! command_exists node; then
        print_warning "Node.js not found (optional for asset compilation)"
    else
        NODE_VERSION=$(node -v)
        print_success "Node.js $NODE_VERSION installed"
    fi
    
    if [ ${#missing_requirements[@]} -gt 0 ]; then
        print_error "Missing requirements:"
        for req in "${missing_requirements[@]}"; do
            echo "  - $req"
        done
        echo ""
        print_info "Please install the missing requirements and try again."
        exit 1
    fi
}

# Clone repository
clone_repository() {
    print_info "Cloning HostClient repository..."
    
    if [ -d "hostclient" ]; then
        print_warning "Directory 'hostclient' already exists."
        read -p "Do you want to remove it and continue? (y/N): " -n 1 -r
        echo
        if [[ $REPLY =~ ^[Yy]$ ]]; then
            rm -rf hostclient
        else
            print_error "Installation cancelled."
            exit 1
        fi
    fi
    
    git clone https://github.com/Nitrohebergeur/hostclient.git
    cd hostclient
    print_success "Repository cloned successfully"
}

# Install dependencies
install_dependencies() {
    print_info "Installing Composer dependencies..."
    composer install --no-dev --optimize-autoloader
    print_success "Composer dependencies installed"
    
    if command_exists npm; then
        print_info "Installing NPM dependencies..."
        npm install
        print_success "NPM dependencies installed"
        
        print_info "Building assets..."
        npm run build
        print_success "Assets built successfully"
    fi
}

# Setup environment
setup_environment() {
    print_info "Setting up environment configuration..."
    
    if [ ! -f ".env" ]; then
        cp .env.example .env
        print_success "Environment file created"
    else
        print_warning ".env file already exists, skipping..."
    fi
    
    print_info "Generating application key..."
    php artisan key:generate
    print_success "Application key generated"
}

# Database setup
setup_database() {
    print_info "Database setup..."
    echo ""
    print_warning "Please configure your database settings in the .env file"
    echo ""
    read -p "Have you configured your database settings? (y/N): " -n 1 -r
    echo
    
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        print_info "Running database migrations..."
        php artisan migrate --force
        print_success "Database migrated successfully"
        
        read -p "Do you want to seed the database with sample data? (y/N): " -n 1 -r
        echo
        if [[ $REPLY =~ ^[Yy]$ ]]; then
            php artisan db:seed
            print_success "Database seeded successfully"
        fi
    else
        print_warning "Skipping database setup. Run 'php artisan migrate' after configuration."
    fi
}

# Set permissions
set_permissions() {
    print_info "Setting directory permissions..."
    
    chmod -R 755 storage bootstrap/cache
    
    if [ -d "storage" ]; then
        chmod -R 775 storage
    fi
    
    if [ -d "bootstrap/cache" ]; then
        chmod -R 775 bootstrap/cache
    fi
    
    print_success "Permissions set successfully"
}

# Create symbolic link for storage
create_storage_link() {
    print_info "Creating storage symbolic link..."
    php artisan storage:link
    print_success "Storage link created"
}

# Final instructions
show_final_instructions() {
    echo ""
    echo -e "${GREEN}╔════════════════════════════════════════╗${NC}"
    echo -e "${GREEN}║     Installation completed! 🎉         ║${NC}"
    echo -e "${GREEN}╚════════════════════════════════════════╝${NC}"
    echo ""
    print_info "Next steps:"
    echo "  1. Configure your .env file with your settings"
    echo "  2. Run: php artisan migrate (if not done)"
    echo "  3. Start the development server:"
    echo "     ${BLUE}php artisan serve${NC}"
    echo ""
    echo "  Or configure your web server to point to the 'public' directory"
    echo ""
    print_info "Documentation: https://github.com/Nitrohebergeur/hostclient"
    echo ""
}

# Main installation flow
main() {
    print_header
    
    check_requirements
    echo ""
    
    clone_repository
    echo ""
    
    install_dependencies
    echo ""
    
    setup_environment
    echo ""
    
    setup_database
    echo ""
    
    set_permissions
    echo ""
    
    create_storage_link
    echo ""
    
    show_final_instructions
}

# Run main installation
main
