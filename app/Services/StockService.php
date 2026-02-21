<?php

namespace App\Services;

use App\Models\Order;
use App\Models\MenuItem;
use App\Models\StockMovement;
use Exception;

/**
 * StockService - Manages inventory stock operations
 * 
 * Handles checking stock availability for menu items and deducting stock
 * from inventory when orders are placed. Maintains stock movement logs
 * for audit trail purposes.
 */
class StockService
{
    /**
     * Check if sufficient stock is available for a menu item
     * 
     * Verifies that all required ingredients have enough current stock
     * to fulfill the requested quantity of the menu item.
     * 
     * @param MenuItem $menuItem The menu item to check stock for
     * @param int $quantity The quantity of menu items requested
     * @throws Exception If any ingredient has insufficient stock
     */
    public function checkStockAvailability(MenuItem $menuItem, int $quantity)
    {
        foreach ($menuItem->ingredients as $ingredient) {
            // Calculate required quantity based on recipe requirements
            $required = $ingredient->pivot->quantity_required * $quantity;
            
            // Throw exception if stock is insufficient
            if ($ingredient->current_stock < $required) {
                throw new Exception("Insufficient stock for {$ingredient->name}.");
            }
        }
    }

    /**
     * Deduct ingredient stock for a completed order
     * 
     * Reduces ingredient stock levels based on order items and their
     * associated recipes, then creates stock movement records for
     * audit trail tracking.
     * 
     * @param Order $order The order to process stock deduction for
     */
    public function deductStockForOrder(Order $order)
    {
        foreach ($order->orderItems as $orderItem) {
            $menuItem = $orderItem->menuItem;
            
            // Process each ingredient in the menu item's recipe
            foreach ($menuItem->ingredients as $ingredient) {
                // Calculate total quantity to deduct
                $deduct = $ingredient->pivot->quantity_required * $orderItem->quantity;
                
                // Update ingredient stock
                $ingredient->current_stock -= $deduct;
                $ingredient->save();

                // Log stock movement for audit trail
                StockMovement::create([
                    'ingredient_id' => $ingredient->id,
                    'quantity_deducted' => $deduct,
                    'order_id' => $order->id,
                    'type' => 'deduction',
                ]);
            }
        }
    }
}
