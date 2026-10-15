DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','staff') NOT NULL DEFAULT 'staff'
);

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sku VARCHAR(50) NOT NULL UNIQUE,
    name VARCHAR(150) NOT NULL,
    category_id INT NULL,
    price DECIMAL(10,2) NOT NULL DEFAULT 0,
    quantity INT NOT NULL DEFAULT 0,
    reorder_level INT NOT NULL DEFAULT 10,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

INSERT INTO users (full_name, username, password, role) VALUES
('System Admin', 'admin', '$2y$12$9mKjq313otuFOdgTn.o18OKZR4IScTCxdVBjgAFzwdwYwR7yRg0IS', 'admin'),
('Demo Staff', 'staff', '$2y$12$ktDY7rTRa.8YzDOoJC1RbOGGLqlf0AMIAUrbq0kvJa6iZJfqI7kU2', 'staff');

INSERT INTO categories (name) VALUES ('Electronics'), ('Office Supplies'), ('Furniture');

INSERT INTO products (sku, name, category_id, price, quantity, reorder_level) VALUES
('ELC-001', 'USB-C Cable', 1, 199.00, 120, 30),
('ELC-002', 'Wireless Mouse', 1, 450.00, 8, 15),
('OFF-001', 'A4 Bond Paper', 2, 285.00, 60, 20),
('OFF-002', 'Ballpen (box of 50)', 2, 320.00, 0, 10),
('FUR-001', 'Office Chair', 3, 4850.00, 6, 3);
