-- Fix database for admin profile
-- Run this in phpMyAdmin or MySQL

USE online_book_store;

-- Add phone column if not exists
ALTER TABLE users ADD COLUMN phone VARCHAR(20) AFTER password;

-- Add address column if not exists
ALTER TABLE users ADD COLUMN address TEXT AFTER phone;

-- Add created_at column if not exists
ALTER TABLE users ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP AFTER user_type;

-- Add role column if not exists
ALTER TABLE users ADD COLUMN role VARCHAR(20) DEFAULT 'user' AFTER user_type;

-- Update existing users to have created_at date
UPDATE users SET created_at = NOW() WHERE created_at IS NULL;

-- Set first user as admin (optional)
-- UPDATE users SET role = 'admin', user_type = 'admin' WHERE id = 1;
