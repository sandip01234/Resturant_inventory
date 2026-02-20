@extends('layouts.app')

@section('content')
    <h1>Stock Movement Details</h1>

    <p><strong>Date:</strong> {{ $stockMovement->created_at->format('Y-m-d H:i:s') }}</p>
    <p><strong>Ingredient:</strong> {{ $stockMovement->ingredient->name ?? 'N/A' }}</p>
    <p><strong>Order ID:</strong> {{ $stockMovement->order_id ?? 'N/A' }}</p>
    <p><strong>Type:</strong> {{ $stockMovement->type }}</p>
    <p><strong>Quantity Deducted:</strong> {{ $stockMovement->quantity_deducted }}</p>

    <a href="{{ route('stock-movements.index') }}" class="btn btn-secondary">Back</a>
@endsection