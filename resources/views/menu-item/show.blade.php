@extends('layouts.app')

@section('content')
    <h1>Menu Item Details</h1>

    <p><strong>Name:</strong> {{ $menuItem->name }}</p>
    <p><strong>Price:</strong> {{ $menuItem->price }}</p>
    <p><strong>Category:</strong> {{ $menuItem->category->name }}</p>
    <p><strong>Image:</strong>
        @if($menuItem->image)
            <img src="{{ asset('storage/' . $menuItem->image) }}" width="200">
        @endif
    </p>

    <h2>Attached Ingredients (Recipe)</h2>
    <table class="table">
        <thead><tr><th>Ingredient</th><th>Quantity Required</th><th>Actions</th></tr></thead>
        <tbody>
            @foreach($menuItem->ingredients as $ingredient)
                <tr>
                    <td>{{ $ingredient->name }} ({{ $ingredient->unit }})</td>
                    <td>{{ $ingredient->pivot->quantity_required }}</td>
                    <td>
                        <form action="{{ route('menu-items.detachIngredient', [$menuItem, $ingredient]) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-danger">Detach</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Attach Ingredient to Recipe</h2>
    <form action="{{ route('menu-items.attachIngredient', $menuItem) }}" method="POST">
        @csrf
        <div class="row mb-3">
            <div class="col">
                <label>Ingredient:</label>
                <select name="ingredient_id" class="form-control" required>
                    @foreach($ingredients as $ingredient)
                        <option value="{{ $ingredient->id }}">{{ $ingredient->name }} ({{ $ingredient->unit }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                <label>Quantity Required:</label>
                <input type="number" name="quantity_required" class="form-control" min="0" step="0.01" required>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Attach</button>
    </form>

    <a href="{{ route('menu-items.index') }}" class="btn btn-secondary">Back</a>
@endsection