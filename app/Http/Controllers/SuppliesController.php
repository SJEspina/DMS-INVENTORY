<?php

namespace App\Http\Controllers;

use App\Models\Supply;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SuppliesController extends Controller
{
    private function getStatus($stock)
    {
        if ($stock <= 0) {
            return 'Out of Stock';
        } elseif ($stock <= 5) {
            return 'Low Stock';
        }

        return 'In Stock';
    }

    public function index(Request $request)
    {
        $supplies = Supply::query()
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('category', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Supplies/Index', [
            'supplies' => $supplies,
            'filters' => [
                'search' => $request->search,
            ],
        ]);
    }

    public function create()
    {
        return Inertia::render('Supplies/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $validated['status'] = $this->getStatus($validated['stock']);

        Supply::create($validated);

        return redirect()->route('supplies.index');
    }

    public function edit(Supply $supply)
    {
        return Inertia::render('Supplies/Edit', [
            'supply' => $supply,
        ]);
    }

    public function update(Request $request, Supply $supply)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $validated['status'] = $this->getStatus($validated['stock']);

        $supply->update($validated);

        return redirect()->route('supplies.index');
    }

    public function destroy(Supply $supply)
    {
        $supply->delete();

        return redirect()->route('supplies.index');
    }
}