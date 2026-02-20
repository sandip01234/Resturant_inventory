@extends('layouts.app')

@section('content')
    <h1>Order Details</h1>

    <p><strong>ID:</strong> {{ $order->id }}</p>
    <p><strong>Status:</strong> {{ $order->status }}</p>
    <p><strong>Total Amount:</strong> {{ $order->total_amount }}</p>

    <h2>Order Items</h2>
    <table class="table">
        <thead><tr><th>Menu Item</th><th>Quantity</th><th>Price</th><th>Subtotal</th></tr></thead>
        <tbody>
            @foreach($order->orderItems as $item)
                <tr>
                    <td>{{ $item->menuItem->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->price }}</td>
                    <td>{{ $item->price * $item->quantity }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if(in_array($order->status, ['pending', 'preparing']))
        <form action="{{ route('orders.deliver', $order) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-success">Deliver Order</button>
        </form>
    @endif

    <a href="{{ route('orders.index') }}" class="btn btn-secondary">Back</a>
@endsection