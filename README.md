# Inventory Management System

Simple inventory system: PHP (MySQLi) + MySQL + Bootstrap 5 + JavaScript.

**Live URL:** https://firstname-lastname.infinityfreeapp.com  *(replace)*

## Test Credentials
| Role  | Username | Password  |
|-------|----------|-----------|
| Admin | admin    | Admin@123 |
| Staff | staff    | Staff@123 |

## Features
- Register / Login / Logout (password_hash, sessions)
- Roles: **admin** (everything) and **staff** (view/add/edit products)
- Products: Create, Read, Update, Delete + search
- Categories and Users management (admin only)
- Dashboard with totals and low-stock list
- Client-side (HTML5 + JS) and server-side (PHP) validation
- SQL injection protection (prepared statements) and XSS protection (htmlspecialchars)

## Setup
1. Create a MySQL database and import `database/schema.sql` using phpMyAdmin.
2. Open `db.php` and change the host, username, password, and database name.
3. Upload all files to your server (`htdocs` on InfinityFree) or `htdocs` on XAMPP.
4. Open the site and log in.

## Files
| File | Purpose |
|---|---|
| db.php | Database connection and helper functions |
| index.php / register.php / logout.php | Authentication |
| dashboard.php | Summary page |
| products.php, product_form.php, product_delete.php | Product CRUD |
| categories.php | Category CRUD (admin) |
| users.php | User management (admin) |
| header.php / footer.php | Shared layout |
| css/style.css | Shared theme and component styles |
| css/layout2.css | Sidebar app shell and centered login card |
| js/app.js | Form validation, password toggle/strength, live search |
| database/schema.sql | Database tables + sample data |
