-- Complete Greenzio Database Setup
-- This will create all necessary tables and fresh user accounts
-- Run this in phpMyAdmin or MySQL command line

DROP DATABASE IF EXISTS `shop`;
CREATE DATABASE `shop` CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `shop`;

-- ===================== ADMIN TABLE =====================
CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_name` varchar(100) NOT NULL,
  `admin_email` varchar(100) NOT NULL UNIQUE,
  `admin_password` varchar(255) NOT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`admin_id`),
  INDEX `idx_admin_email` (`admin_email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Fresh Admin Accounts
INSERT INTO `admin` (`admin_name`, `admin_email`, `admin_password`) VALUES 
('Super Admin', 'admin@greenzio.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Store Manager', 'manager@greenzio.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Test Admin', 'test@admin.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- ===================== USERS TABLE =====================
CREATE TABLE `users` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `mobile` varchar(15) DEFAULT NULL,
  `gender` enum('m','f','other') DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`uid`),
  INDEX `idx_user_email` (`email`),
  INDEX `idx_user_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Fresh User Accounts (password: password123 for all)
INSERT INTO `users` (`email`, `password`, `mobile`, `gender`, `first_name`, `last_name`) VALUES 
('user1@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543210', 'm', 'John', 'Doe'),
('user2@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543211', 'f', 'Jane', 'Smith'),
('test@user.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543212', 'm', 'Test', 'User'),
('demo@customer.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543213', 'f', 'Demo', 'Customer');

-- ===================== PRODUCT TABLE =====================
CREATE TABLE `product` (
  `pid` int(11) NOT NULL AUTO_INCREMENT,
  `pname` varchar(200) NOT NULL,
  `category` varchar(100) NOT NULL,
  `subcategory` varchar(100) DEFAULT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `discount` decimal(5,2) DEFAULT 0.00,
  `weight` varchar(50) DEFAULT NULL,
  `unit` varchar(20) DEFAULT NULL,
  `stock_quantity` int(11) DEFAULT 0,
  `expiry_date` date DEFAULT NULL,
  `pimage` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pid`),
  INDEX `idx_product_category` (`category`),
  INDEX `idx_product_active` (`is_active`),
  INDEX `idx_product_stock` (`stock_quantity`),
  INDEX `idx_product_expiry` (`expiry_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Sample Products
INSERT INTO `product` (`pname`, `category`, `subcategory`, `brand`, `price`, `discount`, `weight`, `unit`, `stock_quantity`, `expiry_date`, `description`) VALUES 
('Organic Red Apples', 'Fruits & Vegetables', 'Fruits', 'Fresh Farm', 150.00, 10.00, '1', 'kg', 50, '2025-01-15', 'Fresh organic red apples from local farms'),
('Whole Milk', 'Dairy & Bakery', 'Dairy', 'Pure Dairy', 65.00, 5.00, '1', 'liter', 30, '2025-01-10', 'Fresh whole milk, rich in calcium'),
('Basmati Rice', 'Grains & Rice', 'Rice', 'Royal Brand', 120.00, 8.00, '5', 'kg', 100, NULL, 'Premium quality basmati rice'),
('Fresh Bananas', 'Fruits & Vegetables', 'Fruits', 'Tropical Fresh', 80.00, 0.00, '1', 'dozen', 75, '2025-01-12', 'Fresh ripe bananas'),
('Whole Wheat Bread', 'Dairy & Bakery', 'Bakery', 'Daily Bread', 45.00, 0.00, '400', 'grams', 25, '2025-01-08', 'Fresh whole wheat bread'),
('Chicken Breast', 'Meat & Seafood', 'Chicken', 'Fresh Meat Co', 280.00, 15.00, '1', 'kg', 20, '2025-01-07', 'Fresh chicken breast, premium cut'),
('Olive Oil', 'Oils & Ghee', 'Oil', 'Golden Drop', 350.00, 12.00, '500', 'ml', 40, NULL, 'Extra virgin olive oil'),
('Tomatoes', 'Fruits & Vegetables', 'Vegetables', 'Garden Fresh', 40.00, 0.00, '1', 'kg', 60, '2025-01-14', 'Fresh red tomatoes'),
('Greek Yogurt', 'Dairy & Bakery', 'Dairy', 'Creamy Delight', 85.00, 0.00, '200', 'grams', 35, '2025-01-11', 'Thick Greek yogurt'),
('Green Tea', 'Snacks & Beverages', 'Beverages', 'Healthy Brew', 180.00, 20.00, '100', 'grams', 45, NULL, 'Premium green tea leaves');

-- ===================== ORDERS TABLE =====================
CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `order_status` enum('pending','confirmed','shipped','delivered','cancelled') DEFAULT 'pending',
  `payment_status` enum('pending','paid','failed','refunded') DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT NULL,
  `delivery_address` text DEFAULT NULL,
  `order_date` timestamp DEFAULT CURRENT_TIMESTAMP,
  `delivery_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  PRIMARY KEY (`order_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`uid`) ON DELETE CASCADE,
  INDEX `idx_order_user` (`user_id`),
  INDEX `idx_order_status` (`order_status`),
  INDEX `idx_order_date` (`order_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ===================== ORDER DETAILS TABLE =====================
CREATE TABLE `order_details` (
  `order_detail_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `discount_amount` decimal(10,2) DEFAULT 0.00,
  `total_price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`order_detail_id`),
  FOREIGN KEY (`order_id`) REFERENCES `orders`(`order_id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `product`(`pid`) ON DELETE CASCADE,
  INDEX `idx_order_details_order` (`order_id`),
  INDEX `idx_order_details_product` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ===================== BILLING DETAILS TABLE =====================
CREATE TABLE `billing_details` (
  `bill_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `address_line1` varchar(255) NOT NULL,
  `address_line2` varchar(255) DEFAULT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `postal_code` varchar(10) NOT NULL,
  `country` varchar(100) DEFAULT 'India',
  `is_default` tinyint(1) DEFAULT 0,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`bill_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`uid`) ON DELETE CASCADE,
  INDEX `idx_billing_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ===================== CART TABLE =====================
CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `added_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cart_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`uid`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `product`(`pid`) ON DELETE CASCADE,
  UNIQUE KEY `unique_user_product` (`user_id`, `product_id`),
  INDEX `idx_cart_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ===================== CONTACT TABLE =====================
CREATE TABLE `contact` (
  `contact_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mobile` varchar(15) DEFAULT NULL,
  `subject` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `status` enum('new','read','replied','closed') DEFAULT 'new',
  `admin_reply` text DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`contact_id`),
  INDEX `idx_contact_status` (`status`),
  INDEX `idx_contact_date` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Sample Contact Messages
INSERT INTO `contact` (`name`, `email`, `mobile`, `subject`, `message`) VALUES 
('John Customer', 'john@example.com', '9876543210', 'Product Quality Inquiry', 'I would like to know more about the organic products you offer.'),
('Mary Shopper', 'mary@example.com', '9876543211', 'Delivery Time', 'What are your delivery timings for my area?'),
('Test User', 'test@contact.com', '9876543212', 'Website Issue', 'I am facing some issues with the checkout process.');

-- ===================== WISHLISHTS/FAVORITES (Optional) =====================
CREATE TABLE `wishlist` (
  `wishlist_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `added_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`wishlist_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`uid`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `product`(`pid`) ON DELETE CASCADE,
  UNIQUE KEY `unique_user_wishlist` (`user_id`, `product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ===================== COUPONS TABLE (Optional) =====================
CREATE TABLE `coupons` (
  `coupon_id` int(11) NOT NULL AUTO_INCREMENT,
  `coupon_code` varchar(50) NOT NULL UNIQUE,
  `discount_type` enum('percentage','fixed') NOT NULL,
  `discount_value` decimal(10,2) NOT NULL,
  `minimum_order` decimal(10,2) DEFAULT 0.00,
  `maximum_discount` decimal(10,2) DEFAULT NULL,
  `usage_limit` int(11) DEFAULT NULL,
  `used_count` int(11) DEFAULT 0,
  `valid_from` date NOT NULL,
  `valid_until` date NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`coupon_id`),
  INDEX `idx_coupon_code` (`coupon_code`),
  INDEX `idx_coupon_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Sample Coupons
INSERT INTO `coupons` (`coupon_code`, `discount_type`, `discount_value`, `minimum_order`, `valid_from`, `valid_until`) VALUES 
('WELCOME10', 'percentage', 10.00, 500.00, '2025-01-01', '2025-12-31'),
('FLAT50', 'fixed', 50.00, 300.00, '2025-01-01', '2025-06-30'),
('NEWUSER', 'percentage', 15.00, 200.00, '2025-01-01', '2025-03-31');

-- Display success message and account information
SELECT 'Database setup completed successfully!' AS status;
SELECT 'ADMIN ACCOUNTS' AS account_type, admin_email AS email, 'password123' AS password FROM admin
UNION ALL
SELECT 'USER ACCOUNTS' AS account_type, email AS email, 'password123' AS password FROM users;
