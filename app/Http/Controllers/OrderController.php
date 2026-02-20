<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\MenuItem;
use App\Http\Requests\StoreOrderRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\StockService;

class OrderController extends Controller
{
    protected $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    public function index()
    {
        $orders = Order::with('orderItems')->get();
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $menuItems = MenuItem::all();
        return view('orders.create', compact('menuItems'));
    }

    public function store(StoreOrderRequest $request)
    {
        $total = 0;
        DB::beginTransaction();
        try {
            $order = Order::create(['status' => 'pending', 'total_amount' => 0]);

            foreach ($request->items as $item) {
                $menuItem = MenuItem::findOrFail($item['menu_item_id']);
                $this->stockService->checkStockAvailability($menuItem, $item['quantity']);

                $order->orderItems()->create([
                    'menu_item_id' => $menuItem->id,
                    'quantity' => $item['quantity'],
                    'price' => $menuItem->price,
                ]);

                $total += $menuItem->price * $item['quantity'];
            }

            $order->update(['total_amount' => $total]);
            DB::commit();
            return redirect()->route('orders.index')->with('success', 'Order created.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function show(Order $order)
    {
        return view('orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        return view('orders.edit', compact('order'));
    }

    public function update(StoreOrderRequest $request, Order $order)
    {
        // Assuming update logic similar to store, but for simplicity, updating status or other fields
        $order->update($request->validated());
        return redirect()->route('orders.index')->with('success', 'Order updated.');
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Order deleted.');
    }

    public function deliver(Order $order)
    {
        if ($order->status === 'delivered') {
            return redirect()->back()->with('error', 'Already delivered.');
        }

        DB::beginTransaction();
        try {
            $this->stockService->deductStockForOrder($order);
            $order->update(['status' => 'delivered']);
            DB::commit();
            return redirect()->back()->with('success', 'Order delivered and stock deducted.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors($e->getMessage());
        }
    }
}