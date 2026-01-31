-- TechNovation Solutions Database Schema
-- Educational Purpose - Contains Intentional Vulnerabilities
-- Created: January 2026

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS `technovation` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `technovation`;

-- --------------------------------------------------------
-- Table structure for table `users`
-- VULNERABILITIES: Plain text passwords, weak constraints
-- --------------------------------------------------------

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,  -- Stored in plain text (vulnerability)
  `role` varchar(20) DEFAULT 'user',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `last_login` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert default users with weak passwords
INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'admin', 'admin@technovation.com', 'admin123', 'admin', NOW()),
(2, 'john_doe', 'john@example.com', 'password', 'user', NOW()),
(3, 'jane_smith', 'jane@example.com', '12345', 'user', NOW()),
(4, 'test_user', 'test@test.com', 'test123', 'user', NOW()),
(5, 'demo', 'demo@technovation.com', 'demo', 'user', NOW());

-- --------------------------------------------------------
-- Table structure for table `products`
-- --------------------------------------------------------

CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) DEFAULT 100,
  `category` varchar(50) DEFAULT 'Electronics',
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert sample products
INSERT INTO `products` (`id`, `name`, `description`, `price`, `stock`, `category`) VALUES
(1, 'Laptop Pro 15', 'High performance laptop with Intel i7 processor, 16GB RAM, 512GB SSD. Perfect for professionals and gamers.', 3500.00, 25, 'Computers'),
(2, 'Smartphone X1', 'Latest Android smartphone with 6.5" AMOLED display, 128GB storage, 48MP camera.', 2500.00, 50, 'Mobile'),
(3, 'Wireless Headphones', 'Premium noise-cancelling wireless headphones with 30-hour battery life.', 450.00, 100, 'Audio'),
(4, 'Smart Watch Pro', '4G enabled smartwatch with health tracking, GPS, and water resistance.', 899.00, 75, 'Wearables'),
(5, 'Tablet Ultra', '10.5" tablet with stylus support, 256GB storage, perfect for creativity.', 1899.00, 40, 'Tablets'),
(6, 'Gaming Keyboard RGB', 'Mechanical gaming keyboard with customizable RGB lighting and macro keys.', 299.00, 150, 'Accessories'),
(7, 'External SSD 1TB', 'Portable solid state drive with USB-C, up to 1050MB/s transfer speed.', 399.00, 80, 'Storage'),
(8, 'Webcam 4K', '4K Ultra HD webcam with auto-focus, perfect for streaming and video calls.', 199.00, 120, 'Accessories');

-- --------------------------------------------------------
-- Table structure for table `orders`
-- --------------------------------------------------------

CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT 0.00,
  `order_date` timestamp DEFAULT CURRENT_TIMESTAMP,
  `status` enum('pending','processing','completed','cancelled') DEFAULT 'pending',
  `shipping_address` text,
  `payment_method` varchar(50) DEFAULT 'credit_card',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert sample orders
INSERT INTO `orders` (`id`, `username`, `user_id`, `total_amount`, `order_date`, `status`) VALUES
(1, 'john_doe', 2, 3500.00, '2026-01-15 10:30:00', 'completed'),
(2, 'admin', 1, 2500.00, '2026-01-20 14:15:00', 'completed'),
(3, 'jane_smith', 3, 5400.00, '2026-01-25 09:45:00', 'processing'),
(4, 'test_user', 4, 1350.00, '2026-01-28 16:20:00', 'pending');

-- --------------------------------------------------------
-- Table structure for table `order_items`
-- --------------------------------------------------------

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `product_name` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `subtotal` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`),
  FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert sample order items
INSERT INTO `order_items` (`order_id`, `product_id`, `product_name`, `price`, `quantity`, `subtotal`) VALUES
(1, 1, 'Laptop Pro 15', 3500.00, 1, 3500.00),
(2, 2, 'Smartphone X1', 2500.00, 1, 2500.00),
(3, 1, 'Laptop Pro 15', 3500.00, 1, 3500.00),
(3, 5, 'Tablet Ultra', 1899.00, 1, 1899.00),
(4, 3, 'Wireless Headphones', 450.00, 2, 900.00),
(4, 3, 'Wireless Headphones', 450.00, 1, 450.00);

-- --------------------------------------------------------
-- Table structure for table `comments`
-- VULNERABILITY: XSS through unescaped comments
-- --------------------------------------------------------

CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `comment` text NOT NULL,  -- Stored without sanitization
  `rating` int(1) DEFAULT 5,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert sample comments (some with XSS payloads for testing)
INSERT INTO `comments` (`product_id`, `username`, `comment`, `rating`) VALUES
(1, 'john_doe', 'Excellent laptop! Very fast and reliable.', 5),
(1, 'jane_smith', 'Great performance for the price.', 4),
(2, 'test_user', 'Love this phone! Camera is amazing.', 5),
(3, 'demo', 'Best headphones I have ever owned. Worth every penny!', 5);

-- --------------------------------------------------------
-- Table structure for table `sessions`
-- VULNERABILITY: Session data stored in database
-- --------------------------------------------------------

CREATE TABLE `sessions` (
  `id` varchar(128) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `data` text,
  `last_activity` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table structure for table `logs`
-- --------------------------------------------------------

CREATE TABLE `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `description` text,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table structure for table `api_keys`
-- VULNERABILITY: API keys visible in database
-- --------------------------------------------------------

CREATE TABLE `api_keys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `api_key` varchar(64) NOT NULL,
  `api_secret` varchar(64) NOT NULL,
  `permissions` varchar(255) DEFAULT 'read',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `expires_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `api_key` (`api_key`),
  KEY `user_id` (`user_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert sample API key
INSERT INTO `api_keys` (`user_id`, `api_key`, `api_secret`, `permissions`) VALUES
(1, 'technovation_2026_key_12345', 'secret_key_admin_access', 'read,write,delete');

-- --------------------------------------------------------
-- Auto-increment settings
-- --------------------------------------------------------

ALTER TABLE `users` AUTO_INCREMENT=6;
ALTER TABLE `products` AUTO_INCREMENT=9;
ALTER TABLE `orders` AUTO_INCREMENT=5;
ALTER TABLE `order_items` AUTO_INCREMENT=7;
ALTER TABLE `comments` AUTO_INCREMENT=5;
ALTER TABLE `logs` AUTO_INCREMENT=1;
ALTER TABLE `api_keys` AUTO_INCREMENT=2;

COMMIT;

-- --------------------------------------------------------
-- Intentional Database Vulnerabilities Summary:
-- --------------------------------------------------------
-- 1. Plain text password storage
-- 2. No input validation at database level
-- 3. Weak foreign key constraints
-- 4. Default admin credentials
-- 5. Exposed API keys
-- 6. No encryption for sensitive data
-- 7. Predictable primary keys
-- 8. No data sanitization
-- --------------------------------------------------------
