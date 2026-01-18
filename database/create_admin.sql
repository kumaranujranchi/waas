-- Create Admin User with correct password hash
-- Run this in phpMyAdmin

DELETE FROM users WHERE email = 'admin@waas.com';

INSERT INTO users (full_name, email, phone, password, role, status, created_at) 
VALUES (
    'Admin User',
    'admin@waas.com',
    '+91 9876543210',
    '$2y$12$1w0Myg4EijpDczQ2lnf2KOodG/gTQ5ESEFTkd6H5Sg9evLsAdLVCi',
    'admin',
    'active',
    NOW()
);
