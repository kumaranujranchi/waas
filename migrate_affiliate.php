<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

echo "<h1>Affiliate System Migration</h1>";

try {
    $db = Database::getInstance();
    $conn = $db->getConnection();

    // 1. Create Affiliates Table
    $sql = "CREATE TABLE IF NOT EXISTS affiliates (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        referral_code VARCHAR(50) NOT NULL UNIQUE,
        commission_rate DECIMAL(5,2) DEFAULT 20.00,
        balance DECIMAL(10,2) DEFAULT 0.00,
        status ENUM('pending', 'active', 'rejected') DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

    $conn->exec($sql);
    echo "<p style='color:green'>✅ Table 'affiliates' created/checked.</p>";

    // 2. Create Referrals Table
    $sql = "CREATE TABLE IF NOT EXISTS referrals (
        id INT AUTO_INCREMENT PRIMARY KEY,
        affiliate_id INT NOT NULL,
        user_id INT NOT NULL,
        status ENUM('pending', 'approved', 'paid', 'rejected') DEFAULT 'pending',
        commission_amount DECIMAL(10,2) DEFAULT 0.00,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (affiliate_id) REFERENCES affiliates(id) ON DELETE CASCADE,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

    $conn->exec($sql);
    echo "<p style='color:green'>✅ Table 'referrals' created/checked.</p>";

    // 3. Update Users Table (Add referred_by)
    // Check if column exists first
    $stmt = $conn->query("SHOW COLUMNS FROM users LIKE 'referred_by'");
    $exists = $stmt->fetch();

    if (!$exists) {
        $sql = "ALTER TABLE users ADD COLUMN referred_by INT NULL DEFAULT NULL AFTER role";
        $conn->exec($sql);
        echo "<p style='color:green'>✅ Column 'referred_by' added to 'users' table.</p>";

        // Add Foreign Key safely
        // Note: We don't enforce FK here strictly to avoid issues if affiliate is deleted but user remains, 
        // but normally we should. Let's keep it loose for now or add SET NULL.
        try {
            $conn->exec("ALTER TABLE users ADD CONSTRAINT fk_user_referral FOREIGN KEY (referred_by) REFERENCES affiliates(id) ON DELETE SET NULL");
            echo "<p style='color:green'>✅ Foreign Key 'fk_user_referral' added.</p>";
        } catch (Exception $e) {
            echo "<p style='color:orange'>⚠️ Could not add Foreign Key (might already exist or data mismatch).</p>";
        }

    } else {
        echo "<p style='color:blue'>ℹ️ Column 'referred_by' already exists.</p>";
    }

    echo "<h2>Migration Complete! 🚀</h2>";
    echo "<p>You can now delete this file.</p>";

} catch (Exception $e) {
    echo "<h2 style='color:red'>Migration Failed! ❌</h2>";
    echo "<p>Error: " . $e->getMessage() . "</p>";
}
