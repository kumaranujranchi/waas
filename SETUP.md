# WaaS Marketplace - Setup Guide

## Quick Start

### Step 1: Database Setup

1. Open phpMyAdmin or MySQL command line
2. Create a new database:
   ```sql
   CREATE DATABASE waas_marketplace;
   ```
3. Import the schema:
   - In phpMyAdmin: Import > Choose file > `database/schema.sql`
   - Or command line: `mysql -u root -p waas_marketplace < database/schema.sql`

### Step 2: Configure Database

Edit `config/database.php`:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'waas_marketplace');
define('DB_USER', 'root');          // Your MySQL username
define('DB_PASS', '');              // Your MySQL password
```

### Step 3: Configure Site URL

Edit `config/config.php`:

```php
define('SITE_URL', 'http://localhost/waas-marketplace'); // Update this
```

### Step 4: Access the Site

1. Open your browser
2. Navigate to: `http://localhost/waas-marketplace`
3. You should see the homepage!

## Default Login

**Admin Account:**

- Email: `admin@waasmarketplace.com`
- Password: `admin123`

**⚠️ Change this password immediately!**

## Testing the Application

### 1. Register a New User

- Click "Get Started"
- Fill in the registration form
- You'll be auto-logged in

### 2. Browse Products

- Homepage shows all products
- Click on any product card to see details

### 3. Purchase a Subscription

- On product detail page, select a plan
- Click "Buy Now"
- Complete checkout (currently auto-completes for demo)

### 4. View Dashboard

- After purchase, go to Dashboard
- See your active subscriptions and orders

### 5. Book a Consultation

- Click "Book Consultation" in navigation
- Fill in the form
- Submit booking request

## Common Issues

### "Database connection failed"

- Check MySQL is running
- Verify database credentials in `config/database.php`
- Make sure database `waas_marketplace` exists

### "Page not found"

- Check the SITE_URL in `config/config.php`
- Make sure you're accessing the correct URL

### "Headers already sent" error

- Check for any output before `<?php` tags
- Ensure no whitespace before `<?php` in files

## File Permissions

If you get permission errors:

```bash
chmod 755 config/
chmod 644 config/*.php
```

## Next Steps

1. ✅ Test user registration and login
2. ✅ Browse and search products
3. ✅ Test checkout process
4. ✅ View user dashboard
5. ✅ Submit consultation booking
6. 🔧 Integrate real payment gateway (Stripe/PayPal)
7. 🔧 Build admin panel for product management
8. 🔧 Add email notifications

## Need Help?

Check the main README.md for detailed documentation.
