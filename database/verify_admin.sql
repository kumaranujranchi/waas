-- Test if admin user exists and password is correct
-- Run this in phpMyAdmin to verify

SELECT 
    id,
    full_name,
    email,
    role,
    status,
    LEFT(password, 20) as password_hash_preview,
    created_at
FROM users 
WHERE email = 'admin@waas.com';

-- Also check if password verification would work
-- The password hash should start with: $2y$12$1w0Myg4EijpDczQ2lnf2KO
