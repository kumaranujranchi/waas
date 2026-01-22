# User Dashboard - Quick Start Guide

## Overview
Complete user dashboard system with professional layout, sidebar navigation, and account management features. When users log in, they're redirected to the dashboard instead of the home page where they can manage their account, subscriptions, orders, and billing.

## 🎯 Features at a Glance

### Dashboard Pages
1. **Dashboard Home** - Overview with stats cards and recent activity
2. **Subscriptions** - Manage active and inactive subscriptions
3. **Orders** - Complete order history with payment status
4. **Billing** - Billing summary, payment methods, and invoice history
5. **Profile Settings** - Edit personal information
6. **Account Settings** - Password management and account security

### Key Features
- ✅ No public header/footer - clean dashboard experience
- ✅ Professional sidebar navigation with user profile
- ✅ Dark mode support throughout
- ✅ Responsive mobile design
- ✅ Status badges with color coding
- ✅ Empty states with helpful CTAs
- ✅ Success/error messages
- ✅ Confirmation modals for destructive actions
- ✅ Google OAuth integration display
- ✅ Session security

## 📁 File Structure

```
dashboard/
├── index.php                          # Dashboard home page
├── subscriptions.php                  # Subscriptions management
├── orders.php                         # Order history
├── billing.php                        # Billing & payments
├── includes/
│   ├── header.php                    # Dashboard header with sidebar
│   └── footer.php                    # Dashboard footer
└── settings/
    ├── profile.php                   # Edit profile
    └── account.php                   # Account settings & security
```

## 🔧 Implementation Details

### Header Layout (`dashboard/includes/header.php`)
```
┌─────────────────────────────────────────────────┐
│ SIDEBAR (Fixed, w-64)   │ MAIN CONTENT AREA    │
│                         │                      │
│ ┌─────────────────────┐ │ ┌──────────────────┐│
│ │ Logo                │ │ │ Top Bar         ││
│ │ User Profile Card   │ │ │ Title + Toggle  ││
│ │                     │ │ │ + Profile Menu  ││
│ │ NAVIGATION:         │ │ └──────────────────┘│
│ │ • Dashboard         │ │                     │
│ │ • Subscriptions     │ │ Page Content        │
│ │ • Orders            │ │                     │
│ │ • Billing           │ │                     │
│ │ • Profile Settings  │ │                     │
│ │ • Account Settings  │ │                     │
│ │ • Logout            │ │                     │
│ └─────────────────────┘ │                     │
└─────────────────────────────────────────────────┘
```

### Sidebar Navigation
- **Logo** - Links to main site
- **User Profile Card** - Shows user avatar, name, email
- **Main Navigation** - Dashboard, Subscriptions, Orders, Billing
- **Settings** - Profile & Account settings
- **Logout** - Ends session

### Top Bar
- **Page Title** - Current page name
- **Dark Mode Toggle** - Switch between light/dark theme
- **Profile Dropdown** - Quick access to profile & logout

## 🚀 Usage

### When User Logs In
1. User logs in via `/auth/login.php` or Google OAuth
2. Session is created with `user_id`
3. User is redirected to `/dashboard/index.php`
4. Dashboard header loads with sidebar
5. User can navigate all dashboard sections

### Navigation Example
```php
// In any dashboard page
include __DIR__ . '/includes/header.php';  // Loads sidebar
// ... page content ...
include __DIR__ . '/includes/footer.php';  // Closes layout
```

### Active Page Highlighting
The sidebar automatically highlights the current page based on filename:
```php
<?php echo basename($_SERVER['PHP_SELF']) === 'index.php' ? 'bg-primary text-white' : '...'; ?>
```

## 📊 Dashboard Home Page

### Stats Cards
Three clickable cards showing:
1. **Active Subscriptions** (links to subscriptions page)
2. **Total Orders** (links to orders page)
3. **Total Spent** (links to billing page)

### Quick Overview
- **Active Subscriptions** - Shows first 4 subscriptions
- **Recent Orders** - Shows last 5 orders

## 📋 Subscriptions Page

### Features
- Tabbed interface (Active / Inactive)
- Card-based layout with details:
  - Product name & plan name
  - Pricing & billing cycle
  - Start date & next billing date
  - Days remaining (calculated)
  - View Details & Cancel buttons

### Status Indicators
- Green badge for active subscriptions
- Gray badge for inactive subscriptions

## 📦 Orders Page

### Features
- List view of all user orders
- Order cards with:
  - Order number & date
  - Status badge (pending, completed, failed)
  - Amount
  - Breakdown (subtotal, tax, discount, items)
  - Action buttons

### Status Indicators
- Yellow: Pending
- Green: Completed
- Red: Failed

## 💳 Billing Page

### Sections
1. **Billing Summary**
   - Total Spent (all time)
   - Completed Orders (count)
   - Average Order Value

2. **Payment Methods**
   - Add new payment method button
   - Display saved cards
   - Edit/Remove options

3. **Billing History**
   - Table with all transactions
   - Date, Invoice #, Description, Amount, Status
   - Download invoice for completed orders

## 👤 Profile Settings

### Left Panel
- User avatar with initial
- Full name
- Email (read-only)
- Account status
- Member since date

### Edit Form
- Full name (editable)
- Email (read-only)
- Phone number (editable)
- Save Changes button

## 🔐 Account Settings

### Sections
1. **Change Password** (hidden for Google OAuth users)
   - Current password verification
   - New password & confirmation
   - Password strength requirements (8+ chars)
   - Update button

2. **Connected Accounts**
   - Display Google Account status
   - Connect/Disconnect options

3. **Danger Zone**
   - Delete account button
   - Modal confirmation with type-DELETE verification
   - Warning about irreversible action

## 🎨 Styling

### Colors
- Primary: #6366f1 (Indigo)
- Accent Green: #10b981
- Background Light: #f8f7ff
- Background Dark: #0f0e1b

### Classes Used
- Tailwind CSS (grid, flexbox, spacing)
- Material Symbols for icons
- Custom transitions and hover effects

### Dark Mode
Implemented using Tailwind's `dark:` prefix
- Automatically toggles with button click
- Saved to localStorage
- Applied to all pages

## 🔐 Security Features

### Session Verification
```php
// Dashboard header checks:
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . baseUrl('auth/login.php'));
    exit;
}
```

### Password Verification
- Current password must be verified before changing
- Uses `password_verify()` for security

### Confirmation Modals
- Delete account requires typing "DELETE"
- Cancel subscription requires confirmation
- Change password requires current password

## 📱 Responsive Design

### Mobile Breakpoints
- **sm**: Stack sidebar on top
- **md**: Show sidebar beside content
- **lg**: Optimize layout for larger screens

### Mobile Navigation
- Sidebar remains accessible
- Top bar scales well
- Content areas responsive grid layout

## 🛠️ Database Methods Required

The following User model methods are needed:

```php
// In models/User.php:
updateProfile($userId, $fullName, $phone)
updatePassword($userId, $newPassword)
verifyPassword($plainPassword, $hashedPassword)
deleteUser($userId)
```

## 💡 Tips

1. **Customizing Colors**
   - Edit Tailwind color classes in files
   - Update primary color in config

2. **Adding New Dashboard Pages**
   - Copy header/footer includes
   - Include model files
   - Get user ID with `getCurrentUserId()`
   - Follow same layout structure

3. **Admin vs User Dashboard**
   - This is the user dashboard
   - Admin dashboard is in `/admin/` folder
   - Different header/layout for admins

4. **Testing**
   - Log in as test user
   - Visit each dashboard page
   - Test dark mode toggle
   - Test mobile responsiveness
   - Test empty states (no orders/subscriptions)

## 🚨 Important Notes

1. **Header Must Come First**
   - Always include dashboard header before content
   - Never include public header/footer in dashboard pages

2. **Footer Must Come Last**
   - Always include dashboard footer at end
   - Closes main content wrapper

3. **Session Required**
   - Dashboard pages check for `$_SESSION['user_id']`
   - If not found, redirects to login

4. **User Data Access**
   - Use `getCurrentUser()` to get user data
   - Use `getCurrentUserId()` to get user ID
   - These functions are defined in `includes/functions.php`

## 📚 Related Files

- `/auth/login.php` - User login page
- `/auth/register.php` - User registration
- `/auth/google_callback.php` - Google OAuth callback
- `/models/User.php` - User model
- `/models/Order.php` - Order model
- `/models/Subscription.php` - Subscription model
- `/includes/functions.php` - Helper functions

## ✅ Checklist

- [x] Dashboard layout created
- [x] Sidebar navigation implemented
- [x] All dashboard pages created
- [x] Dark mode support added
- [x] Responsive design implemented
- [x] Status indicators added
- [x] Empty states created
- [x] Form handling implemented
- [x] Security features added
- [x] Documentation completed

## 🎉 Ready to Deploy!

The user dashboard is fully implemented and ready to use. Simply ensure:
1. User model has required methods
2. Database tables are properly set up
3. Environment variables are configured
4. Test with actual user data

For any issues or customizations, refer to individual file comments.
