-- =========================================================
-- Frost & Brew Database
-- Version: 1.0
-- Engine : MySQL 8+
-- =========================================================

CREATE DATABASE IF NOT EXISTS frost_brew
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE frost_brew;

-- =========================================================
-- Roles
-- =========================================================

CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO roles (name) VALUES
('Super Admin'),
('Admin'),
('Manager'),
('Cashier'),
('Customer');

-- =========================================================
-- Users
-- =========================================================

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role_id INT NOT NULL DEFAULT 5,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) DEFAULT NULL,
    username VARCHAR(100) UNIQUE,
    email VARCHAR(150) UNIQUE NOT NULL,
    phone VARCHAR(30),
    password VARCHAR(255) NOT NULL,
    profile_photo VARCHAR(255) DEFAULT NULL,
    gender ENUM('Male','Female','Other') DEFAULT NULL,
    date_of_birth DATE DEFAULT NULL,
    status ENUM('Active','Inactive','Blocked') DEFAULT 'Active',
    email_verified TINYINT(1) DEFAULT 0,
    remember_token VARCHAR(255) DEFAULT NULL,
    last_login DATETIME DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_users_role
    FOREIGN KEY (role_id)
    REFERENCES roles(id)
);

-- =========================================================
-- User Addresses
-- =========================================================

CREATE TABLE user_addresses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(50),
    address TEXT NOT NULL,
    city VARCHAR(100),
    province VARCHAR(100),
    postal_code VARCHAR(20),
    is_default TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_address_user
    FOREIGN KEY (user_id)
    REFERENCES users(id)
    ON DELETE CASCADE
);

-- =========================================================
-- Categories
-- =========================================================

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    parent_id INT DEFAULT NULL,
    name VARCHAR(150) NOT NULL,
    slug VARCHAR(180) UNIQUE,
    image VARCHAR(255),
    icon VARCHAR(100),
    description TEXT,
    sort_order INT DEFAULT 0,
    status TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_category_parent
    FOREIGN KEY (parent_id)
    REFERENCES categories(id)
    ON DELETE SET NULL
);

-- =========================================================
-- Products
-- =========================================================

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    name VARCHAR(200) NOT NULL,
    slug VARCHAR(220) UNIQUE,
    sku VARCHAR(100) UNIQUE,
    short_description TEXT,
    description LONGTEXT,
    image VARCHAR(255),
    price DECIMAL(10,2) NOT NULL,
    sale_price DECIMAL(10,2) DEFAULT NULL,
    cost_price DECIMAL(10,2) DEFAULT NULL,
    stock INT DEFAULT 0,
    minimum_stock INT DEFAULT 5,
    featured TINYINT(1) DEFAULT 0,
    popular TINYINT(1) DEFAULT 0,
    status TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_product_category
    FOREIGN KEY (category_id)
    REFERENCES categories(id)
);

-- =========================================================
-- Product Images
-- =========================================================

CREATE TABLE product_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    image VARCHAR(255) NOT NULL,
    sort_order INT DEFAULT 0,

    CONSTRAINT fk_product_image
    FOREIGN KEY (product_id)
    REFERENCES products(id)
    ON DELETE CASCADE
);
-- =========================================================
-- Product Sizes
-- =========================================================

CREATE TABLE product_sizes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    price_adjustment DECIMAL(10,2) DEFAULT 0.00,
    status TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO product_sizes (name, price_adjustment) VALUES
('Small',0),
('Medium',100),
('Large',200);

-- =========================================================
-- Sugar Levels
-- =========================================================

CREATE TABLE sugar_levels (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    percentage INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO sugar_levels (name,percentage) VALUES
('No Sugar',0),
('25% Sugar',25),
('50% Sugar',50),
('75% Sugar',75),
('100% Sugar',100);

-- =========================================================
-- Ice Levels
-- =========================================================

CREATE TABLE ice_levels (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    percentage INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO ice_levels (name,percentage) VALUES
('No Ice',0),
('Light Ice',25),
('Normal Ice',50),
('Extra Ice',100);

-- =========================================================
-- Product Extras
-- =========================================================

CREATE TABLE extras (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    price DECIMAL(10,2) DEFAULT 0.00,
    image VARCHAR(255),
    status TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO extras (name,price) VALUES
('Extra Espresso Shot',150),
('Whipped Cream',120),
('Chocolate Syrup',100),
('Caramel Syrup',100),
('Vanilla Syrup',100),
('Hazelnut Syrup',120),
('Oat Milk',180),
('Almond Milk',220);

-- =========================================================
-- Product Variants
-- =========================================================

CREATE TABLE product_variants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    size_id INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    stock INT DEFAULT 0,
    sku VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_variant_product
        FOREIGN KEY(product_id)
        REFERENCES products(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_variant_size
        FOREIGN KEY(size_id)
        REFERENCES product_sizes(id)
        ON DELETE CASCADE
);

-- =========================================================
-- Product Extra Mapping
-- =========================================================

CREATE TABLE product_extra (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    extra_id INT NOT NULL,

    CONSTRAINT fk_product_extra_product
        FOREIGN KEY(product_id)
        REFERENCES products(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_product_extra_extra
        FOREIGN KEY(extra_id)
        REFERENCES extras(id)
        ON DELETE CASCADE
);

-- =========================================================
-- Product Gallery
-- =========================================================

CREATE TABLE product_gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    image VARCHAR(255) NOT NULL,
    sort_order INT DEFAULT 0,

    CONSTRAINT fk_gallery_product
        FOREIGN KEY(product_id)
        REFERENCES products(id)
        ON DELETE CASCADE
);

-- =========================================================
-- Product Reviews
-- =========================================================

CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    user_id INT NOT NULL,
    rating TINYINT NOT NULL,
    review TEXT,
    status TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_review_product
        FOREIGN KEY(product_id)
        REFERENCES products(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_review_user
        FOREIGN KEY(user_id)
        REFERENCES users(id)
        ON DELETE CASCADE
);

-- =========================================================
-- Wishlist
-- =========================================================

CREATE TABLE wishlist (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_wishlist_user
        FOREIGN KEY(user_id)
        REFERENCES users(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_wishlist_product
        FOREIGN KEY(product_id)
        REFERENCES products(id)
        ON DELETE CASCADE
);

-- =========================================================
-- Shopping Cart
-- =========================================================

CREATE TABLE cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    variant_id INT DEFAULT NULL,
    sugar_level_id INT DEFAULT NULL,
    ice_level_id INT DEFAULT NULL,
    quantity INT DEFAULT 1,
    unit_price DECIMAL(10,2) NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_cart_user
        FOREIGN KEY(user_id)
        REFERENCES users(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_cart_product
        FOREIGN KEY(product_id)
        REFERENCES products(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_cart_variant
        FOREIGN KEY(variant_id)
        REFERENCES product_variants(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_cart_sugar
        FOREIGN KEY(sugar_level_id)
        REFERENCES sugar_levels(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_cart_ice
        FOREIGN KEY(ice_level_id)
        REFERENCES ice_levels(id)
        ON DELETE SET NULL
);
-- =========================================================
-- Coupons
-- =========================================================

CREATE TABLE coupons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL UNIQUE,
    title VARCHAR(150) NOT NULL,
    description TEXT,
    discount_type ENUM('Fixed','Percentage') NOT NULL,
    discount_value DECIMAL(10,2) NOT NULL,
    minimum_order DECIMAL(10,2) DEFAULT 0,
    maximum_discount DECIMAL(10,2) DEFAULT NULL,
    usage_limit INT DEFAULT NULL,
    used_count INT DEFAULT 0,
    start_date DATE,
    expiry_date DATE,
    status TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================================================
-- Orders
-- =========================================================

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_number VARCHAR(50) NOT NULL UNIQUE,
    user_id INT NOT NULL,

    address_id INT DEFAULT NULL,

    coupon_id INT DEFAULT NULL,

    subtotal DECIMAL(10,2) NOT NULL,

    discount DECIMAL(10,2) DEFAULT 0,

    delivery_charges DECIMAL(10,2) DEFAULT 0,

    tax DECIMAL(10,2) DEFAULT 0,

    grand_total DECIMAL(10,2) NOT NULL,

    payment_method ENUM(
        'Cash',
        'Card',
        'JazzCash',
        'EasyPaisa',
        'Bank Transfer'
    ) DEFAULT 'Cash',

    payment_status ENUM(
        'Pending',
        'Paid',
        'Failed',
        'Refunded'
    ) DEFAULT 'Pending',

    order_type ENUM(
        'Delivery',
        'Take Away',
        'Dine In'
    ) DEFAULT 'Delivery',

    order_status ENUM(
        'Pending',
        'Confirmed',
        'Preparing',
        'Ready',
        'Out For Delivery',
        'Completed',
        'Cancelled'
    ) DEFAULT 'Pending',

    notes TEXT,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_order_user
        FOREIGN KEY(user_id)
        REFERENCES users(id),

    CONSTRAINT fk_order_coupon
        FOREIGN KEY(coupon_id)
        REFERENCES coupons(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_order_address
        FOREIGN KEY(address_id)
        REFERENCES user_addresses(id)
        ON DELETE SET NULL
);

-- =========================================================
-- Order Items
-- =========================================================

CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,

    order_id INT NOT NULL,

    product_id INT NOT NULL,

    variant_id INT DEFAULT NULL,

    sugar_level_id INT DEFAULT NULL,

    ice_level_id INT DEFAULT NULL,

    quantity INT NOT NULL,

    unit_price DECIMAL(10,2) NOT NULL,

    total_price DECIMAL(10,2) NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_order_item_order
        FOREIGN KEY(order_id)
        REFERENCES orders(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_order_item_product
        FOREIGN KEY(product_id)
        REFERENCES products(id),

    CONSTRAINT fk_order_item_variant
        FOREIGN KEY(variant_id)
        REFERENCES product_variants(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_order_item_sugar
        FOREIGN KEY(sugar_level_id)
        REFERENCES sugar_levels(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_order_item_ice
        FOREIGN KEY(ice_level_id)
        REFERENCES ice_levels(id)
        ON DELETE SET NULL
);

-- =========================================================
-- Order Item Extras
-- =========================================================

CREATE TABLE order_item_extras (
    id INT AUTO_INCREMENT PRIMARY KEY,

    order_item_id INT NOT NULL,

    extra_id INT NOT NULL,

    extra_price DECIMAL(10,2) NOT NULL,

    CONSTRAINT fk_order_extra_item
        FOREIGN KEY(order_item_id)
        REFERENCES order_items(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_order_extra
        FOREIGN KEY(extra_id)
        REFERENCES extras(id)
        ON DELETE CASCADE
);

-- =========================================================
-- Payments
-- =========================================================

CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,

    order_id INT NOT NULL,

    transaction_id VARCHAR(150),

    payment_gateway VARCHAR(100),

    amount DECIMAL(10,2) NOT NULL,

    payment_status ENUM(
        'Pending',
        'Paid',
        'Failed',
        'Refunded'
    ) DEFAULT 'Pending',

    paid_at DATETIME DEFAULT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_payment_order
        FOREIGN KEY(order_id)
        REFERENCES orders(id)
        ON DELETE CASCADE
);

-- =========================================================
-- Coupon Usage
-- =========================================================

CREATE TABLE coupon_usage (
    id INT AUTO_INCREMENT PRIMARY KEY,

    coupon_id INT NOT NULL,

    user_id INT NOT NULL,

    order_id INT NOT NULL,

    discount_amount DECIMAL(10,2) NOT NULL,

    used_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_coupon_usage_coupon
        FOREIGN KEY(coupon_id)
        REFERENCES coupons(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_coupon_usage_user
        FOREIGN KEY(user_id)
        REFERENCES users(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_coupon_usage_order
        FOREIGN KEY(order_id)
        REFERENCES orders(id)
        ON DELETE CASCADE
);

-- =========================================================
-- Order Status History
-- =========================================================

CREATE TABLE order_status_history (
    id INT AUTO_INCREMENT PRIMARY KEY,

    order_id INT NOT NULL,

    status VARCHAR(100) NOT NULL,

    remarks TEXT,

    created_by INT DEFAULT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_history_order
        FOREIGN KEY(order_id)
        REFERENCES orders(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_history_user
        FOREIGN KEY(created_by)
        REFERENCES users(id)
        ON DELETE SET NULL
);
-- =========================================================
-- Website Settings
-- =========================================================

CREATE TABLE settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    site_name VARCHAR(150) NOT NULL,
    site_email VARCHAR(150),
    site_phone VARCHAR(50),
    whatsapp VARCHAR(50),
    address TEXT,
    facebook VARCHAR(255),
    instagram VARCHAR(255),
    tiktok VARCHAR(255),
    youtube VARCHAR(255),
    logo VARCHAR(255),
    favicon VARCHAR(255),
    currency VARCHAR(20) DEFAULT 'PKR',
    currency_symbol VARCHAR(10) DEFAULT 'Rs.',
    timezone VARCHAR(100) DEFAULT 'Asia/Karachi',
    maintenance_mode TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- =========================================================
-- Hero Sliders
-- =========================================================

CREATE TABLE sliders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    subtitle VARCHAR(255),
    description TEXT,
    button_text VARCHAR(100),
    button_link VARCHAR(255),
    image VARCHAR(255),
    sort_order INT DEFAULT 0,
    status TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================================================
-- Promotional Banners
-- =========================================================

CREATE TABLE banners (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    image VARCHAR(255),
    link VARCHAR(255),
    banner_position ENUM(
        'Home Top',
        'Home Middle',
        'Sidebar',
        'Menu',
        'Footer'
    ),
    status TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================================================
-- Contact Messages
-- =========================================================

CREATE TABLE contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150),
    email VARCHAR(150),
    phone VARCHAR(50),
    subject VARCHAR(200),
    message TEXT,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================================================
-- Newsletter
-- =========================================================

CREATE TABLE newsletter (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(150) UNIQUE,
    status TINYINT(1) DEFAULT 1,
    subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================================================
-- Notifications
-- =========================================================

CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT NULL,
    title VARCHAR(255),
    message TEXT,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_notification_user
        FOREIGN KEY(user_id)
        REFERENCES users(id)
        ON DELETE CASCADE
);

-- =========================================================
-- Activity Logs
-- =========================================================

CREATE TABLE activity_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT NULL,
    activity VARCHAR(255),
    ip_address VARCHAR(100),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_log_user
        FOREIGN KEY(user_id)
        REFERENCES users(id)
        ON DELETE SET NULL
);

-- =========================================================
-- Suppliers
-- =========================================================

CREATE TABLE suppliers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    company_name VARCHAR(200),
    contact_person VARCHAR(150),
    phone VARCHAR(50),
    email VARCHAR(150),
    address TEXT,
    status TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================================================
-- Inventory
-- =========================================================

CREATE TABLE inventory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT,
    supplier_id INT DEFAULT NULL,
    purchase_price DECIMAL(10,2),
    quantity INT DEFAULT 0,
    remaining_quantity INT DEFAULT 0,
    purchase_date DATE,

    CONSTRAINT fk_inventory_product
        FOREIGN KEY(product_id)
        REFERENCES products(id),

    CONSTRAINT fk_inventory_supplier
        FOREIGN KEY(supplier_id)
        REFERENCES suppliers(id)
        ON DELETE SET NULL
);

-- =========================================================
-- Purchase History
-- =========================================================

CREATE TABLE purchases (
    id INT AUTO_INCREMENT PRIMARY KEY,
    supplier_id INT,
    invoice_number VARCHAR(100),
    total_amount DECIMAL(10,2),
    purchase_date DATE,
    remarks TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_purchase_supplier
        FOREIGN KEY(supplier_id)
        REFERENCES suppliers(id)
);

-- =========================================================
-- Purchase Items
-- =========================================================

CREATE TABLE purchase_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    purchase_id INT,
    product_id INT,
    quantity INT,
    unit_price DECIMAL(10,2),
    total_price DECIMAL(10,2),

    CONSTRAINT fk_purchase_item_purchase
        FOREIGN KEY(purchase_id)
        REFERENCES purchases(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_purchase_item_product
        FOREIGN KEY(product_id)
        REFERENCES products(id)
);

-- =========================================================
-- Cafe Tables
-- =========================================================

CREATE TABLE cafe_tables (
    id INT AUTO_INCREMENT PRIMARY KEY,
    table_number VARCHAR(50),
    capacity INT,
    status ENUM(
        'Available',
        'Occupied',
        'Reserved'
    ) DEFAULT 'Available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================================================
-- Table Reservations
-- =========================================================

CREATE TABLE reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    table_id INT,
    reservation_date DATE,
    reservation_time TIME,
    guests INT DEFAULT 1,
    special_request TEXT,
    status ENUM(
        'Pending',
        'Confirmed',
        'Cancelled',
        'Completed'
    ) DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_reservation_user
        FOREIGN KEY(user_id)
        REFERENCES users(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_reservation_table
        FOREIGN KEY(table_id)
        REFERENCES cafe_tables(id)
        ON DELETE CASCADE
);
-- =========================================================
-- Cart Extras
-- =========================================================

CREATE TABLE cart_extras (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cart_id INT NOT NULL,
    extra_id INT NOT NULL,
    extra_price DECIMAL(10,2) NOT NULL,

    CONSTRAINT fk_cart_extra_cart
        FOREIGN KEY(cart_id)
        REFERENCES cart(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_cart_extra_item
        FOREIGN KEY(extra_id)
        REFERENCES extras(id)
        ON DELETE CASCADE
);