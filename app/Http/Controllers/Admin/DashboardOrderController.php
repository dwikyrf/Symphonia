<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Exports\OrdersExport;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
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

    public function downloadPdf(Order $order)
    {
        // pre-load relasi
        $order->load([
            'details.product', 'address', 'shipping',
            'transaction'   // jika soft-delete
        ]);

        /* Helper: cari file di public → private → null */
        $toB64 = function (?string $rel) {
            foreach (['public','private'] as $disk) {
                if ($rel && Storage::disk($disk)->exists($rel)) {
                    $abs  = Storage::disk($disk)->path($rel);
                    $mime = Storage::disk($disk)->mimeType($rel) ?? 'image/png';
                    return 'data:'.$mime.';base64,'.
                        base64_encode(file_get_contents($abs));
                }
            }
            return null;
        };

        /* ---------- Gambar produk ---------- */
        $items = $order->details->map(function ($d) use ($toB64) {
            return [
                'name'  => $d->product->name,
                'size'  => $d->size,
                'qty'   => $d->quantity,
                'img64' => $toB64($d->product->image),      // disk public
            ];
        });

        /* ---------- Desain & Logo ---------- */
        $design64 = $toB64($order->design);                 // disk public
        $logo64   = $toB64($order->logo);                   // disk public

        /* ---------- Bukti transfer ---------- */
        $tx = $order->transaction;
        $proof64 = $toB64(
            $tx?->transfer_proof_full ?: $tx?->transfer_proof_dp // disk private
        );

        $pdf = Pdf::loadView('dashboard.orders.pdf', [
                    'order'    => $order,
                    'items'    => $items,
                    'design64' => $design64,
                    'logo64'   => $logo64,
                    'desc'     => $order->description,
                    'proof64'  => $proof64,
                ])->setPaper('a4');

        return $pdf->download('Order-'.$order->order_number.'.pdf');
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

        return redirect()->route('dashboard.orders.index')->with('success', 'Orders deleted successfully!');
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
        $toB64 = function (?string $rel) {
            foreach (['public','private'] as $disk) {
                if ($rel && Storage::disk($disk)->exists($rel)) {
                    $abs  = Storage::disk($disk)->path($rel);
                    $mime = Storage::disk($disk)->mimeType($rel) ?? 'image/png';
                    return 'data:'.$mime.';base64,'.
                        base64_encode(file_get_contents($abs));
                }
            }
            return null;
        };
        $tx = $order->transaction;
        $proof64 = $toB64(
            $tx?->transfer_proof_full ?: $tx?->transfer_proof_dp // disk private
        );
        return view('dashboard.orders.show', compact('order', 'tx'));
    }
    public function exportsExcel()
    {
        return Excel::download(new OrdersExport, 'orders.xlsx');
    }

}
