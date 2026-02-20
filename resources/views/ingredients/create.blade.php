@extends('layouts.app')

@section('content')
    <h1>Create Ingredient</h1>

    <form action="{{ route('ingredients.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Name:</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Unit (e.g., kg, gram, piece, liter):</label>
            <input type="text" name="unit" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Initial Stock Quantity:</label>
            <input type="number" name="current_stock" class="form-control" min="0" value="0">
        </div>

        <div class="mb-3">
            <label>Minimum Stock (for alerts):</label>
            <input type="number" name="minimum_stock" class="form-control" min="0" required>
        </div>

        <button type="submit" class="btn btn-success">Save</button>
    </form>
@endsection