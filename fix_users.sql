-- Fix existing users - set user_type where it's NULL
USE online_book_store;

-- Set user_type for all users where it's NULL or empty
UPDATE users SET user_type = 'user' WHERE user_type IS NULL OR user_type = '';

-- Set role for all users where it's NULL or empty  
UPDATE users SET role = 'user' WHERE role IS NULL OR role = '';

-- Ensure first user is admin (optional - remove if not needed)
-- UPDATE users SET user_type = 'admin', role = 'admin' WHERE id = 1;

SELECT 'Users fixed successfully!' as message;
SELECT id, name, email, user_type, role FROM users;
