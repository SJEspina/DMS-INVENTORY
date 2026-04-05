<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::query();

        if ($request->filled('search')) {
            $query->where('customer', 'like', '%' . $request->search . '%');
        }

        return Inertia::render('Orders/Index', [
            'orders' => $query->latest()->paginate(10)->withQueryString(),
            'filters' => $request->only('search'),
        ]);
    }

    public function create()
    {
        return Inertia::render('Orders/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'deadline' => 'nullable|date',
            'customer' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'product' => 'required|string|max:255',
            'qty' => 'required|integer|min:1',
            'status' => 'required|in:Pending,Ongoing,Done',
        ]);

        Order::create($validated);

        return redirect()->route('orders.index');
    }

    public function edit(Order $order)
    {
        return Inertia::render('Orders/Edit', [
            'order' => $order,
        ]);
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'deadline' => 'nullable|date',
            'customer' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'product' => 'required|string|max:255',
            'qty' => 'required|integer|min:1',
            'status' => 'required|in:Pending,Ongoing,Done',
        ]);

        $order->update($validated);

        return redirect()->route('orders.index');
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('orders.index');
    }
}