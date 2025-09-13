-- =============================================
-- Shyam Enterprise Database Setup Script
-- Run this script in phpMyAdmin or MySQL command line
-- =============================================

-- Create database
CREATE DATABASE IF NOT EXISTS `jenish_sheladiya` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `jenish_sheladiya`;

-- =============================================
-- 1. USERS TABLE
-- =============================================
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','manager','admin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =============================================
-- 2. ORDERS TABLE
-- =============================================
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `service_type` varchar(50) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `size` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `reference_image` varchar(255) DEFAULT NULL,
  `urgent_order` tinyint(1) DEFAULT 0,
  `order_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('pending','in_progress','completed','cancelled') DEFAULT 'pending',
  `payment_status` enum('unpaid','paid','failed') DEFAULT 'unpaid',
  `amount` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =============================================
-- 3. CONTACT MESSAGES TABLE
-- =============================================
CREATE TABLE IF NOT EXISTS `contact_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('new','read','replied') DEFAULT 'new',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =============================================
-- 4. PAYMENTS TABLE
-- =============================================
CREATE TABLE IF NOT EXISTS `payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `method` varchar(50) DEFAULT 'card',
  `status` enum('success','failed') NOT NULL,
  `transaction_id` varchar(64) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `order_idx` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =============================================
-- 5. GALLERY TABLE (Optional)
-- =============================================
CREATE TABLE IF NOT EXISTS `gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `description` text,
  `image_path` varchar(255) NOT NULL,
  `category` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_featured` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =============================================
-- 6. SERVICE PRICING TABLE (Optional)
-- =============================================
CREATE TABLE IF NOT EXISTS `service_pricing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `service_code` varchar(50) NOT NULL,
  `service_name` varchar(200) NOT NULL,
  `base_price` decimal(10,2) DEFAULT NULL,
  `price_unit` varchar(50) DEFAULT 'per piece',
  `description` text,
  `is_active` tinyint(1) DEFAULT 1,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `service_code` (`service_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =============================================
-- 7. INSERT DEFAULT ADMIN USER
-- =============================================
INSERT INTO `users` (`username`, `email`, `password`, `role`) VALUES
('admin', 'admin@shyamenterprise.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- =============================================
-- 8. INSERT SAMPLE GALLERY DATA (Optional)
-- =============================================
INSERT INTO `gallery` (`title`, `description`, `image_path`, `category`, `is_featured`) VALUES
('Flex Board Banner Sample', 'High-quality outdoor advertising banner', '/images/gallery/flexboard1.jpg', 'flexboard', 1),
('Vinyl Sticker Design', 'Custom vinyl stickers for branding', '/images/gallery/vinyl1.jpg', 'vinyl', 1),
('LED Board Display', 'Modern LED signage solution', '/images/gallery/led1.jpg', 'ledboard', 1),
('Business Card Design', 'Professional visiting card design', '/images/gallery/card1.jpg', 'visiting_card', 0);

-- =============================================
-- 9. INSERT SERVICE PRICING DATA (Optional)
-- =============================================
INSERT INTO `service_pricing` (`service_code`, `service_name`, `base_price`, `price_unit`, `description`) VALUES
('flexboard', 'Flex Board Banner', 25.00, 'per sq ft', 'High-quality outdoor advertising banners'),
('vinyl', 'Vinyl Sticker Printing', 15.00, 'per sq ft', 'Durable vinyl stickers for branding'),
('oneway', 'One-Way Vision Print', 45.00, 'per sq ft', 'Window graphics with one-way visibility'),
('reflective', 'Reflective Vinyl Print', 60.00, 'per sq ft', 'High-visibility reflective materials'),
('ecohd', 'Eco HD Print', 35.00, 'per sq ft', 'Eco-friendly high-definition printing'),
('lighting', 'Lighting Board', 150.00, 'per sq ft', 'Illuminated signage solutions'),
('rollup', 'Roll-Up Standee', 800.00, 'per piece', 'Portable display stands'),
('canopy', 'Canopy', 2500.00, 'per piece', 'Branded outdoor canopies'),
('ledboard', 'L.E.D Board', 200.00, 'per sq ft', 'Digital LED display boards'),
('safety', 'Industrial Safety Sign Board', 40.00, 'per piece', 'Safety compliance signage'),
('acp', 'A.C.P Board', 80.00, 'per sq ft', 'Aluminum composite panel signage'),
('foam', 'Foam Board', 20.00, 'per sq ft', 'Lightweight foam board displays'),
('visiting_card', 'Visiting Cards', 2.00, 'per piece', 'Professional business cards'),
('letterhead', 'Letter Head', 5.00, 'per piece', 'Branded letterhead printing'),
('billbook', 'Bill Book', 50.00, 'per book', 'Custom bill books'),
('envelope', 'Envelope', 3.00, 'per piece', 'Branded envelope printing'),
('brochure', 'Brochure', 8.00, 'per piece', 'Marketing brochures'),
('pamphlet', 'Pamphlet', 1.50, 'per piece', 'Promotional pamphlets'),
('idcard', 'ID Card', 15.00, 'per piece', 'Employee/Student ID cards'),
('stickers', 'Stickers', 10.00, 'per sheet', 'Custom sticker printing'),
('invitation', 'Invitation Card', 12.00, 'per piece', 'Event invitation cards');

-- =============================================
-- SETUP COMPLETE
-- =============================================
-- Default Admin Login:
-- Username: admin
-- Password: admin123
-- 
-- Next Steps:
-- 1. Copy files to C:\xampp\htdocs\jenish_sheladiya\
-- 2. Start Apache and MySQL in XAMPP
-- 3. Visit: http://localhost/jenish_sheladiya/
-- 4. Login with admin credentials
-- 5. Change default password for security
