<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\Category;
use App\Models\Ingredient;
use App\Http\Requests\StoreMenuItemRequest;
use Illuminate\Http\Request;

class MenuItemController extends Controller
{
    public function index()
    {
        $menuItems = MenuItem::with('category')->get();
        return view('menu-item.index', compact('menuItems'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('menu-item.create', compact('categories'));
    }

    public function store(StoreMenuItemRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('menu-images', 'public');
        }
        MenuItem::create($data);
        return redirect()->route('menu-items.index')->with('success', 'Menu item created.');
    }

    public function show(MenuItem $menuItem)
    {
        $ingredients = Ingredient::all(); // Assuming we need ingredients for attach in show
        return view('menu-item.show', compact('menuItem', 'ingredients'));
    }

    public function edit(MenuItem $menuItem)
    {
        $categories = Category::all();
        return view('menu-item.edit', compact('menuItem', 'categories'));
    }

    public function update(StoreMenuItemRequest $request, MenuItem $menuItem)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('menu-images', 'public');
        }
        $menuItem->update($data);
        return redirect()->route('menu-items.index')->with('success', 'Menu item updated.');
    }

    public function destroy(MenuItem $menuItem)
    {
        $menuItem->delete();
        return redirect()->route('menu-items.index')->with('success', 'Menu item deleted.');
    }

    public function attachIngredient(Request $request, MenuItem $menuItem)
    {
        $request->validate([
            'ingredient_id' => 'required|exists:ingredients,id',
            'quantity_required' => 'required|numeric|min:0',
        ]);
        $menuItem->ingredients()->attach($request->ingredient_id, ['quantity_required' => $request->quantity_required]);
        return redirect()->back()->with('success', 'Ingredient attached.');
    }

    public function detachIngredient(MenuItem $menuItem, Ingredient $ingredient)
    {
        $menuItem->ingredients()->detach($ingredient->id);
        return redirect()->back()->with('success', 'Ingredient detached.');
    }
}