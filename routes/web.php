<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StockMovementController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// ============================================================================
// PUBLIC ROUTES (No authentication required)
// ============================================================================

/**
 * Welcome/Landing Page
 * 
 * Displays the public landing page with authentication state-aware navigation.
 * - If logged out: Shows login/register buttons
 * - If logged in: Shows dashboard button
 */
Route::get('/', function () {
    return view('welcome');
});

/**
 * Authentication Routes
 * 
 * All authentication endpoints are public and handle user login/registration.
 * Route names follow convention: 'login' for primary auth, 'auth.xxx' for secondary
 */

// Display login form (GET request)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

// Handle login form submission (POST request)
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');

// Display registration form (GET request)
Route::get('/register', [AuthController::class, 'showRegister'])->name('auth.showRegister');

// Handle registration form submission (POST request)
Route::post('/register', [AuthController::class, 'register'])->name('auth.register');


// ============================================================================
// PROTECTED ROUTES (Require authentication)
// ============================================================================

/**
 * All routes within this group require users to be logged in.
 * Laravel middleware 'auth' checks for valid session before allowing access.
 * Unauthenticated users are redirected to login page.
 */
Route::middleware('auth')->group(function () {
    
    /**
     * Dashboard Route
     * 
     * Shows authenticated user's dashboard with quick access cards
     * to main features (categories, ingredients, menu items, orders).
     * Users see their name and a personalized welcome message.
     */
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    /**
     * Logout Route
     * 
     * Handles user logout. Clears session, invalidates tokens,
     * and redirects to home page with success message.
     */
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

    // ========================================================================
    // INVENTORY MANAGEMENT ROUTES
    // ========================================================================
    
    /**
     * Category Management
     * 
     * Full CRUD operations for food/drink categories.
     * Automatic route generation provides:
     * - GET    /categories          → index (list all)
     * - POST   /categories          → store (create)
     * - GET    /categories/create   → create (show form)
     * - GET    /categories/{id}     → show (view single)
     * - PUT    /categories/{id}     → update (apply changes)
     * - DELETE /categories/{id}     → destroy (delete)
     * - GET    /categories/{id}/edit → edit (show form)
     * 
     * Used to organize menu items by type (appetizers, mains, desserts, etc)
     */
    Route::resource('categories', CategoryController::class);

    /**
     * Menu Item Management
     * 
     * CRUD operations for menu items with category linking and recipes.
     * Includes additional routes for recipe attachment/detachment.
     * 
     * Standard resource routes:
     * - GET    /menu-items                           → index
     * - POST   /menu-items                           → store
     * - GET    /menu-items/create                    → create
     * - GET    /menu-items/{id}                      → show
     * - PUT    /menu-items/{id}                      → update
     * - DELETE /menu-items/{id}                      → destroy
     * - GET    /menu-items/{id}/edit                 → edit
     * 
     * Custom routes for recipe management:
     * - POST   /menu-items/{id}/attach-ingredient    → attachIngredient (link ingredient)
     * - DELETE /menu-items/{id}/detach-ingredient/{id} → detachIngredient (unlink ingredient)
     * 
     * Menu items are linked to ingredients via recipes which define
     * how much of each ingredient is needed to prepare the item.
     */
    Route::resource('menu-items', MenuItemController::class);
    Route::post('menu-items/{menuItem}/attach-ingredient', [MenuItemController::class, 'attachIngredient'])
        ->name('menu-items.attachIngredient');
    Route::delete('menu-items/{menuItem}/detach-ingredient/{ingredient}', [MenuItemController::class, 'detachIngredient'])
        ->name('menu-items.detachIngredient');

    /**
     * Ingredient & Stock Management
     * 
     * CRUD operations for managing ingredients and their stock levels.
     * Tracks current inventory and minimum stock thresholds.
     * 
     * Standard resource routes:
     * - GET    /ingredients                    → index
     * - POST   /ingredients                    → store
     * - GET    /ingredients/create             → create
     * - GET    /ingredients/{id}               → show
     * - PUT    /ingredients/{id}               → update
     * - DELETE /ingredients/{id}               → destroy
     * - GET    /ingredients/{id}/edit          → edit
     * 
     * Custom route:
     * - POST   /ingredients/{id}/update-stock  → updateStock (adjust inventory)
     * 
     * Stock levels are automatically deducted when orders are delivered
     * and manually adjusted via the updateStock endpoint.
     */
    Route::resource('ingredients', IngredientController::class);
    Route::post('ingredients/{ingredient}/update-stock', [IngredientController::class, 'updateStock'])
        ->name('ingredients.updateStock');

    /**
     * Order Management
     * 
     * CRUD operations for customer orders and delivery tracking.
     * Orders contain multiple menu items with quantities.
     * 
     * Standard resource routes:
     * - GET    /orders                    → index
     * - POST   /orders                    → store
     * - GET    /orders/create             → create
     * - GET    /orders/{id}               → show
     * - PUT    /orders/{id}               → update
     * - DELETE /orders/{id}               → destroy
     * - GET    /orders/{id}/edit          → edit
     * 
     * Custom route:
     * - POST   /orders/{id}/deliver       → deliver (mark as delivered & deduct stock)
     * 
     * When order is marked as delivered:
     * - System verifies sufficient ingredient stock
     * - Deducts required ingredients based on recipes
     * - Creates stock movement logs for audit trail
     * - Updates order status to 'delivered'
     */
    Route::resource('orders', OrderController::class);
    Route::post('orders/{order}/deliver', [OrderController::class, 'deliver'])
        ->name('orders.deliver');

    /**
     * Stock Movement History
     * 
     * Audit trail of all inventory changes (read-only)
     * 
     * Routes:
     * - GET /stock-movements         → index (view all movements)
     * - GET /stock-movements/{id}    → show (view specific movement)
     * 
     * Tracks:
     * - Which ingredient changed
     * - Quantity deducted
     * - Type of movement (deduction/manual_add)
     * - Related order (if applicable)
     * - Timestamp of change
     * 
     * Useful for inventory reconciliation and auditing.
     */
    Route::resource('stock-movements', StockMovementController::class)->only(['index', 'show']);

});
