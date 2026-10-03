CREATE DATABASE IF NOT EXISTS php_inventory_demo
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE php_inventory_demo;

CREATE TABLE users (
    user_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL DEFAULT 'Staff',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE products (
    product_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    sku_code VARCHAR(50) NOT NULL UNIQUE,
    product_name VARCHAR(150) NOT NULL,
    quantity_in_stock INT NOT NULL DEFAULT 0,
    reorder_level INT NOT NULL DEFAULT 5,
    unit_price DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE transactions (
    transaction_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    transaction_type ENUM('IN', 'OUT') NOT NULL,
    quantity INT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_transaction_product
        FOREIGN KEY (product_id) REFERENCES products(product_id)
        ON DELETE RESTRICT,
    CONSTRAINT fk_transaction_user
        FOREIGN KEY (user_id) REFERENCES users(user_id)
        ON DELETE RESTRICT
);

INSERT INTO users (username, password_hash, role)
VALUES (
    'admin',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC8jZqv9W0wXfF7H6r1e',
    'Admin'
);

INSERT INTO products
    (sku_code, product_name, quantity_in_stock, reorder_level, unit_price)
VALUES
    ('SKU-001', 'Laptop Computer', 15, 5, 85000.00),
    ('SKU-002', 'Wireless Mouse', 32, 10, 2500.00),
    ('SKU-003', 'Keyboard', 8, 5, 3500.00);
