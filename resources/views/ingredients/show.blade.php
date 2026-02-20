@extends('layouts.app')

@section('content')
    <h1>Ingredient Details</h1>

    <p><strong>Name:</strong> {{ $ingredient->name }}</p>
    <p><strong>Unit:</strong> {{ $ingredient->unit }}</p>
    <p><strong>Current Stock:</strong> {{ $ingredient->current_stock }}</p>
    <p><strong>Minimum Stock:</strong> {{ $ingredient->minimum_stock }}</p>
    <p><strong>Status:</strong>
        @if($ingredient->current_stock < $ingredient->minimum_stock)
            <span class="badge bg-danger">Low Stock Alert!</span>
        @else
            <span class="badge bg-success">OK</span>
        @endif
    </p>

    <h2>Update Stock</h2>
    <form action="{{ route('ingredients.updateStock', $ingredient) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Add Quantity:</label>
            <input type="number" name="add_quantity" class="form-control" min="0" required>
        </div>
        <button type="submit" class="btn btn-primary">Add to Stock</button>
    </form>

    <a href="{{ route('ingredients.index') }}" class="btn btn-secondary">Back</a>
@endsection