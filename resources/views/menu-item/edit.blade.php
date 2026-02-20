@extends('layouts.app')

@section('content')
    <h1>Edit Menu Item</h1>

    <form action="{{ route('menu-items.update', $menuItem) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Name:</label>
            <input type="text" name="name" value="{{ $menuItem->name }}" class="form-control">
        </div>

        <div class="mb-3">
            <label>Price:</label>
            <input type="number" name="price" value="{{ $menuItem->price }}" class="form-control" step="0.01">
        </div>

        <div class="mb-3">
            <label>Category:</label>
            <select name="category_id" class="form-control">
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ $menuItem->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Image (optional):</label>
            <input type="file" name="image" class="form-control">
            @if($menuItem->image)
                <img src="{{ asset('storage/' . $menuItem->image) }}" width="100" alt="Current Image">
            @endif
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
@endsection