<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Jobs\SendOrderShippedEmail;
// Use the mail job if we had one, for now we will just simulate or leave a placeholder comment
// actually plan mentions creating email jobs later.

class AdminOrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')->latest()->paginate(20);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['items.product', 'statusHistory.creator', 'user']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled,refunded',
            'notes' => 'nullable|string',
            'tracking_number' => 'nullable|required_if:status,shipped|string|max:255',
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        // Update Order
        $updateData = ['status' => $newStatus];

        if ($newStatus === 'shipped' && $request->tracking_number) {
            $updateData['tracking_number'] = $request->tracking_number;
            $updateData['shipped_at'] = now();
        }

        if ($newStatus === 'delivered') {
            $updateData['delivered_at'] = now();
        }

        $order->update($updateData);

        // Record History
        OrderStatusHistory::create([
            'order_id' => $order->id,
            'status' => $newStatus,
            'notes' => $request->notes,
            'created_by' => Auth::id()
        ]);

        // Send Email Notifications (Placeholder for Phase 3 - Email Templates task)
        // if ($newStatus === 'shipped' && $oldStatus !== 'shipped') {
        //     dispatch(new SendOrderShippedEmail($order));
        // }

        return back()->with('success', 'Order status updated successfully');
    }

    public function generateInvoice(Order $order)
    {
        $order->load(['items.product', 'user']);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.orders.invoice', compact('order'));

        return $pdf->download('invoice-' . $order->order_number . '.pdf');
    }
}