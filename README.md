# 🍽️ Restaurant Inventory Management System

A comprehensive Laravel-based Inventory Management System designed for restaurants to manage categories, menu items, ingredients, and automate stock deduction based on orders. Features a complete authentication system with login/register functionality and protected routes.

Built using the MVC architecture of Laravel, this project focuses on real-world backend logic such as stock handling, relationships, transactional consistency, and user authentication.

---

## 📋 Table of Contents
- [Features](#-key-features)
- [Project Structure](#-project-structure)
- [Setup Instructions](#-setup-instructions)
- [Authentication System](#-authentication-system)
- [Database Schema](#-database-schema)
- [How It Works](#-how-it-works)
- [API Routes](#-api-routes)

---

## 🚀 Key Features

### Authentication & Authorization
- **User Registration** - Create new user accounts with email and password
- **User Login** - Secure login with session management
- **Protected Routes** - All inventory management routes require authentication
- **Logout Functionality** - Secure session termination
- **Password Hashing** - Bcrypt password encryption for security
- **Session Regeneration** - CSRF protection and session security

### Inventory Management
- **Category Management** - CRUD operations for food categories
- **Menu Item Management** - Create menu items with images and pricing
- **Ingredient & Stock Management** - Track ingredient inventory with stock levels
- **Recipe System** - Link menu items to ingredients with required quantities
- **Order Management** - Create and track customer orders
- **Stock Deduction** - Automatic ingredient deduction on order delivery
- **Stock Movement Logging** - Audit trail of all inventory changes

---

## 📁 Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php          # Authentication logic
│   │   ├── CategoryController.php      # Category CRUD
│   │   ├── IngredientController.php    # Ingredient CRUD & stock
│   │   ├── MenuItemController.php      # Menu item CRUD & recipes
│   │   ├── OrderController.php         # Order CRUD & delivery
│   │   └── StockMovementController.php # Stock logs
│   └── Requests/
│       ├── StoreCategoryRequest.php
│       ├── StoreIngredientRequest.php
│       ├── StoreMenuItemRequest.php
│       └── StoreOrderRequest.php
├── Models/
│   ├── User.php                 # User model with authentication
│   ├── Category.php
│   ├── Ingredient.php
│   ├── MenuItem.php
│   ├── Order.php
│   ├── OrderItem.php
│   ├── Recipe.php              # Pivot table for menu-ingredient
│   └── StockMovement.php
├── Services/
│   └── StockService.php        # Stock availability & deduction logic
└── Providers/
    └── AppServiceProvider.php

resources/views/
├── auth/
│   ├── login.blade.php         # Login form with gradient styling
│   └── register.blade.php      # Registration form
├── layouts/
│   ├── app.blade.php           # Main layout with navbar
│   └── navbar.blade.php        # Navigation menu (auth-aware)
├── dashboard.blade.php         # Authenticated user dashboard
├── welcome.blade.php           # Public landing page
└── [categories/ingredients/menu-item/orders/stock-movements]/
    └── [create/edit/index/show].blade.php

database/
├── migrations/                 # All table creation migrations
└── seeders/                    # Database seeders (if any)

routes/
└── web.php                     # All web routes with auth middleware
```

---

## ⚙️ Setup Instructions

### Prerequisites
- PHP 8.4+
- Composer
- MySQL/MariaDB
- Node.js & npm (for frontend assets)

### Installation Steps

1. **Clone the Repository**
```bash
git clone <repository-url>
cd restaurant-inventory
```

2. **Install Dependencies**
```bash
composer install
npm install
```

3. **Create Environment File**
```bash
cp .env.example .env
```

4. **Generate Application Key**
```bash
php artisan key:generate
```

5. **Configure Database** - Edit `.env` with your database credentials:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=restaurant_db
DB_USERNAME=root
DB_PASSWORD=your_password
```

6. **Run Migrations**
```bash
php artisan migrate
```

7. **Build Frontend Assets** (if needed)
```bash
npm run build
```

8. **Start Development Server**
```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

---

## 🔐 Authentication System

### How It Works

**Registration Flow:**
```
1. User visits /register
2. Fills in Name, Email, Password
3. System validates data (email unique, password confirmed)
4. User is created with hashed password
5. User is automatically logged in
6. Redirected to dashboard
```

**Login Flow:**
```
1. User visits /login
2. Enters email and password
3. System validates credentials against database
4. Session is created and regenerated for security
5. User redirected to dashboard or intended URL
6. Failed login shows error message
```

**Logout Flow:**
```
1. User clicks logout in navbar
2. Session is cleared and invalidated
3. User is logged out
4. Redirected to home page
```

### Authentication Routes
| Method | Route | Name | Authentication |
|--------|-------|------|-----------------|
| GET | `/login` | `login` | Public |
| POST | `/login` | `auth.login` | Public |
| GET | `/register` | `auth.showRegister` | Public |
| POST | `/register` | `auth.register` | Public |
| POST | `/logout` | `auth.logout` | Private |
| GET | `/dashboard` | `dashboard` | Private |

All other routes require authentication via `auth` middleware.

---

## 📊 Database Schema

### Users Table
```sql
- id (Primary Key)
- name (String)
- email (String, Unique)
- password (String, Hashed)
- email_verified_at (Timestamp, Nullable)
- remember_token (String, Nullable)
- created_at, updated_at (Timestamps)
```

### Categories Table
```sql
- id (Primary Key)
- name (String)
- description (Text, Nullable)
- created_at, updated_at (Timestamps)
```

### Ingredients Table
```sql
- id (Primary Key)
- name (String)
- unit (String)
- current_stock (Decimal)
- minimum_stock (Decimal)
- created_at, updated_at (Timestamps)
```

### Menu Items Table
```sql
- id (Primary Key)
- category_id (Foreign Key → Categories)
- name (String)
- price (Decimal)
- image (String, Nullable)
- created_at, updated_at (Timestamps)
```

### Recipes Table (Pivot)
```sql
- id (Primary Key)
- menu_item_id (Foreign Key → Menu Items)
- ingredient_id (Foreign Key → Ingredients)
- quantity_required (Decimal)
- created_at, updated_at (Timestamps)
```

### Orders Table
```sql
- id (Primary Key)
- status (Enum: pending, preparing, delivered)
- total_amount (Decimal)
- created_at, updated_at (Timestamps)
```

### Order Items Table
```sql
- id (Primary Key)
- order_id (Foreign Key → Orders)
- menu_item_id (Foreign Key → Menu Items)
- quantity (Integer)
- price (Decimal) - Snapshot at order time
- created_at, updated_at (Timestamps)
```

### Stock Movements Table
```sql
- id (Primary Key)
- ingredient_id (Foreign Key → Ingredients)
- quantity_deducted (Decimal)
- type (Enum: deduction, manual_add)
- order_id (Foreign Key → Orders, Nullable)
- created_at, updated_at (Timestamps)
```

---

## 🧠 How It Works

### 1. User Workflow
```
New User → Register → Login → Dashboard → Manage Inventory
```

### 2. Menu & Recipe Linking
Each menu item is linked to multiple ingredients via a recipe system with required quantities.

**Example:**
- Burger (Menu Item)
  - Tomato (50g)
  - Cheese (20g)
  - Bread (100g)

### 3. Order Processing
- Users create orders containing multiple menu items
- Each item in order has a quantity
- Order total is calculated from menu item prices

### 4. Stock Deduction Logic
When an order is marked as **delivered**:
```
1. System fetches recipe data for each menu item
2. Calculates required ingredients based on quantity
3. Checks if sufficient stock is available
4. If available: deducts stock and logs movement
5. If insufficient: throws exception, prevents operation
```

**Example:**
```
Order: 2 Burgers
→ Requires: Tomato (100g), Cheese (40g), Bread (200g)
→ Current Stock: Tomato (80g) ❌ INSUFFICIENT!
→ Operation blocked. User notified.
```

### 5. Stock Tracking
Every stock change creates an audit log:
- Which ingredient changed
- Quantity deducted
- Related order (if any)
- Timestamp
- Type of movement (deduction/manual_add)

---

## 🛣️ API Routes

### Public Routes
```
GET  /                    # Welcome page
GET  /login               # Login form
POST /login               # Login submission
GET  /register            # Register form
POST /register            # Register submission
```

### Protected Routes (Require Authentication)

**Dashboard**
```
GET /dashboard            # User dashboard
```

**Category Management**
```
GET    /categories                      # List all categories
POST   /categories                      # Create category
GET    /categories/create               # Create form
GET    /categories/{id}                 # View category
PUT    /categories/{id}                 # Update category
DELETE /categories/{id}                 # Delete category
GET    /categories/{id}/edit            # Edit form
```

**Ingredient Management**
```
GET    /ingredients                     # List all ingredients
POST   /ingredients                     # Create ingredient
GET    /ingredients/create              # Create form
GET    /ingredients/{id}                # View ingredient
PUT    /ingredients/{id}                # Update ingredient
DELETE /ingredients/{id}                # Delete ingredient
GET    /ingredients/{id}/edit           # Edit form
POST   /ingredients/{id}/update-stock   # Update stock level
```

**Menu Item Management**
```
GET    /menu-items                      # List all menu items
POST   /menu-items                      # Create menu item
GET    /menu-items/create               # Create form
GET    /menu-items/{id}                 # View menu item & recipes
PUT    /menu-items/{id}                 # Update menu item
DELETE /menu-items/{id}                 # Delete menu item
GET    /menu-items/{id}/edit            # Edit form
POST   /menu-items/{id}/attach-ingredient    # Link ingredient
DELETE /menu-items/{id}/detach-ingredient/{id} # Unlink ingredient
```

**Order Management**
```
GET    /orders                          # List all orders
POST   /orders                          # Create order
GET    /orders/create                   # Create form
GET    /orders/{id}                     # View order
PUT    /orders/{id}                     # Update order
DELETE /orders/{id}                     # Delete order
GET    /orders/{id}/edit                # Edit form
POST   /orders/{id}/deliver             # Mark as delivered
```

**Stock Movement Logs**
```
GET /stock-movements         # List all stock movements
GET /stock-movements/{id}    # View specific movement
```

**Authentication**
```
POST /logout                 # Logout user
```

---

## 🎨 UI/UX Features

- **Responsive Design** - Works on desktop, tablet, and mobile
- **Bootstrap 5** - Professional UI framework
- **Bootstrap Icons** - Consistent iconography
- **Gradient Styling** - Modern gradient backgrounds
- **Form Validation** - Client and server-side validation
- **Success/Error Messages** - User feedback on actions
- **Navigation Menu** - Auth-aware navbar with user dropdown
- **Dashboard** - Quick access cards for main features

---

## 🔒 Security Features

- **CSRF Protection** - All forms include CSRF tokens
- **Password Hashing** - Bcrypt encryption for passwords
- **Session Management** - Regenerated on login
- **Authentication Middleware** - Protected routes
- **Email Validation** - Unique email enforcement
- **Form Validation** - Server-side validation on all inputs
- **SQL Injection Prevention** - Eloquent ORM usage

---

## 📝 Form Validation Rules

### Registration
- **Name**: Required, string, max 255 characters
- **Email**: Required, valid email format, unique in database
- **Password**: Required, minimum 6 characters, confirmed

### Login
- **Email**: Required, valid email format
- **Password**: Required, minimum 6 characters

### Category
- **Name**: Required, string, max 255 characters
- **Description**: Optional, string, max 1000 characters

### Ingredient
- **Name**: Required, string, max 255 characters
- **Unit**: Required, string, max 100 characters
- **Current Stock**: Required, numeric, minimum 0
- **Minimum Stock**: Required, numeric, minimum 0

### Menu Item
- **Category**: Required, must exist in categories table
- **Name**: Required, string, max 255 characters
- **Price**: Required, numeric, minimum 0
- **Image**: Optional, must be image file

### Order
- **Items**: Required, array with items
- **Menu Item ID**: Required, must exist in menu_items table
- **Quantity**: Required, integer, minimum 1

---

## 🐛 Troubleshooting

**Database Connection Error**
```bash
# Check .env database configuration
php artisan config:clear
php artisan cache:clear
```

**Routes Not Found**
```bash
php artisan route:clear
php artisan cache:clear
```

**View Not Found**
```bash
php artisan view:clear
php artisan cache:clear
```

**Class Not Found (Services)**
```bash
composer dump-autoload
php artisan config:clear
```

---

## 📦 Technologies Used

- **Laravel 12** - PHP web framework
- **MySQL** - Database
- **Bootstrap 5** - CSS framework
- **Blade** - Template engine
- **Eloquent ORM** - Database abstraction
- **Composer** - Dependency manager

---

## 👨‍💻 Development Notes

### Key Classes

**AuthController** - Handles all authentication operations
- Registration with email validation
- Login with session management
- Logout with session clearing

**StockService** - Manages inventory operations
- Checks stock availability before orders
- Deducts stock on order delivery
- Creates movement logs for audit trail

**MenuItemController** - Menu and recipe management
- Links menu items to ingredients
- Manages menu item CRUD
- Handles recipe attachments

---

## 📄 License

This project is open source and available under the MIT License.

---

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

---

## 📞 Support

For issues and questions, please create an issue in the repository.

---

**Last Updated**: February 21, 2026

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
