<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    private const STATUSES = [
        'pending'          => 'Pending',
        'paid'             => 'Paid',
        'failed'           => 'Failed',
        'canceled'         => 'Cancelled',
        'refund_requested' => 'Refund Requested',
        'refunded'         => 'Refunded',
    ];

    public function index(Request $request): View
    {
        $query = Order::query()->with(['user', 'course', 'subscriptionPlan']);

        if ($search = $request->input('search')) {
            $query->where('transaction_id', 'like', "%{$search}%")
                  ->orWhereHas('user', fn ($q) => $q->where('name', 'like', "%{$search}%"));
        }

        if ($status = $request->input('payment_status')) {
            $query->where('payment_status', $status);
        }

        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }

        $orders = $query->latest()->paginate(20)->withQueryString();

        return view('admin.orders.index', [
            'orders'   => $orders,
            'statuses' => self::STATUSES,
        ]);
    }

    public function edit(Order $order): View
    {
        return view('admin.orders.edit', [
            'order'    => $order->load(['user', 'course', 'subscriptionPlan']),
            'statuses' => self::STATUSES,
        ]);
    }

    public function update(Request $request, Order $order): RedirectResponse
    {
        $data = $request->validate([
            'payment_status' => 'required|in:pending,paid,failed,canceled,refund_requested,refunded',
        ]);

        $order->update($data);

        return redirect()->route('admin.orders.index')
            ->with('success', 'Order updated successfully!');
    }

    public function markRefunded(Order $order): RedirectResponse
    {
        // Mirrors OrderResource's markRefunded record action.
        if ($order->payment_status !== 'refund_requested') {
            return redirect()->back()
                ->with('error', 'Only orders with a pending refund request can be marked as refunded.');
        }

        $order->update(['payment_status' => 'refunded']);

        return redirect()->back()
            ->with('success', 'Order marked as refunded.');
    }
}
