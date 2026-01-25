<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/classes/Database.php';

$db = Database::getInstance();

try {
    $sql = "ALTER TABLE referrals ADD COLUMN source VARCHAR(50) DEFAULT NULL AFTER status";
    $db->query($sql);
    echo "Successfully added 'source' column to referrals table.";
} catch (Exception $e) {
    echo "Error (Column might already exist): " . $e->getMessage();
}
