@extends('layouts.app')

@section('content')
    <h1>Create Order</h1>

    <form action="{{ route('orders.store') }}" method="POST">
        @csrf

        <div id="items-container">
            <div class="row mb-3">
                <div class="col">
                    <label>Menu Item:</label>
                    <select name="items[0][menu_item_id]" class="form-control" required>
                        @foreach($menuItems as $menuItem)
                            <option value="{{ $menuItem->id }}">{{ $menuItem->name }} ({{ $menuItem->price }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col">
                    <label>Quantity:</label>
                    <input type="number" name="items[0][quantity]" class="form-control" min="1" required>
                </div>
            </div>
        </div>

        <button type="button" class="btn btn-secondary mb-3" onclick="addItem()">Add Another Item</button>

        <button type="submit" class="btn btn-success">Save Order</button>
    </form>

    <script>
        let itemIndex = 1;
        function addItem() {
            const container = document.getElementById('items-container');
            const html = `
                <div class="row mb-3">
                    <div class="col">
                        <label>Menu Item:</label>
                        <select name="items[${itemIndex}][menu_item_id]" class="form-control" required>
                            @foreach($menuItems as $menuItem)
                                <option value="{{ $menuItem->id }}">{{ $menuItem->name }} ({{ $menuItem->price }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col">
                        <label>Quantity:</label>
                        <input type="number" name="items[${itemIndex}][quantity]" class="form-control" min="1" required>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
            itemIndex++;
        }
    </script>
@endsection