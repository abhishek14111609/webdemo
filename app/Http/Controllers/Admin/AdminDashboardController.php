<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Inquiry;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard with statistics and recent activities
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        // Get statistics
        $stats = [
            'totalUsers' => User::count(),
            'totalOrders' => Order::count(),
            'revenue' => Order::where('status', 'completed')->sum('total'),
            'pendingOrders' => Order::where('status', 'pending')->count(),
            'recentInquiries' => Inquiry::latest()->take(5)->get(),
        ];

        // Get recent orders for the recent orders section if needed
        $recentOrders = Order::with('user')
            ->latest()
            ->take(5)
            ->get();

        // Get recent users for the recent users section if needed
        $recentUsers = User::latest()
            ->take(5)
            ->get();

        // Get sales data for the last 30 days for charts if needed
        $salesData = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(total) as total_revenue')
            )
            ->where('created_at', '>=', now()->subDays(30))
            ->where('status', 'completed')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Prepare data for the chart if needed
        $chartData = [
            'labels' => $salesData->pluck('date')->map(function ($date) {
                return Carbon::parse($date)->format('M d');
            }),
            'orders' => $salesData->pluck('total_orders'),
            'revenue' => $salesData->pluck('total_revenue')
        ];

        return view('admin.dashboard', compact('stats', 'recentOrders', 'recentUsers', 'chartData'));
    }
}
