-- Complete Database Fix for Admin Panel
-- Run this in phpMyAdmin

USE online_book_store;

-- ============================================
-- Fix Users Table
-- ============================================
ALTER TABLE users ADD COLUMN IF NOT EXISTS phone VARCHAR(20) AFTER password;
ALTER TABLE users ADD COLUMN IF NOT EXISTS address TEXT AFTER phone;
ALTER TABLE users ADD COLUMN IF NOT EXISTS created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE users ADD COLUMN IF NOT EXISTS role VARCHAR(20) DEFAULT 'user';

-- ============================================
-- Fix Books Table
-- ============================================
ALTER TABLE books ADD COLUMN IF NOT EXISTS stock INT DEFAULT 10;
ALTER TABLE books ADD COLUMN IF NOT EXISTS category_id INT DEFAULT NULL;

-- ============================================
-- Create Categories Table
-- ============================================
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default categories
INSERT IGNORE INTO categories (id, name, description) VALUES
(1, 'Fiction', 'Fiction books and novels'),
(2, 'Technology', 'Technology and programming books'),
(3, 'Science', 'Science related books'),
(4, 'History', 'History and historical books'),
(5, 'Other', 'Other categories');

-- ============================================
-- Create Orders Table
-- ============================================
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    status VARCHAR(20) DEFAULT 'pending',
    payment_method VARCHAR(50) DEFAULT 'cash',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create Order Items Table
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    book_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL
);

-- ============================================
-- Update existing data
-- ============================================
UPDATE users SET created_at = NOW() WHERE created_at IS NULL;
UPDATE books SET stock = 10 WHERE stock IS NULL;
UPDATE books SET category_id = 1 WHERE category_id IS NULL;

-- Set first user as admin
UPDATE users SET role = 'admin', user_type = 'admin' WHERE id = 1;

-- Insert sample orders for testing
INSERT IGNORE INTO orders (id, user_id, total_amount, status) VALUES
(1, 1, 2500.00, 'completed'),
(2, 1, 4200.00, 'pending'),
(3, 1, 1800.00, 'processing');

SELECT 'Database fix completed successfully!' as message;
