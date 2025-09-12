# jeni create login and register and now bar 

# dev add style in both and build dashboard and loguot page 

# dev add pagese like service,order,about_us,etc.....

# sql


-- Database setup for Shyam Enterprise website
-- Run these queries in your MySQL database

-- Create users table (if not exists)
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create orders table
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `service_type` varchar(50) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `size` varchar(100) DEFAULT NULL,
  `description` text,
  `urgent_order` tinyint(1) DEFAULT 0,
  `order_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('pending','processing','completed','cancelled') DEFAULT 'pending',
  `admin_notes` text,
  `quote_amount` decimal(10,2) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create contact_messages table for contact form submissions
CREATE TABLE IF NOT EXISTS `contact_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `subject` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('unread','read','replied') DEFAULT 'unread',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create gallery table for showcasing work
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

-- Insert sample data for gallery categories
INSERT INTO `gallery` (`title`, `description`, `image_path`, `category`, `is_featured`) VALUES
('Flex Board Banner Sample', 'High-quality outdoor advertising banner', '/images/gallery/flexboard1.jpg', 'flexboard', 1),
('Vinyl Sticker Design', 'Custom vinyl stickers for branding', '/images/gallery/vinyl1.jpg', 'vinyl', 1),
('LED Board Display', 'Modern LED signage solution', '/images/gallery/led1.jpg', 'ledboard', 1),
('Business Card Design', 'Professional visiting card design', '/images/gallery/card1.jpg', 'visiting_card', 0);

-- Create admin_users table for backend management
CREATE TABLE IF NOT EXISTS `admin_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','manager') DEFAULT 'manager',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default admin user (password: admin123 - change this!)
INSERT INTO `admin_users` (`username`, `email`, `password`, `role`) VALUES
('admin', 'admin@shyamenterprise.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Create service_pricing table for managing service rates
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

-- Insert service pricing data
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