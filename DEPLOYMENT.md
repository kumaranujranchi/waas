# Deployment Guide - WaaS Marketplace

## Production Server Details

- **Domain**: https://honestchoicereview.com
- **Database Name**: u743570205_waas
- **Database User**: u743570205_waas
- **Database Password**: Anuj@2025@2026

## Deployment Steps

### 1. Upload Files to Server

Upload all project files to your hosting server's public_html or www directory using:

- FTP/SFTP client (FileZilla, Cyberduck)
- cPanel File Manager
- Git deployment

**Files to upload:**

```
├── config/
├── database/
├── classes/
├── models/
├── includes/
├── auth/
├── dashboard/
├── assets/
├── index.php
├── product-detail.php
├── checkout.php
├── consultation.php
└── README.md
```

### 2. Import Database

**Option A: Using phpMyAdmin**

1. Login to cPanel
2. Open phpMyAdmin
3. Select database: `u743570205_waas`
4. Click "Import" tab
5. Choose file: `database/schema.sql`
6. Click "Go"

**Option B: Using MySQL Command Line**

```bash
mysql -u u743570205_waas -p u743570205_waas < database/schema.sql
```

Enter password when prompted: `Anuj@2025@2026`

### 3. Verify Configuration Files

✅ **Already configured:**

- `config/database.php` - Production database credentials
- `config/config.php` - Site URL and settings

### 4. Set File Permissions

Using cPanel File Manager or FTP:

```
config/          → 755
config/*.php     → 644
includes/        → 755
includes/*.php   → 644
models/          → 755
models/*.php     → 644
classes/         → 755
classes/*.php    → 644
uploads/         → 755 (if exists)
```

### 5. Create .htaccess File

Create `.htaccess` in root directory:

```apache
# Enable URL Rewriting
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /

    # Force HTTPS
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

    # Remove .php extension
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME}\.php -f
    RewriteRule ^(.*)$ $1.php [L]
</IfModule>

# Security Headers
<IfModule mod_headers.c>
    Header set X-Content-Type-Options "nosniff"
    Header set X-Frame-Options "SAMEORIGIN"
    Header set X-XSS-Protection "1; mode=block"
</IfModule>

# Disable Directory Browsing
Options -Indexes

# Protect config files
<FilesMatch "^(config|database)\.php$">
    Order allow,deny
    Deny from all
</FilesMatch>
```

### 6. Test the Website

1. **Visit Homepage**: https://honestchoicereview.com
2. **Test Registration**: Create a new user account
3. **Test Login**: Login with created account
4. **Browse Products**: Check product listings
5. **Test Checkout**: Try purchasing a subscription
6. **Check Dashboard**: View user dashboard

### 7. Change Default Admin Password

**CRITICAL SECURITY STEP:**

1. Login with default credentials:
   - Email: `admin@waasmarketplace.com`
   - Password: `admin123`

2. Update admin password in database:

```sql
UPDATE users
SET password = '$2y$10$YOUR_NEW_HASHED_PASSWORD',
    email = 'your-admin-email@honestchoicereview.com'
WHERE role = 'admin';
```

Or create a new admin account and delete the default one.

### 8. SSL Certificate

Your hosting should provide free SSL (Let's Encrypt). If not:

1. Go to cPanel → SSL/TLS
2. Install SSL certificate
3. Force HTTPS (already in .htaccess above)

### 9. Email Configuration (Optional)

To enable email notifications, update in `config/config.php`:

```php
define('SMTP_HOST', 'mail.honestchoicereview.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'noreply@honestchoicereview.com');
define('SMTP_PASSWORD', 'your-email-password');
define('SMTP_ENCRYPTION', 'tls');
```

### 10. Backup Strategy

**Database Backup:**

- Use cPanel → Backup → Download Database Backup
- Or setup automated backups in cPanel

**File Backup:**

- Download entire directory periodically
- Use version control (Git)

## Post-Deployment Checklist

- [ ] Database imported successfully
- [ ] Homepage loads without errors
- [ ] User registration works
- [ ] User login works
- [ ] Products display correctly
- [ ] Checkout process works
- [ ] Dashboard accessible
- [ ] Consultation form submits
- [ ] Admin password changed
- [ ] SSL certificate active
- [ ] .htaccess configured
- [ ] File permissions set correctly

## Troubleshooting

### "Database connection failed"

- Check database credentials in `config/database.php`
- Verify database exists in cPanel → MySQL Databases
- Check if database user has privileges

### "500 Internal Server Error"

- Check `.htaccess` syntax
- Review error logs in cPanel → Error Log
- Verify PHP version (should be 8.0+)

### "Page not found"

- Check `SITE_URL` in `config/config.php`
- Verify `.htaccess` is uploaded
- Check mod_rewrite is enabled

### Images/CSS not loading

- Check `ASSETS_URL` in `config/config.php`
- Verify file permissions
- Clear browser cache

## Monitoring

**Check regularly:**

- Error logs: cPanel → Error Log
- Access logs: cPanel → Raw Access
- Database size: cPanel → MySQL Databases
- Disk usage: cPanel → Disk Usage

## Support

If you encounter issues:

1. Check error logs
2. Review this deployment guide
3. Verify all configuration settings
4. Test on local environment first

---

**Deployment Date**: <?php echo date('Y-m-d H:i:s'); ?>
**Deployed By**: Anuj Kumar
**Status**: Ready for Production ✅
