# Laravel Docker Setup

This project provides a Docker development environment for Laravel with PHP 8.3, Nginx, and MySQL 8.0.

## Prerequisites
- Docker Desktop installed and running.

## Getting Started

### 1. Build and Start Containers
Run the following command to build the images and start the services:
```bash
docker compose up -d --build
```

### 2. Install Laravel
Since the `src` folder is empty, you need to install Laravel into it. Run this command:
```bash
docker compose run --rm app composer create-project laravel/laravel .
```
*Note: This may take a few minutes as it downloads dependencies.*

### 3. Access the Application
Once the installation is complete, open your browser and navigate to:
[http://localhost:3000](http://localhost:3000)

## Services

- **App**: PHP 8.3 FPM
- **Webserver**: Nginx (Port 3000)
- **Database**: MySQL 8.0 (Port 3306)

## Database Credentials
- **Host**: `db`
- **Database**: `laravel`
- **Username**: `laravel`
- **Password**: `root`
- **Root Password**: `root`

## Useful Commands

- **Stop containers**: `docker compose down`
- **Run Artisan commands**: `docker compose run --rm app php artisan <command>`
- **Run Composer commands**: `docker compose run --rm app composer <command>`
