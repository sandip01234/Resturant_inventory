@extends('layouts.app')

@section('content')
    <h1>Edit Ingredient</h1>

    <form action="{{ route('ingredients.update', $ingredient) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Name:</label>
            <input type="text" name="name" value="{{ $ingredient->name }}" class="form-control">
        </div>

        <div class="mb-3">
            <label>Unit:</label>
            <input type="text" name="unit" value="{{ $ingredient->unit }}" class="form-control">
        </div>

        <div class="mb-3">
            <label>Minimum Stock:</label>
            <input type="number" name="minimum_stock" value="{{ $ingredient->minimum_stock }}" class="form-control" min="0">
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
@endsection