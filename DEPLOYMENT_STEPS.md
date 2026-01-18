# Quick Deployment Steps for Hostinger

## After Git Deploy Completes:

### Step 1: Create config.php

1. Go to File Manager → `config` folder
2. Copy `config.example.php`
3. Rename copy to `config.php`
4. Done! (Already has production settings)

### Step 2: Create database.php

1. In `config` folder, create new file: `database.php`
2. Paste this content:

```php
<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'u743570205_waas');
define('DB_USER', 'u743570205_waas');
define('DB_PASS', 'Anuj@2025@2026');
define('DB_CHARSET', 'utf8mb4');

define('DB_OPTIONS', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
]);
```

### Step 3: Update Admin Password (phpMyAdmin)

```sql
UPDATE users
SET password = '$2y$12$1w0Myg4EijpDczQ2lnf2KOodG/gTQ5ESEFTkd6H5Sg9evLsAdLVCi'
WHERE email = 'admin@waas.com';
```

### Step 4: Add Product Images (Optional)

```sql
UPDATE products SET image_url = 'https://via.placeholder.com/400x300/14b8a6/ffffff?text=E-commerce' WHERE id = 1;
UPDATE products SET image_url = 'https://via.placeholder.com/400x300/22c55e/ffffff?text=SaaS+Starter' WHERE id = 2;
UPDATE products SET image_url = 'https://via.placeholder.com/400x300/06b6d4/ffffff?text=Property+Portal' WHERE id = 3;
UPDATE products SET image_url = 'https://via.placeholder.com/400x300/8b5cf6/ffffff?text=Pro+Services' WHERE id = 4;
```

### Step 5: Login

- URL: https://honestchoicereview.com/auth/login.php
- Email: admin@waas.com
- Password: Anuj@2025

### Step 6: Access Admin Panel

- URL: https://honestchoicereview.com/admin/

---

## That's it! 🚀

Total time: 2-3 minutes
