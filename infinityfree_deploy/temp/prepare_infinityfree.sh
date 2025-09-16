#!/bin/bash

echo "Preparing Laravel project for InfinityFree deployment..."

# 1. Create deployment directory
mkdir -p infinityfree_deploy
cp -r . infinityfree_deploy/temp
cd infinityfree_deploy/temp

# 2. Copy .env.example to .env for modification
cp .env.example .env

# 3. Create a production .env file
cat > .env.production << 'EOF'
APP_NAME="Installment Management System"
APP_ENV=production
APP_KEY=base64:IvajL5L9ZzPkx0LPP8+vRdQpv9cWZSGNJ/+YvXE7ogg=
APP_DEBUG=false
APP_URL=http://yourdomain.infinityfreeapp.com

LOG_CHANNEL=single
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=sqlXXX.infinityfree.com
DB_PORT=3306
DB_DATABASE=if0_XXXXXXX_installment
DB_USERNAME=if0_XXXXXXX
DB_PASSWORD=your_password_here

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MAIL_MAILER=smtp
MAIL_HOST=
MAIL_PORT=
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=
MAIL_FROM_ADDRESS=
MAIL_FROM_NAME="${APP_NAME}"
EOF

# 4. Create .htaccess for public directory
cat > public/.htaccess << 'EOF'
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>

# Disable directory browsing
Options -Indexes

# Protect .env file
<Files .env>
    Order allow,deny
    Deny from all
</Files>

# Protect composer files
<Files composer.json>
    Order allow,deny
    Deny from all
</Files>
EOF

# 5. Create index.php in root for InfinityFree
cat > index.php << 'EOF'
<?php
/**
 * Laravel - A PHP Framework For Web Artisans
 * This file redirects to public/index.php for InfinityFree hosting
 */

// InfinityFree requires index.php in root, so we include the public one
require_once __DIR__ . '/public/index.php';
EOF

# 6. Update public/index.php paths for InfinityFree
cat > public/index.php << 'EOF'
<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
(require_once __DIR__.'/../bootstrap/app.php')
    ->handleRequest(Request::capture());
EOF

# 7. Create database export script
cat > database_export.sql << 'EOF'
-- Database export for InfinityFree
-- Import this in InfinityFree phpMyAdmin

-- Create tables (migrations)
-- Copy the SQL from migrations or use the following:

-- Users table
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Products table
CREATE TABLE IF NOT EXISTS `products` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `category` varchar(255),
  `price` decimal(10,2) NOT NULL,
  `stock_quantity` int(11) DEFAULT 0,
  `sku` varchar(255) UNIQUE,
  `image` varchar(255),
  `is_active` boolean DEFAULT true,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Customers table
CREATE TABLE IF NOT EXISTS `customers` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address` text,
  `city` varchar(255),
  `state` varchar(255),
  `postal_code` varchar(255),
  `country` varchar(255) DEFAULT 'Pakistan',
  `cnic` varchar(255) UNIQUE,
  `date_of_birth` date,
  `credit_limit` decimal(10,2) DEFAULT 0,
  `status` enum('active','inactive','blocked') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `customers_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Installment Plans table
CREATE TABLE IF NOT EXISTS `installment_plans` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `plan_number` varchar(255) UNIQUE NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `down_payment` decimal(10,2) DEFAULT 0,
  `financed_amount` decimal(10,2) NOT NULL,
  `number_of_installments` int(11) NOT NULL,
  `installment_amount` decimal(10,2) NOT NULL,
  `interest_rate` decimal(5,2) DEFAULT 0,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `payment_frequency` enum('monthly','weekly','bi-weekly','quarterly') DEFAULT 'monthly',
  `status` enum('active','completed','cancelled','defaulted') DEFAULT 'active',
  `total_paid` decimal(10,2) DEFAULT 0,
  `balance` decimal(10,2) NOT NULL,
  `payments_made` int(11) DEFAULT 0,
  `next_payment_date` date,
  `notes` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Payments table
CREATE TABLE IF NOT EXISTS `payments` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `payment_number` varchar(255) UNIQUE NOT NULL,
  `installment_plan_id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_date` date NOT NULL,
  `due_date` date NOT NULL,
  `payment_method` enum('cash','bank_transfer','card','cheque','online') DEFAULT 'cash',
  `status` enum('pending','paid','late','failed','refunded') DEFAULT 'pending',
  `reference_number` varchar(255),
  `late_fee` decimal(10,2) DEFAULT 0,
  `notes` text,
  `receipt_number` varchar(255),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`installment_plan_id`) REFERENCES `installment_plans` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Invoices table
CREATE TABLE IF NOT EXISTS `invoices` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `invoice_number` varchar(255) UNIQUE NOT NULL,
  `installment_plan_id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `payment_id` bigint(20) UNSIGNED,
  `invoice_date` date NOT NULL,
  `due_date` date NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `tax_amount` decimal(10,2) DEFAULT 0,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('draft','sent','paid','overdue','cancelled') DEFAULT 'draft',
  `description` text,
  `notes` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`installment_plan_id`) REFERENCES `installment_plans` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default admin user
INSERT INTO `users` (`name`, `email`, `email_verified_at`, `password`, `created_at`, `updated_at`) 
VALUES ('Admin User', 'admin@example.com', NOW(), '$2y$12$NidNgK8XRz2mYWY.P87.LukgoxvwOuuNr950SykmGsihMV0JRKjaG', NOW(), NOW());

-- Insert sample products
INSERT INTO `products` (`name`, `category`, `price`, `stock_quantity`, `sku`, `created_at`, `updated_at`) VALUES
('Samsung Galaxy S23', 'Mobile Phones', 120000, 50, 'SGS23', NOW(), NOW()),
('iPhone 14 Pro', 'Mobile Phones', 150000, 30, 'IP14P', NOW(), NOW()),
('Dell Laptop XPS 13', 'Laptops', 180000, 20, 'DLXPS13', NOW(), NOW()),
('HP Pavilion 15', 'Laptops', 95000, 40, 'HPP15', NOW(), NOW());
EOF

echo "Deployment package prepared!"
echo ""
echo "Files created:"
echo "1. .env.production - Configure this with InfinityFree database details"
echo "2. index.php - Root index file for InfinityFree"
echo "3. public/.htaccess - Proper htaccess configuration"
echo "4. database_export.sql - Database structure to import"
echo ""
echo "Next steps:"
echo "1. Edit .env.production with your InfinityFree database credentials"
echo "2. Upload all files via FTP"
echo "3. Import database_export.sql in phpMyAdmin"