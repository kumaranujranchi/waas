-- Update product images with placeholder colors
-- Run this in phpMyAdmin

UPDATE products SET image_url = 'https://via.placeholder.com/400x300/14b8a6/ffffff?text=E-commerce' WHERE id = 1;
UPDATE products SET image_url = 'https://via.placeholder.com/400x300/22c55e/ffffff?text=SaaS+Starter' WHERE id = 2;
UPDATE products SET image_url = 'https://via.placeholder.com/400x300/06b6d4/ffffff?text=Property+Portal' WHERE id = 3;
UPDATE products SET image_url = 'https://via.placeholder.com/400x300/8b5cf6/ffffff?text=Pro+Services' WHERE id = 4;
