<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Status;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Services\PaymentService;
use PHPUnit\Event\Code\Throwable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;


class TransactionController extends Controller
{
    public function index(Request $request)
    {

        $query = Transaction::with(['user', 'order']);

        if (auth()->user()->role !== 'admin') {
            $query->where('user_id', auth()->id());
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $keyword = '%'.$request->q.'%';

            $query->where(function ($q) use ($keyword) {
                $q->whereHas('user',   fn ($u) => $u->where('name', 'like', $keyword))
                ->orWhereHas('order', fn ($o) => $o->where('order_number', 'like', $keyword));
            });
        }


        $transactions = $query->latest()
                            ->paginate(10)
                            ->withQueryString();

        return view('dashboard.transactions.index', compact('transactions'));
    }

    public function create()
    {
        $statuses = Status::all();
        return view('dashboard.transactions.create', compact('statuses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_date' => 'required|date',
            'total_payment' => 'required|numeric|min:0',
            // 'status_id' => 'required|exists:statuses,id',
        ]);

        Transaction::create([
            'user_id' => auth()->id(),
            'order_date' => $request->order_date,
            'total_payment' => $request->total_payment,
            // 'status_id' => $request->status_id,
        ]);

        return redirect()->route('transactions.index')->with('success', 'Transaction created successfully!');
    }

    public function show(Transaction $transaction)
    {
       
        return view('dashboard.transactions.show', compact('transaction'));
    }

    public function edit(Transaction $transaction)
    {
        $statuses = Status::all();
        return view('dashboard.transactions.edit', compact('transaction', 'statuses'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $request->validate([
            'order_date' => ['required','date'],
            'status'     => ['required',
                'in:pending,pending_full,pending_po,paid_dp,paid,approved,failed'],
        ]);

        $transaction->update([
            'order_date' => $request->order_date,
            'status'     => $request->status,
        ]);

        return redirect()
            ->route('dashboard.transactions.index')
            ->with('success','Transaction updated successfully!');
    }

    public function destroy(Transaction $transaction)
    {
        if ($transaction->transfer_proof_dp) {
            Storage::disk('public')->delete($transaction->transfer_proof_dp);
        }
        if ($transaction->transfer_proof_full) {
            Storage::disk('public')->delete($transaction->transfer_proof_full);
        }

        $transaction->delete();

        return redirect()->route('transactions.index')->with('success', 'Transaction deleted successfully!');
    }

    public function analytics()
    {
        $transactionsByStatus = Transaction::join('statuses', 'transactions.status_id', '=', 'statuses.id')
            ->selectRaw('statuses.name as status_name, COUNT(transactions.id) as count')
            ->groupBy('statuses.name')
            ->pluck('count', 'status_name');

        $monthlyRevenue = Transaction::selectRaw("DATE_FORMAT(order_date, '%Y-%m') as month, SUM(total_payment) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $dailyTransactions = Transaction::where('order_date', '>=', now()->subDays(30))
            ->selectRaw("order_date, COUNT(*) as count")
            ->groupBy('order_date')
            ->orderBy('order_date')
            ->pluck('count', 'order_date');

        $paymentDistribution = Transaction::selectRaw("ROUND(total_payment, -5) as range, COUNT(*) as count")
            ->groupBy('range')
            ->orderBy('range')
            ->pluck('count', 'range');

        return view('dashboard.index', compact(
            'transactionsByStatus',
            'monthlyRevenue',
            'dailyTransactions',
            'paymentDistribution'
        ));
    }



    // public function verify(Request $request, Transaction $transaction)
    // {
    //     /* 1. Autorisasi & validasi */
    //     abort_if(Auth::user()->role !== 'admin', 403);

    //     $validated = $request->validate([
    //         'stage' => 'required|in:dp,full,po',
    //     ]);
    //     $stage = $validated['stage'];

    //     /* 2. Transaksi database agar konsisten */
    //     DB::transaction(function () use ($stage, $transaction) {

    //         /* ---------- update kolom di tabel transactions ---------- */
    //         $transaction->update(match ($stage) {
    //             'dp'   => [
    //                 'is_verified_dp'   => true,
    //                 'status'           => $transaction->is_verified_full ? 'paid' : 'paid_dp',
    //             ],
    //             'full' => [
    //                 'is_verified_full' => true,
    //                 'status'           => $transaction->is_verified_dp   ? 'paid' : 'pending_full',
    //                 'remaining_balance' => 0,
    //             ],
    //             'po'   => [
    //                 'is_verified_full' => true,
    //                 'status'           => 'approved',
    //             ],
    //         });

    //         /* ---------- sinkronkan ke tabel orders ---------- */
    //         $order = $transaction->order()->lockForUpdate()->firstOrFail();
            
    //         $order->update([
    //             'payment_status' => match ($transaction->status) {
    //                 'paid_dp'          => 'partial',
    //                 'paid', 'approved' => 'paid',
                    
    //                 default            => $order->payment_status,
    //             },
                
    //         ]);
    //     });

    //     return back()->with('success', 'Bukti transfer berhasil diverifikasi.');
    // }
//  public function verify(Request $request, Transaction $transaction)
//     {
//         // 1. Keamanan: Hanya admin yang boleh melanjutkan.
//         abort_if(!Auth::user() || Auth::user()->role !== 'admin', 403, 'Akses ditolak.');

//         // 2. Validasi: Memastikan input 'stage' valid.
//         $validated = $request->validate([
//             'stage' => 'required|string|in:dp,full,po',
//         ]);
//         $stage = $validated['stage'];

//         // Jika transaksi sudah lunas atau disetujui, tidak perlu verifikasi lagi.
//         if (in_array($transaction->status, ['paid', 'approved'])) {
//             return back()->with('info', 'Transaksi ini statusnya sudah lunas atau disetujui.');
//         }

//         try {
//             // 3. Database Transaction: Menjamin semua proses berhasil atau tidak sama sekali.
//             DB::transaction(function () use ($stage, $transaction) {

//                 /*
//                 |--------------------------------------------------------------------------
//                 | (A) Perbarui Status Transaksi
//                 |--------------------------------------------------------------------------
//                 | Logika ini memperbarui record transaksi berdasarkan tahap verifikasi.
//                 */
//                 $transaction->update(match ($stage) {
//                     'dp'   => [
//                         'is_verified_dp'   => true,
//                         // Jika pembayaran lunas sudah diverif sebelumnya, langsung set 'paid'.
//                         'status'           => $transaction->is_verified_full ? 'paid' : 'paid_dp',
//                     ],
//                     'full' => [
//                         'is_verified_full' => true,
//                         'status'           => 'paid', // Pembayaran lunas selalu membuat status 'paid'.
//                     ],
//                     'po'   => [
//                         // Untuk PO, kita anggap pembayaran sudah terjamin (lunas).
//                         'is_verified_full' => true,
//                         'status'           => 'approved',
//                     ],
//                 });


//                 /*
//                 |--------------------------------------------------------------------------
//                 | (B) Sinkronisasi ke Order Terkait
//                 |--------------------------------------------------------------------------
//                 | Logika ini mengandalkan status BARU dari $transaction setelah di-update.
//                 | Ini lebih aman daripada mengandalkan variabel $stage dari input.
//                 */
//                 $order = $transaction->order()->lockForUpdate()->firstOrFail();

//                 $order->update([
//                     // Status pembayaran order disesuaikan dengan status baru transaksi.
//                     'payment_status' => match ($transaction->status) {
//                         'paid', 'approved' => 'paid',
//                         'paid_dp'          => 'partial',
//                         default            => $order->payment_status, // Fallback jika ada status lain
//                     },

//                     // Sisa tagihan menjadi 0 jika status transaksi sudah 'paid' atau 'approved'.
//                     // Ini lebih baik karena tidak bergantung pada $stage='full'.
//                     'remaining_balance' => in_array($transaction->status, ['paid', 'approved'])
//                         ? 0
//                         : $order->remaining_balance,
//                 ]);
//             });

//             return back()->with('success', 'Bukti transfer berhasil diverifikasi.');

//         } catch (Throwable $e) {
//             // Menangkap semua kemungkinan error (misal: order tidak ditemukan)
//             report($e); // Opsional: laporkan error ke sistem logging
//             return back()->with('error', 'Terjadi kesalahan saat memverifikasi transaksi.');
//         }
//     }

// app/Http/Controllers/TransactionController.php
public function verify(Request $request, Transaction $transaction)
{
    /* 1. Hanya admin */
    abort_if(auth()->user()->role !== 'admin', 403);

    /* 2. Ambil & validasi input stage */
    $stage = $request->validate([
        'stage' => 'required|in:dp,full,po',
    ])['stage'];

    /* 3. Pastikan transaksi yang akan diverifikasi memang punya stage itu */
    if ($transaction->payment_stage !== $stage) {
        return back()->withErrors(
            "Transaksi #{$transaction->id} adalah {$transaction->payment_stage}, "
          . "bukan {$stage}."
        );
    }

    /* 4. DB‑transaction */
    DB::transaction(function () use ($transaction, $stage) {

        /* ---------- (A) update baris transactions ---------- */
        $transaction->update(match ($stage) {
            'dp'   => [
                'is_verified_dp'   => true,
                'status'           => $transaction->is_verified_full ? 'paid' : 'paid_dp',
            ],
            'full' => [
                'is_verified_full' => true,
                'status'           => 'paid',   // selalu lunas
            ],
            'po'   => [
                'is_verified_full' => true,
                'status'           => 'approved',
            ],
        });

        /* ---------- (B) sinkron ke tabel orders ---------- */
        /** @var \App\Models\Order $order */
        $order = $transaction->order()->lockForUpdate()->firstOrFail();

        $order->update([
            'payment_status' => match ($transaction->status) {
                'paid_dp' => 'partial',
                'paid'    => 'paid',
                default   => $order->payment_status,
            },
            'remaining_balance' => $stage === 'full' ? 0 : $order->remaining_balance,
        ]);
    });

    return back()->with('success', 'Bukti transfer berhasil diverifikasi.');
}

}
