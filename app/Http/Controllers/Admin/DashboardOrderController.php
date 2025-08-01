<?php

namespace App\Http\Controllers\Admin;

use App\Exports\OrdersExport;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Notifications\SendInvoiceNotification;



class DashboardOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user')->orderBy('created_at', 'desc');

        // 🔍 Search
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('order_number', 'like', '%'.$request->search.'%')
                  ->orWhereHas('user', function($user) use ($request) {
                      $user->where('name', 'like', '%'.$request->search.'%');
                  });
            });
        }

        // 🎯 Filter Status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(10);

        return view('dashboard.orders.index', compact('orders'));
    }

    
    public function exportInvoice(Order $order)
    {
        $pdf = Pdf::loadView('dashboard.orders.invoice', compact('order'));
        return $pdf->download('Invoice-'.$order->order_number.'.pdf');
    }
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array',
        ]);

        Order::whereIn('id', $request->order_ids)->delete();

        return redirect()->route('dashboard.order.index')->with('success', 'Orders deleted successfully!');
    }
 

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled'
        ]);

        $order->update(['status' => $request->status]);

        // ✅ Jika status completed, kirim email invoice otomatis
        if ($request->status === 'completed') {
            $order->user->notify(new SendInvoiceNotification($order));
        }

        return redirect()->route('dashboard.order.show', $order->id)
            ->with('success', 'Status order berhasil diperbarui.');
    }

    public function show(Order $order)
    {
        $order->load(['orderDetails.product', 'transaction', 'shipping', 'details.product']);
        $transaction = $order->transaction;
        return view('dashboard.orders.show', compact('order', 'transaction'));

        return view('dashboard.orders.show', compact('order'));
    }
    public function exportsExcel()
    {
        return Excel::download(new OrdersExport, 'orders.xlsx');
    }

}
