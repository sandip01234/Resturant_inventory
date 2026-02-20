<?php

namespace App\Services;

use App\Models\Order;
use App\Models\MenuItem;
use App\Models\StockMovement;
use Exception;

class StockService
{
    public function checkStockAvailability(MenuItem $menuItem, int $quantity)
    {
        foreach ($menuItem->ingredients as $ingredient) {
            $required = $ingredient->pivot->quantity_required * $quantity;
            if ($ingredient->current_stock < $required) {
                throw new Exception("Insufficient stock for {$ingredient->name}.");
            }
        }
    }

    public function deductStockForOrder(Order $order)
    {
        foreach ($order->orderItems as $orderItem) {
            $menuItem = $orderItem->menuItem;
            foreach ($menuItem->ingredients as $ingredient) {
                $deduct = $ingredient->pivot->quantity_required * $orderItem->quantity;
                $ingredient->current_stock -= $deduct;
                $ingredient->save();

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