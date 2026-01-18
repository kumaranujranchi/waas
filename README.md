# WaaS Marketplace - PHP & MySQL

A modern SaaS marketplace platform built with PHP and MySQL, featuring subscription-based service offerings, user authentication, and consultation booking.

## Features

- 🎨 **Modern UI** - Beautiful TailwindCSS design with dark mode support
- 🔐 **User Authentication** - Secure login/registration system
- 📦 **Product Management** - Dynamic product listings with categories
- 💳 **Subscription System** - Multiple pricing plans (monthly, 6-month, yearly)
- 📅 **Consultation Booking** - Schedule expert consultations
- 👤 **User Dashboard** - Manage subscriptions and view orders
- 🔍 **Search & Filter** - Find products easily
- 📱 **Responsive Design** - Works on all devices

## Requirements

- PHP 8.0 or higher
- MySQL 8.0 or higher
- Apache/Nginx web server
- Composer (optional, for dependencies)

## Installation

### 1. Clone/Download the Project

Place the project files in your web server's document root (e.g., `htdocs`, `www`, or `public_html`).

### 2. Create Database

Create a new MySQL database:

```sql
CREATE DATABASE waas_marketplace CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 3. Import Database Schema

Import the database schema:

```bash
mysql -u your_username -p waas_marketplace < database/schema.sql
```

Or use phpMyAdmin to import `database/schema.sql`.

### 4. Configure Database Connection

Edit `config/database.php` and update with your database credentials:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'waas_marketplace');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
```

### 5. Configure Application Settings

Edit `config/config.php` and update:

```php
define('SITE_URL', 'http://localhost/waas-marketplace'); // Your site URL
define('SITE_EMAIL', 'your-email@example.com');
```

### 6. Set Permissions

Make sure the web server has write permissions for uploads (if needed):

```bash
chmod 755 uploads/
```

## Default Admin Credentials

- **Email**: admin@waasmarketplace.com
- **Password**: admin123

**⚠️ IMPORTANT**: Change the admin password immediately after first login!

## Project Structure

```
waas-marketplace/
├── config/              # Configuration files
│   ├── config.php       # Main application config
│   └── database.php     # Database connection config
├── database/            # Database files
│   └── schema.sql       # Database schema
├── classes/             # Core classes
│   └── Database.php     # Database wrapper class
├── models/              # Data models
│   ├── Product.php
│   ├── User.php
│   ├── Subscription.php
│   ├── Order.php
│   └── Consultation.php
├── includes/            # Shared components
│   ├── header.php       # Header component
│   ├── footer.php       # Footer component
│   └── functions.php    # Helper functions
├── auth/                # Authentication pages
│   ├── login.php
│   ├── register.php
│   └── logout.php
├── dashboard/           # User dashboard
│   └── index.php
├── assets/              # Static assets
│   ├── css/
│   ├── js/
│   └── images/
├── index.php            # Homepage
├── product-detail.php   # Product details page
├── checkout.php         # Checkout page
└── consultation.php     # Consultation booking
```

## Usage

### For Users

1. **Browse Products**: Visit the homepage to see available solutions
2. **Register**: Create an account to purchase subscriptions
3. **Select Plan**: Choose a product and pricing plan
4. **Checkout**: Complete the purchase
5. **Dashboard**: Manage your subscriptions

### For Administrators

1. **Login**: Use admin credentials
2. **Manage Products**: Add/edit/delete products (admin panel - to be built)
3. **View Orders**: Monitor all orders and subscriptions
4. **Manage Consultations**: Review and schedule consultation requests

## Database Tables

- `users` - User accounts (customers and admins)
- `categories` - Product categories
- `products` - Services/solutions offered
- `pricing_plans` - Subscription pricing tiers
- `product_features` - Product feature descriptions
- `subscriptions` - Active user subscriptions
- `orders` - Payment transactions
- `order_items` - Order line items
- `consultations` - Consultation booking requests

## Security Features

- Password hashing with bcrypt
- SQL injection protection via PDO prepared statements
- XSS protection with input sanitization
- CSRF token support (helper functions included)
- Session-based authentication

## Customization

### Adding New Products

1. Login as admin
2. Insert products directly into database (admin panel coming soon):

```sql
INSERT INTO products (category_id, name, slug, short_description, full_description, badge, is_featured)
VALUES (1, 'Your Product', 'your-product', 'Short desc', 'Full desc', 'Website', 1);
```

### Changing Colors

Edit the Tailwind config in `includes/header.php`:

```javascript
colors: {
    "primary": "#5048e5",  // Change this
    "accent-green": "#10b981",  // And this
}
```

## Payment Integration

The checkout currently creates orders automatically (for demo purposes). To integrate real payments:

1. **Stripe**: Uncomment Stripe code in `checkout.php`
2. **PayPal**: Add PayPal SDK and update `checkout.php`
3. Update `config/config.php` with your API keys

## Email Notifications

To enable email notifications:

1. Update SMTP settings in `config/config.php`
2. Use the `sendEmail()` helper function (requires PHPMailer)

## Troubleshooting

### Database Connection Error

- Check database credentials in `config/database.php`
- Ensure MySQL service is running
- Verify database exists

### Page Not Found (404)

- Check `.htaccess` file (if using Apache)
- Ensure mod_rewrite is enabled
- Verify file paths are correct

### Session Issues

- Check PHP session configuration
- Ensure `session_start()` is called
- Clear browser cookies

## Development

### Debug Mode

Enable debug mode in `config/config.php`:

```php
define('DEBUG_MODE', true);
```

**⚠️ Disable in production!**

## License

This project is open-source. Feel free to modify and use for your projects.

## Support

For issues or questions:

- Check the code comments
- Review the database schema
- Contact: info@waasmarketplace.com

## Roadmap

- [ ] Admin panel for product management
- [ ] Payment gateway integration (Stripe/PayPal)
- [ ] Email notifications
- [ ] Invoice generation
- [ ] Advanced analytics
- [ ] API endpoints
- [ ] Multi-language support

---

**Built with ❤️ using PHP, MySQL, and TailwindCSS**
