# steps of add database 

1. Install XAMPP on the new PC
2. Copy the entire jenish_sheladiya folder to C:\xampp\htdocs\
3. Start Apache and MySQL in XAMPP
4. Visit: http://localhost/jenish_sheladiya/install.php
5. Follow the on-screen instructions




















# Shyam Enterprise - Complete Setup Guide

## 🚀 Quick Setup for New PC

### Prerequisites
- XAMPP (Apache + MySQL + PHP)
- Web browser
- Text editor (optional)

### Step 1: Install XAMPP
1. Download XAMPP from https://www.apachefriends.org/
2. Install XAMPP in `C:\xampp\` (default location)
3. Start Apache and MySQL services from XAMPP Control Panel

### Step 2: Setup Database
1. Open phpMyAdmin: http://localhost/phpmyadmin
2. Create new database: `jenish_sheladiya`
3. Run the complete SQL script below

### Step 3: Deploy Files
1. Copy the entire `jenish_sheladiya` folder to `C:\xampp\htdocs\`
2. Access the website: http://localhost/jenish_sheladiya/


### Step 4: Initial Setup
1. Visit: http://localhost/jenish_sheladiya/setup_database.php
2. Visit: http://localhost/jenish_sheladiya/migrate_add_manager_role.php
3. Visit: http://localhost/jenish_sheladiya/migrate_add_order_image.php
4. Visit: http://localhost/jenish_sheladiya/migrate_payments.php

### Step 5: Admin Access
- **URL**: http://localhost/jenish_sheladiya/login.php
- **Username**: admin
- **Password**: admin123

---

## 📊 Complete Database Schema

### 1. Create Database
```sql
CREATE DATABASE IF NOT EXISTS `jenish_sheladiya` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `jenish_sheladiya`;
```

### 2. Users Table
```sql
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
```

### 3. Orders Table
```sql
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
```

### 4. Contact Messages Table
```sql
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
```

### 5. Payments Table
```sql
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
```

### 6. Insert Default Admin User
```sql
INSERT INTO `users` (`username`, `email`, `password`, `role`) VALUES
('admin', 'admin@shyamenterprise.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
```

---

## 🔧 Configuration

### Database Configuration
Edit `config.php` if needed:
```php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "jenish_sheladiya";
```

### File Permissions
- Ensure `uploads/` folder exists and is writable
- Check PHP file upload settings in php.ini

---

## 📁 File Structure
```
jenish_sheladiya/
├── config.php              # Database configuration
├── setup_database.php      # Initial database setup
├── quick_setup.php         # Quick setup script
├── migrate_*.php           # Database migration scripts
├── index.php              # Homepage
├── login.php              # Login page
├── register.php           # Registration page
├── dashboard.php          # User dashboard
├── admin_panel.php        # Admin panel
├── order.php              # Order form
├── services.php           # Services page
├── about.php              # About page
├── contact.php            # Contact page
├── payment_*.php          # Payment processing
├── css/                   # Stylesheets
└── uploads/               # File uploads directory
```

---

## 🚨 Important Notes

1. **Security**: Change default admin password after first login
2. **Database**: Always backup your database before making changes
3. **File Uploads**: Ensure uploads directory has proper permissions
4. **PHP Version**: Requires PHP 7.4+ with MySQLi extension
5. **Apache**: Enable mod_rewrite for clean URLs

---

## 🔍 Troubleshooting

### Common Issues:
1. **Database Connection Error**: Check XAMPP MySQL service is running
2. **File Upload Issues**: Check PHP upload settings and folder permissions
3. **Page Not Found**: Ensure Apache is running and files are in correct location
4. **Permission Denied**: Check file/folder permissions on Windows

### Quick Fixes:
- Restart XAMPP services
- Clear browser cache
- Check error logs in XAMPP control panel
- Verify database credentials in config.php

---

## 📞 Support
For technical support or questions, contact the development team.