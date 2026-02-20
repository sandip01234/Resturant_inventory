<?php

use Illuminate\Support\Facades\Route;
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

Route::get('/', function () {
    return view('welcome');
});

// Category Management
Route::resource('categories', CategoryController::class);

// Menu Management (includes recipe attachment)
Route::resource('menu-items', MenuItemController::class);
Route::post('menu-items/{menuItem}/attach-ingredient', [MenuItemController::class, 'attachIngredient'])->name('menu-items.attachIngredient');
Route::delete('menu-items/{menuItem}/detach-ingredient/{ingredient}', [MenuItemController::class, 'detachIngredient'])->name('menu-items.detachIngredient');

// Ingredient/Stock Management
Route::resource('ingredients', IngredientController::class);
Route::post('ingredients/{ingredient}/update-stock', [IngredientController::class, 'updateStock'])->name('ingredients.updateStock');

// Order/Delivery Integration
Route::resource('orders', OrderController::class);
Route::post('orders/{order}/deliver', [OrderController::class, 'deliver'])->name('orders.deliver');

// Stock Movement Logs (view-only for now)
Route::resource('stock-movements', StockMovementController::class)->only(['index', 'show']);