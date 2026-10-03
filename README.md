# PHP Inventory Management Demo

A small database-driven inventory management application built with **PHP 8** and **MySQL**.

This project demonstrates practical backend development skills including authentication, CRUD operations, SQL queries, validation, stock transactions, and inventory reporting.

## Features

- User login with password hashing
- Product management
- Product search
- Stock-in transactions
- Stock-out transactions
- Stock balance validation
- Low-stock monitoring
- Transaction history
- MySQL relational database
- Basic role-based access structure

## Technologies

- PHP 8+
- MySQL 8+
- HTML5
- CSS3
- SQL
- PDO

## Project Structure

```text
php-inventory-management-demo/
├── auth/
│   ├── login.php
│   └── logout.php
├── config/
│   ├── auth.php
│   └── database.php
├── products/
│   ├── add.php
│   ├── delete.php
│   ├── edit.php
│   └── index.php
├── transactions/
│   ├── stock_in.php
│   ├── stock_out.php
│   └── history.php
├── database/
│   └── database.sql
├── assets/
│   └── style.css
├── dashboard.php
└── index.php
```

## Setup

1. Install PHP 8+, MySQL and a local server such as XAMPP.
2. Copy the project into the web server directory.
3. Create a MySQL database named `php_inventory_demo`.
4. Import `database/database.sql`.
5. Update the database credentials in `config/database.php`.
6. Open the project through your local PHP server.

## Demo login

The SQL file creates a demo user:

- Username: `admin`
- Password: `password`

For a real deployment, change the demo password and database credentials.

## Note

This is a personal demonstration project created to showcase PHP and MySQL development skills. It is not intended as production-ready enterprise software.
