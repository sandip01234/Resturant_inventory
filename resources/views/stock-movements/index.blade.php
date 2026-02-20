@extends('layouts.app')

@section('content')
    <h1>Stock Movements</h1>
    <table class="table">
        <thead><tr><th>Date</th><th>Ingredient</th><th>Order ID</th><th>Type</th><th>Quantity Deducted</th><th>Actions</th></tr></thead>
        <tbody>
            @foreach($movements as $movement)
                <tr>
                    <td>{{ $movement->created_at->format('Y-m-d H:i:s') }}</td>
                    <td>{{ $movement->ingredient->name ?? 'N/A' }}</td>
                    <td>{{ $movement->order_id ?? 'N/A' }}</td>
                    <td>{{ $movement->type }}</td>
                    <td>{{ $movement->quantity_deducted }}</td>
                    <td>
                        <a href="{{ route('stock-movements.show', $movement) }}" class="btn btn-sm btn-info">View</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection