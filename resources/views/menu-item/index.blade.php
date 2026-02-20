@extends('layouts.app')

@section('content')
    <h1>Menu Items</h1>
    <a href="{{ route('menu-items.create') }}" class="btn btn-primary">Create Menu Item</a>
    <table class="table">
        <thead><tr><th>Name</th><th>Category</th><th>Price</th><th>Image</th><th>Actions</th></tr></thead>
        <tbody>
            @foreach($menuItems as $menuItem)
                <tr>
                    <td>{{ $menuItem->name }}</td>
                    <td>{{ $menuItem->category->name }}</td>
                    <td>{{ $menuItem->price }}</td>
                    <td>
                        @if($menuItem->image)
                            <img src="{{ asset('storage/' . $menuItem->image) }}" width="50">
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('menu-items.show', $menuItem) }}" class="btn btn-sm btn-info">View</a>
                        <a href="{{ route('menu-items.edit', $menuItem) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('menu-items.destroy', $menuItem) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection