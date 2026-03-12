# Book Business Website

## Introduction

This is an e-commerce website for selling books (BookLand). Customers can browse products by category, view product details, add items to cart, and complete checkout. The system also includes an admin area for product management.

## Main Features

### Customer

- Browse books by category (textbooks, novels, comics, business, science, and curriculum books)
- View product details
- Search books by name (with instant suggestions)
- Register, log in, and log out
- Manage shopping cart (add, view, remove)
- Complete payment for orders
- View order history
- Manage personal profile information

### Administrator

- Access admin area with admin privileges
- View product list
- Add new products
- Update product information
- Delete products
- Filter/view products by genre

## Technology Stack

### Backend

- PHP (plain PHP, no framework)
- MySQL / MariaDB
- MySQL Stored Procedures (e.g., `p_register`, `p_add_to_cart`, `p_add_books`, `p_up_book`, `p_del_book`, `p_view_gen_books`)

### Frontend

- HTML/CSS/JavaScript
- Fetch API (AJAX search)
- Font Awesome, Google Fonts

### Tools

- XAMPP/WAMP/Laragon (Apache + MySQL)
- phpMyAdmin (optional)

## Installation

### Requirements

- PHP 8.x (recommended)
- MySQL 5.7+ or equivalent MariaDB version
- Apache web server (or PHP built-in server for quick local testing)

### Setup Steps

```bash
# 1) Clone project
git clone https://github.com/<your-username>/the-book-store-web-page.git
cd the-book-store-web-page

# 2) Create database (example: bookstore)
# In MySQL client or phpMyAdmin:
# - Create DB: bookstore
# - Import schema + sample data + related stored procedures

# 3) Update DB credentials in config/db_connection.php
# $servername = "localhost";
# $username   = "root";
# $password   = "...";
# $database   = "bookstore";

# 4) Run the project
# Option A: put source in htdocs/www and run Apache + MySQL
# Option B: quick run with PHP built-in server
php -S localhost:3000
```

Access URL: `http://localhost:3000/index.php`

> Note: Some paths in the project still reference the `Shop_project` directory. If you rename your project folder, update these paths in PHP/HTML files accordingly.

## Default Account Notes

- Admin access is identified by:
  - `username = admin`
  - `userRole = 1` in the `userInfos` table

> You can create your own test accounts directly in the database for local testing.

## Main Directory Structure

```text
├── assets/                 # CSS, fonts, images, UI assets
├── config/                 # DB connection, login/register/logout handlers
├── controller/             # Admin product management features (CRUD)
├── index.php               # Landing page with register/login
├── homepage.php            # Main page after user login
├── detailProduct.php       # Product detail page
├── cart.php                # Shopping cart
├── historyCart.php         # Order history
├── personalPage.php        # User profile page
├── admin.php               # Admin navigation page
├── addToCart.php           # Add-to-cart handler
└── payment.php             # Payment handler
```


## Auto Seed Database

Hệ thống sẽ tự động seed dữ liệu khi khởi tạo kết nối DB trong `config/db_connection.php`.

### Các file seed
- `config/auto_seed.php`: seed runner (tự nhận diện schema cũ/mới).
- `database/seeds/01_schema.sql`: tạo schema theo mô hình ERD bạn cung cấp (`BOOKS`, `GENRES`, `ORIGINS`, `NATIONS`, `PUBLISHERS`, `USERS`, `USER_ROLE`, `ORDERS`, `DETAIL_ORDERS`).
- `database/seeds/02_seed_data.sql`: dữ liệu mẫu idempotent (chạy nhiều lần không tạo trùng).

### Cơ chế hoạt động
- Nếu DB đang dùng **schema cũ** của project (`userinfos`, `books`, ...), hệ thống seed theo schema cũ và bổ sung stored procedures tương thích.
- Nếu không có schema cũ, hệ thống tự tạo + seed theo **schema ERD mới** từ file SQL.

> Muốn tắt auto-seed ở production: comment dòng `run_auto_seed($conn);` trong `config/db_connection.php`.

## License

MIT License
