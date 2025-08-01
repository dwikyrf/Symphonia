<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\Shipping;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShippingController extends Controller
{
    public function index()
    {
        // Ambil semua shipping tanpa relasi order karena tidak ada relasi di model Shipping
        $shippings = Shipping::latest()->paginate(20);
        return view('dashboard.shippings.index', compact('shippings'));
    }

    public function edit(Order $order)
    {
        $shipping = $order->shipping; // relasi belongsTo dari Order ke Shipping
        return view('dashboard.shippings.edit', compact('order', 'shipping'));
    }

    public function update(Request $request, Order $order)
    {
        // ‼️ Pastikan order SUDAH punya shipping; kalau belum, tolak
        if (!$order->shipping) {
            return back()->withErrors('Order ini belum memiliki data pengiriman.');
        }

        // hanya 4 field yang boleh diubah
        $validated = $request->validate([
            'tracking_number' => 'nullable|string|max:255',
            'status'          => 'required|in:pending,dikirim,diterima',
            'shipped_at'      => 'nullable|date',
            'delivered_at'    => 'nullable|date|after_or_equal:shipped_at',
        ]);

        $order->shipping->update($validated);

        return redirect()
            ->route('dashboard.shipping.index')
            ->with('success', 'Data pengiriman berhasil diperbarui.');
    }

}
