-- WaaS Marketplace Database Schema
-- MySQL 8.0+

-- Drop existing tables if they exist (for development)
DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS subscriptions;
DROP TABLE IF EXISTS consultations;
DROP TABLE IF EXISTS product_features;
DROP TABLE IF EXISTS pricing_plans;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;

-- Users table (customers and admins)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    role ENUM('customer', 'admin') DEFAULT 'customer',
    status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Categories table
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    icon VARCHAR(50),
    display_order INT DEFAULT 0,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_slug (slug),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Products/Services table
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    short_description TEXT,
    full_description TEXT,
    image_url VARCHAR(500),
    badge VARCHAR(50), -- 'Website', 'Software', 'New', etc.
    is_featured BOOLEAN DEFAULT FALSE,
    status ENUM('active', 'inactive', 'draft') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
    INDEX idx_slug (slug),
    INDEX idx_category (category_id),
    INDEX idx_featured (is_featured),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Pricing plans table (monthly, 6-month, yearly)
CREATE TABLE pricing_plans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    plan_name VARCHAR(100) NOT NULL, -- 'Starter', 'Professional', 'Enterprise'
    plan_type ENUM('monthly', 'semi_annual', 'yearly') NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    currency VARCHAR(3) DEFAULT 'USD',
    billing_cycle INT NOT NULL, -- 1 for monthly, 6 for semi-annual, 12 for yearly
    discount_percentage DECIMAL(5, 2) DEFAULT 0,
    is_popular BOOLEAN DEFAULT FALSE,
    features JSON, -- Store plan-specific features as JSON
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    INDEX idx_product (product_id),
    INDEX idx_type (plan_type),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Product features table
CREATE TABLE product_features (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    feature_icon VARCHAR(50),
    feature_title VARCHAR(255) NOT NULL,
    feature_description TEXT,
    display_order INT DEFAULT 0,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    INDEX idx_product (product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Product FAQs table
CREATE TABLE product_faqs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    question TEXT NOT NULL,
    answer TEXT NOT NULL,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    INDEX idx_product (product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Subscriptions table
CREATE TABLE subscriptions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    plan_id INT NOT NULL,
    subscription_status ENUM('active', 'cancelled', 'expired', 'pending') DEFAULT 'pending',
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    auto_renew BOOLEAN DEFAULT TRUE,
    payment_method VARCHAR(50), -- 'stripe', 'paypal', etc.
    stripe_subscription_id VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (plan_id) REFERENCES pricing_plans(id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_status (subscription_status),
    INDEX idx_dates (start_date, end_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Orders table
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    order_number VARCHAR(50) NOT NULL UNIQUE,
    total_amount DECIMAL(10, 2) NOT NULL,
    tax_amount DECIMAL(10, 2) DEFAULT 0,
    discount_amount DECIMAL(10, 2) DEFAULT 0,
    final_amount DECIMAL(10, 2) NOT NULL,
    currency VARCHAR(3) DEFAULT 'USD',
    payment_status ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending',
    payment_method VARCHAR(50),
    payment_id VARCHAR(255), -- Stripe/PayPal transaction ID
    billing_email VARCHAR(255),
    billing_name VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_order_number (order_number),
    INDEX idx_payment_status (payment_status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Order items table
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    plan_id INT NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    plan_name VARCHAR(100) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    quantity INT DEFAULT 1,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (plan_id) REFERENCES pricing_plans(id) ON DELETE CASCADE,
    INDEX idx_order (order_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Consultations table
CREATE TABLE consultations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    business_type VARCHAR(100),
    requirements TEXT,
    preferred_date DATE,
    preferred_time TIME,
    status ENUM('pending', 'confirmed', 'completed', 'cancelled') DEFAULT 'pending',
    admin_notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user (user_id),
    INDEX idx_status (status),
    INDEX idx_date (preferred_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default admin user (password: admin123 - hashed)
INSERT INTO users (email, password, full_name, role) VALUES 
('admin@waasmarketplace.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin User', 'admin');

-- Insert sample categories
INSERT INTO categories (name, slug, description, icon, display_order) VALUES
('E-commerce', 'ecommerce', 'Online stores and shopping platforms', 'shopping_cart', 1),
('SaaS Starters', 'saas-starters', 'Software as a Service starter kits', 'cloud', 2),
('Real Estate', 'real-estate', 'Property listing and management', 'home', 3),
('Professional Services', 'professional-services', 'Consulting, legal, and medical websites', 'business', 4),
('Ready Software', 'ready-software', 'Pre-built business software solutions', 'apps', 5);

-- Insert sample products
INSERT INTO products (category_id, name, slug, short_description, full_description, badge, is_featured) VALUES
(1, 'E-commerce Elite', 'ecommerce-elite', 'High-conversion store with advanced inventory and payments.', 'Scalable online stores for growing brands with enterprise-grade performance, native checkout, and seamless inventory management.', 'Website', TRUE),
(2, 'SaaS Starter Kit', 'saas-starter-kit', 'Complete subscription engine with auth and billing.', 'Launch your SaaS product with built-in authentication, subscription management, and billing integration.', 'Software', TRUE),
(3, 'Property Portal', 'property-portal', 'Dynamic real estate listings with map integration.', 'Professional real estate platform with advanced search, map integration, and lead management.', 'Website', TRUE),
(4, 'Pro Services', 'pro-services', 'Optimized for consulting, legal, and medical firms.', 'Professional service websites with appointment booking, client portals, and case management.', 'Website', FALSE);

-- Insert sample pricing plans
INSERT INTO pricing_plans (product_id, plan_name, plan_type, price, billing_cycle, features) VALUES
-- E-commerce Elite plans
(1, 'Starter', 'monthly', 49.00, 1, '["Up to 50 products", "Standard Checkout", "Community Support"]'),
(1, 'Professional', 'monthly', 129.00, 1, '["Unlimited products", "Advanced Abandoned Cart", "24/7 Priority Support", "Custom Domain Integration"]'),
(1, 'Enterprise', 'monthly', 399.00, 1, '["Dedicated Account Manager", "Custom API Access", "SLA Guarantee"]'),

-- SaaS Starter Kit plans
(2, 'Starter', 'monthly', 79.00, 1, '["Up to 100 users", "Basic Analytics", "Email Support"]'),
(2, 'Professional', 'monthly', 199.00, 1, '["Unlimited users", "Advanced Analytics", "Priority Support", "Custom Branding"]'),
(2, 'Enterprise', 'monthly', 499.00, 1, '["White Label", "Dedicated Infrastructure", "24/7 Phone Support"]');

-- Insert sample product features
INSERT INTO product_features (product_id, feature_icon, feature_title, feature_description, display_order) VALUES
(1, 'devices', 'Responsive Design', 'Perfectly optimized for mobile, tablet, and desktop shoppers.', 1),
(1, 'search_insights', 'SEO Optimization', 'Built-in tools to help your store rank higher on search engines.', 2),
(1, 'payments', 'Secure Payments', 'Integration with Stripe, PayPal, and major credit cards.', 3),
(1, 'inventory_2', 'Inventory Sync', 'Real-time stock tracking across all sales channels.', 4),
(1, 'bolt', 'Lightning Fast', 'Sub-second page loads to minimize cart abandonment.', 5),
(1, 'query_stats', 'Advanced Analytics', 'Detailed insights into customer behavior and sales.', 6);
