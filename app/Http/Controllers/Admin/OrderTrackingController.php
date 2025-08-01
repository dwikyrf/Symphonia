<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderTracking;
use Illuminate\Http\Request;

class OrderTrackingController extends Controller
{
        public function index()
    {
        $ordertrackings = OrderTracking::with('order')->latest()->paginate(10);
        return view('dashboard.ordertrackings.index', compact('ordertrackings'));
    }


    public function create()
    {
        $orders = Order::where('status', '!=', 'completed')->get();
        return view('dashboard.ordertrackings.create', compact('orders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'status' => 'required|string|max:255',
        ]);

        $order = Order::findOrFail($request->order_id);

        if ($order->status === 'completed') {
            return back()->with('error', 'Order sudah completed, tidak bisa menambah tracking lagi.');
        }

        $tracking = OrderTracking::create([
            'order_id' => $order->id,
            'status' => $request->status,
        ]);

        $this->updateOrderStatus($order, $tracking->status);

        $order->user->notify(new \App\Notifications\OrderTrackingUpdated($order, $tracking));

        return redirect()->route('ordertrackings.index')->with('success', 'Tracking berhasil ditambahkan dan notifikasi dikirim.');
    }

    public function edit(OrderTracking $ordertracking)
    {
        $order = $ordertracking->order;
        return view('dashboard.ordertrackings.edit', compact('ordertracking', 'order'));
    }

    public function update(Request $request, OrderTracking $ordertracking)
    {
        $request->validate([
            'status' => 'required|string|max:255',
        ]);

        $ordertracking->update([
            'status' => $request->status,
        ]);

        $order = $ordertracking->order;

        $this->updateOrderStatus($order, $request->status);

        $order->user->notify(new \App\Notifications\OrderTrackingUpdated($order, $ordertracking));

        return redirect()->route('ordertrackings.index')->with('success', 'Tracking berhasil diperbarui!');
    }

    public function destroy(OrderTracking $ordertracking)
    {
        $ordertracking->delete();

        return back()->with('success', 'Tracking berhasil dihapus.');
    }

    private function updateOrderStatus(Order $order, $trackingStatus)
    {
        if (str_contains(strtolower($trackingStatus), 'diterima')) {
            $order->update(['status' => 'completed']);
        } elseif (str_contains(strtolower($trackingStatus), 'dikirim') || str_contains(strtolower($trackingStatus), 'diproses')) {
            $order->update(['status' => 'processing']);
        }
    }
    
}
