-- Drop old weekly availability tables
DROP TABLE IF EXISTS `consultation_availability`;
DROP TABLE IF EXISTS `consultation_blocked_dates`;

-- Create new slot-based table
CREATE TABLE IF NOT EXISTS `consultation_slots` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `is_booked` tinyint(1) DEFAULT 0,
  `booking_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_slot` (`date`, `start_time`),
  KEY `date_index` (`date`),
  KEY `is_booked_index` (`is_booked`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
