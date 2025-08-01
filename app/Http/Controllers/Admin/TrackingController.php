<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderTracking;
use App\Notifications\OrderCompleted;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    /**
     * Menampilkan daftar tracking.
     */
    public function index()
    {
        $orders = Order::with('tracking')->orderBy('created_at', 'desc')->paginate(10);
        return view('dashboard.trackings.index', compact('orders'));
    }

    /**
     * Menampilkan form tambah tracking ke order.
     */
    public function create(Order $order)
    {
        return view('dashboard.trackings.create', compact('order'));
    }

    /**
     * Simpan tracking baru.
     */
    public function store(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|string|max:255',
        ]);

        $order->tracking()->create([
            'status' => $request->status,
        ]);

        // Logika update otomatis status order
        if (strtolower($request->status) === 'pesanan selesai' || strtolower($request->status) === 'selesai') {
            $order->update(['status' => 'completed']);

            // Kirim Email Notifikasi Order Completed
            if ($order->user && $order->user->email) {
                $order->user->notify(new OrderCompleted($order));
            }
        } else {
            // Kalau status belum selesai, tetap update ke 'processing'
            $order->update(['status' => 'processing']);
        }

        return redirect()->route('trackings.index')->with('success', 'Tracking berhasil ditambahkan.');
    }

    /**
     * Hapus tracking.
     */
    public function destroy(OrderTracking $orderTracking)
    {
        $orderTracking->delete();

        return redirect()->back()->with('success', 'Tracking berhasil dihapus.');
    }

    /**
     * Preview email Order Completed.
     */
    public function previewOrderCompletedEmail(Order $order)
    {
        return (new OrderCompleted($order))->toMail($order->user);
    }
}
