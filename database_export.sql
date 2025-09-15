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
