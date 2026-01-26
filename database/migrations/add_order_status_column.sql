-- Migration: Add order_status column to orders table
ALTER TABLE `orders` ADD `order_status` ENUM('pending', 'processing', 'completed', 'cancelled') DEFAULT 'pending' AFTER `payment_status`;
