#!/bin/bash

# Exit on error
set -e

echo "🚀 Starting Laravel application..."

# Navigate to working directory
cd /var/www/html

# Install/update composer dependencies if composer.json exists
if [ -f "composer.json" ]; then
    echo "📦 Installing/updating Composer dependencies..."
    composer install --no-interaction --prefer-dist --optimize-autoloader
fi

# Install/update NPM dependencies if package.json exists
if [ -f "package.json" ]; then
    echo "📦 Installing/updating NPM dependencies..."
    npm install
    
    # Build frontend assets with Vite
    echo "🎨 Building frontend assets..."
    npm run build
fi

# Create .env from .env.example if it doesn't exist
if [ ! -f ".env" ] && [ -f ".env.example" ]; then
    echo "📝 Creating .env file from .env.example..."
    cp .env.example .env
    
    # Configure MySQL database connection
    echo "🔧 Configuring MySQL database connection..."
    sed -i 's/DB_CONNECTION=.*/DB_CONNECTION=mysql/' .env
    sed -i 's/# DB_HOST=.*/DB_HOST=db/' .env
    sed -i 's/DB_HOST=.*/DB_HOST=db/' .env
    sed -i 's/# DB_PORT=.*/DB_PORT=3306/' .env
    sed -i 's/DB_PORT=.*/DB_PORT=3306/' .env
    sed -i 's/# DB_DATABASE=.*/DB_DATABASE=laravel/' .env
    sed -i 's/DB_DATABASE=.*/DB_DATABASE=laravel/' .env
    sed -i 's/# DB_USERNAME=.*/DB_USERNAME=laravel/' .env
    sed -i 's/DB_USERNAME=.*/DB_USERNAME=laravel/' .env
    sed -i 's/# DB_PASSWORD=.*/DB_PASSWORD=root/' .env
    sed -i 's/DB_PASSWORD=.*/DB_PASSWORD=root/' .env
fi

# Generate application key if .env exists and APP_KEY is empty
if [ -f ".env" ]; then
    if ! grep -q "APP_KEY=base64:" .env; then
        echo "🔑 Generating application key..."
        php artisan key:generate --ansi
    fi
fi

# Create SQLite database file# Set permissions for SQLite database if it exists
if [ -f ".env" ]; then
    DB_CONNECTION=$(grep "^DB_CONNECTION=" .env | cut -d '=' -f2)
    if [ "$DB_CONNECTION" = "sqlite" ]; then
        # Get DB_DATABASE from .env or use Laravel's default
        DB_DATABASE=$(grep "^DB_DATABASE=" .env | cut -d '=' -f2)
        if [ -z "$DB_DATABASE" ]; then
            DB_DATABASE="database/database.sqlite"
        fi
        
        if [ -f "$DB_DATABASE" ]; then
            chmod 664 "$DB_DATABASE" 2>/dev/null || true
            chown www-data:www-data "$DB_DATABASE" 2>/dev/null || true
            DB_DIR=$(dirname "$DB_DATABASE")
            if [ -d "$DB_DIR" ]; then
                chmod 775 "$DB_DIR" 2>/dev/null || true
                chown www-data:www-data "$DB_DIR" 2>/dev/null || true
            fi
        fi
    fi
fi
# Run migrations if database is available
if [ -f ".env" ]; then
    echo "⏳ Waiting for database..."
    # Wait for database to be ready (max 30 seconds)
    for i in {1..30}; do
        if php artisan migrate:status &>/dev/null; then
            echo "✅ Database is ready!"
            break
        fi
        echo "Waiting for database... ($i/30)"
        sleep 1
    done
    
    # Run migrations
    echo "🔄 Running migrations..."
    php artisan migrate --force || echo "⚠️  Migrations failed or already up to date"
    
    # Run seeders to populate initial data
    echo "🌱 Running database seeders..."
    php artisan db:seed --force || echo "⚠️  Seeders failed or already executed"
fi

# Clear and cache config
echo "🧹 Clearing and caching configuration..."
php artisan config:clear || true
php artisan cache:clear || true
php artisan view:clear || true

# Set proper permissions
echo "🔐 Setting permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true

echo "✅ Laravel application is ready!"

# Execute the main command (php-fpm)
exec "$@"
