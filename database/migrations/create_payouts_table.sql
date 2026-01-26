-- Migration: Create affiliate_payouts table
CREATE TABLE IF NOT EXISTS `affiliate_payouts` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `affiliate_id` INT NOT NULL,
    `amount` DECIMAL(10, 2) NOT NULL,
    `status` ENUM('pending', 'paid', 'cancelled') DEFAULT 'paid',
    `payment_method` VARCHAR(50) DEFAULT 'manual',
    `transaction_id` VARCHAR(255) NULL,
    `admin_notes` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`affiliate_id`) REFERENCES `affiliates`(`id`) ON DELETE CASCADE,
    INDEX idx_affiliate (`affiliate_id`),
    INDEX idx_status (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
