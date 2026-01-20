-- Table for Weekly Availability Settings
CREATE TABLE IF NOT EXISTS `consultation_availability` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `day_of_week` enum('mon','tue','wed','thu','fri','sat','sun') COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `start_time` time DEFAULT '09:00:00',
  `end_time` time DEFAULT '17:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `day_of_week` (`day_of_week`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default values (Mon-Fri, 9-5)
INSERT IGNORE INTO `consultation_availability` (`day_of_week`, `is_active`, `start_time`, `end_time`) VALUES
('mon', 1, '10:00:00', '18:00:00'),
('tue', 1, '10:00:00', '18:00:00'),
('wed', 1, '10:00:00', '18:00:00'),
('thu', 1, '10:00:00', '18:00:00'),
('fri', 1, '10:00:00', '18:00:00'),
('sat', 0, '10:00:00', '14:00:00'),
('sun', 0, '00:00:00', '00:00:00');

-- Table for Blocked Dates (Holidays, Leaves)
CREATE TABLE IF NOT EXISTS `consultation_blocked_dates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `date` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
