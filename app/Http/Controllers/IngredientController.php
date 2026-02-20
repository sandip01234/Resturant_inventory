<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Http\Requests\StoreIngredientRequest;
use Illuminate\Http\Request;
use App\Models\StockMovement;

class IngredientController extends Controller
{
    public function index()
    {
        $ingredients = Ingredient::all();
        return view('ingredients.index', compact('ingredients'));
    }

    // ... Similar CRUD methods as Category ...


    public function create()
    {
        return view('ingredients.create');
    }

    public function store(StoreIngredientRequest $request)
    {
        Ingredient::create($request->validated());
        return redirect()->route('ingredients.index')->with('success', 'Ingredient created.');
    }

    public function show(Ingredient $ingredient)
    {
        return view('ingredients.show', compact('ingredient'));
    }

    public function edit(Ingredient $ingredient)
    {
        return view('ingredients.edit', compact('ingredient'));
    }

    public function update(StoreIngredientRequest $request, Ingredient $ingredient)
    {
        $ingredient->update($request->validated());
        return redirect()->route('ingredients.index')->with('success', 'Ingredient updated.');
    }

    public function destroy(Ingredient $ingredient)
    {
        $ingredient->delete();
        return redirect()->route('ingredients.index')->with('success', 'Ingredient deleted.');
    }
    public function updateStock(Request $request, Ingredient $ingredient)
    {
        $request->validate(['add_quantity' => 'required|numeric|min:0']);
        $ingredient->current_stock += $request->add_quantity;
        $ingredient->save();

        StockMovement::create([
            'ingredient_id' => $ingredient->id,
            'quantity_deducted' => -$request->add_quantity,  // Negative for addition
            'type' => 'manual_add',
        ]);

        return redirect()->back()->with('success', 'Stock updated.');
    }
}