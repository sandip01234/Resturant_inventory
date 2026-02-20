<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'unit', 'current_stock', 'minimum_stock'];

    public function menuItems()
    {
        return $this->belongsToMany(MenuItem::class, 'recipes')
                    ->withPivot('quantity_required');
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }
}
