@extends('layouts.app')

@section('content')
    <h1>Ingredients</h1>
    <a href="{{ route('ingredients.create') }}" class="btn btn-primary">Create Ingredient</a>
    <table class="table">
        <thead><tr><th>Name</th><th>Unit</th><th>Current Stock</th><th>Minimum Stock</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
            @foreach($ingredients as $ingredient)
                <tr>
                    <td>{{ $ingredient->name }}</td>
                    <td>{{ $ingredient->unit }}</td>
                    <td>{{ $ingredient->current_stock }}</td>
                    <td>{{ $ingredient->minimum_stock }}</td>
                    <td>
                        @if($ingredient->current_stock < $ingredient->minimum_stock)
                            <span class="badge bg-danger">Low Stock Alert!</span>
                        @else
                            <span class="badge bg-success">OK</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('ingredients.show', $ingredient) }}" class="btn btn-sm btn-info">View</a>
                        <a href="{{ route('ingredients.edit', $ingredient) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('ingredients.destroy', $ingredient) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection