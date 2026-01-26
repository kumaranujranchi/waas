-- Migration: Update order_status column with granular stages
-- Run this if you have already added the column, or use this as the base definition.
ALTER TABLE `orders` MODIFY `order_status` ENUM('pending', 'requirements', 'development', 'deployment', 'completed', 'cancelled') DEFAULT 'pending';
