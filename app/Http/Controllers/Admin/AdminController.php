<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Inquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function dashboard()
    {
        try {
            $stats = [
                'totalUsers' => User::count(),
                'totalOrders' => Order::count(),
                'revenue' => Order::where('status', '!=', 'cancelled')->sum('total'),
                'pendingOrders' => Order::where('status', 'pending')->count(),
                'recentOrders' => Order::with('user')
                    ->latest()
                    ->take(5)
                    ->get(),
                'recentInquiries' => Inquiry::latest()
                    ->take(5)
                    ->get(),
                'totalProducts' => Product::count(),
            ];

            return view('admin.dashboard', compact('stats'));
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error loading dashboard: ' . $e->getMessage());
        }
    }

    public function users()
    {
        try {
            $users = User::latest()->paginate(10);
            return view('admin.users.index', compact('users'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error loading users: ' . $e->getMessage());
        }
    }

    public function showUser(User $user)
    {
        try {
            $orders = $user->orders()->latest()->paginate(5);
            return view('admin.users.show', compact('user', 'orders'));
        } catch (\Exception $e) {
            return redirect()->route('admin.users')->with('error', 'Error loading user: ' . $e->getMessage());
        }
    }

    public function editUser(User $user)
    {
        try {
            return view('admin.users.edit', compact('user'));
        } catch (\Exception $e) {
            return redirect()->route('admin.users')->with('error', 'Error loading user edit form: ' . $e->getMessage());
        }
    }

    public function updateUser(Request $request, User $user)
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
                'phone' => ['nullable', 'string', 'max:20'],
                'is_admin' => ['boolean'],
            ]);

            $user->update($validated);

            return redirect()->route('admin.users.show', $user)
                ->with('success', 'User updated successfully');
                
        } catch (\Exception $e) {
            return redirect()->back()->withInput()
                ->with('error', 'Error updating user: ' . $e->getMessage());
        }
    }

    public function destroyUser(User $user)
    {
        try {
            if ($user->id === auth()->id()) {
                return back()->with('error', 'You cannot delete your own account');
            }

            $user->delete();
            return redirect()->route('admin.users')
                ->with('success', 'User deleted successfully');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting user: ' . $e->getMessage());
        }
    }

    public function orders()
    {
        try {
            $orders = Order::with(['user', 'items'])
                ->latest()
                ->paginate(10);
                
            return view('admin.orders.index', compact('orders'));
            
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error loading orders: ' . $e->getMessage());
        }
    }

    public function showOrder(Order $order)
    {
        try {
            $order->load(['user', 'items.product']);
            return view('admin.orders.show', compact('order'));
        } catch (\Exception $e) {
            return redirect()->route('admin.orders')
                ->with('error', 'Error loading order: ' . $e->getMessage());
        }
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        try {
            $validated = $request->validate([
                'status' => ['required', 'in:pending,processing,shipped,delivered,cancelled'],
                'notes' => ['nullable', 'string', 'max:1000'],
            ]);

            $order->update([
                'status' => $validated['status'],
                'notes' => $validated['notes'] ?? $order->notes,
            ]);

            // If you want to send email notifications when status changes
            // $order->user->notify(new OrderStatusUpdated($order));

            return response()->json([
                'success' => true,
                'message' => 'Order status updated successfully',
                'status' => $order->status,
                'status_label' => ucfirst($order->status),
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating order status: ' . $e->getMessage(),
            ], 500);
        }
    }
}
