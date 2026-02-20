# 🍽️ Restaurant Inventory Management System

A Laravel-based Inventory Management System designed for restaurants to manage categories, menu items, ingredients, and automate stock deduction based on orders.

Built using the MVC architecture of Laravel, this project focuses on real-world backend logic such as stock handling, relationships, and transactional consistency.

---

## 🚀 Key Features

- Category management (CRUD)
- Menu item management with category mapping
- Ingredient & stock management
- Recipe system (menu item ↔ ingredients with quantity)
- Order management with multiple items
- Automatic stock deduction on order delivery
- Stock movement logging

---

## 🧠 How It Works

### 1. Menu & Recipe Linking
Each menu item is linked to multiple ingredients via a recipe system.

Example:
- Burger → Tomato (50g), Cheese (20g)

---

### 2. Order Processing
- Orders can contain multiple menu items
- Each item has a quantity

---

### 3. Stock Deduction Logic
When an order is marked as **delivered**:
- The system fetches recipe data
- Calculates required ingredients
- Deducts stock accordingly
- Prevents operation if stock is insufficient

---

### 4. Stock Tracking
Every stock change is recorded in a log system:
- Ingredient used
- Quantity deducted
- Related order reference

---

## 🧩 Core Modules

- Categories
- Menu Items
- Ingredients (Stock)
- Recipes (Pivot relationship)
- Orders
- Stock Movements (Logs)

---

## 🛠️ Tech Stack

- PHP
- Laravel
- MySQL
- Blade (templating)

---

## ⚙️ Setup

```bash
git clone <repo-url>
cd restaurant-inventory
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
