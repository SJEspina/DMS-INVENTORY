<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Supply;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class ReportController extends Controller
{
    public function index(): Response
    {
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // Today's sale
        $todaySale = Order::whereDate('created_at', $today)
            ->sum(DB::raw('price'));

        // Today's total orders
        $todayTotalOrders = Order::whereDate('created_at', $today)->count();

        // Monthly sales
        $monthlySales = Order::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum(DB::raw('price'));

        // Monthly expense from supplies
        $monthlyExpense = Supply::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum(DB::raw('price'));

        // Order statistics - last 7 days
        $orderStatistics = collect(range(6, 0))->map(function ($daysAgo) {
            $date = Carbon::today()->subDays($daysAgo);

            return [
                'label' => $date->format('d M'),
                'orders' => Order::whereDate('created_at', $date)->count(),
            ];
        });

        // Sales overview - last 7 days
        $salesOverview = collect(range(6, 0))->map(function ($daysAgo) {
            $date = Carbon::today()->subDays($daysAgo);

            return [
                'label' => $date->format('d M'),
                'sales' => (float) Order::whereDate('created_at', $date)
                    ->sum(DB::raw('price')),
            ];
        });

        return Inertia::render('Reports/Index', [
            'summary' => [
                'todaySale' => (float) $todaySale,
                'todayTotalOrders' => $todayTotalOrders,
                'monthlySales' => (float) $monthlySales,
                'monthlyExpense' => (float) $monthlyExpense,
            ],
            'orderStatistics' => $orderStatistics,
            'salesOverview' => $salesOverview,
        ]);
    }
}