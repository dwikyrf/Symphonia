<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\PaymentService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Validation\ValidationException;

class PaymentController extends Controller
{
    public static function initialStatus(Order $order): string
    {
        if ($order->user->role === 'corporate') {
            return 'pending_po';                // menunggu verifikasi PO
        }
        return $order->selected_payment_type === 'dp'
            ? 'pending'                     // tunggu DP
            : 'pending_full';               // user pilih Full di awal
    }

    private function makeTx(Order $order, bool $isFull): Transaction
    {
        return $order->transaction()->firstOrCreate([], [
            'user_id'        => $order->user_id,
            'transaction_id' => Str::uuid(),
            'order_date'     => now(),
            // ⬇ angka yang benar
            'total_payment'  => $isFull
                ? $order->remaining_balance        // pelunasan
                : (int) round($order->remaining_balance * 0.40), // DP
            'payment_method' => 'bank_transfer',
            'payment_stage'  => $isFull ? 'full' : 'dp',
            'status'         => self::initialStatus($order),
            'is_verified_dp'     => false,
            'is_verified_full'   => false,
        ]);
    }

    //   public function uploadTransferProof(
    //     Request         $request,
    //     Order           $order,
    //     PaymentService  $pay
    // ) {
    //     $expectsJson = $request->expectsJson();          // true bila header Accept: application/json
    //     $stage       = $request->input('payment_stage'); // 'dp' | 'full'

    //     /* ------------------------------------------------------------------ */
    //     /* 1.  VALIDASI DASAR (file + stage)                                  */
    //     /* ------------------------------------------------------------------ */
    //     try {
    //         $request->validate([
    //             'payment_stage'  => 'required|in:dp,full',
    //             'transfer_proof' => 'required|image|max:2048',
    //         ]);
    //     } catch (ValidationException $e) {
    //         return $this->jsonOrBack(
    //             'Validasi gagal',
    //             422,
    //             $expectsJson,
    //             ['errors' => $e->errors()]
    //         );
    //     }

    //     /* ------------------------------------------------------------------ */
    //     /* 2.  BUSINESS RULE                                                  */
    //     /* ------------------------------------------------------------------ */
    //     if ($stage === 'full' && $order->payment_status !== 'partial') {
    //         return $this->jsonOrBack(
    //             'Anda harus membayar DP terlebih dahulu.',
    //             422,
    //             $expectsJson
    //         );
    //     }

    //     if ($stage === 'dp' && $order->payment_status !== 'unpaid') {
    //         // DP sudah terdata – boleh diganti HANYA kalau belum diverifikasi
    //         if ($order->transaction?->is_verified_dp) {
    //             return $this->jsonOrBack(
    //                 'DP sudah diverifikasi — tidak bisa diganti lagi.',
    //                 422,
    //                 $expectsJson
    //             );
    //         }
    //     }

    //     /* ------------------------------------------------------------------ */
    //     /* 3.  SIMPAN FILE                                                    */
    //     /* ------------------------------------------------------------------ */
    //     $path = $request->file('transfer_proof')
    //                     ->store('transfer_proofs', 'public');

    //     /* ------------------------------------------------------------------ */
    //     /* 4.  TRANSAKSI & UPDATE ORDER                                       */
    //     /* ------------------------------------------------------------------ */
    //     if ($stage === 'dp') {
    //         /* ambil atau buat transaksi DP sekali saja */
    //         $tx = $order->transactions()
    //                     ->where('payment_stage', 'dp')
    //                     ->first();

    //         if (! $tx) {
    //             $tx = $pay->createStageTransaction($order, 'dp');
    //         }

    //         // Timpa bukti + reset verifikasi
    //         $tx->update([
    //             'transfer_proof_dp' => $path,
    //             'is_verified_dp'    => false
    //         ]);
    //     } else { // pelunasan
    //         $tx = $pay->createStageTransaction($order, 'full');
    //         $tx->update([
    //             'transfer_proof_full' => $path,
    //             'is_verified_full'    => false
    //         ]);

    //         // Terapkan pembayaran ke Order
    //         $order->applyPayment($tx->total_payment, 'full');
    //     }

    //     /* ------------------------------------------------------------------ */
    //     /* 5.  RESPONSE                                                       */
    //     /* ------------------------------------------------------------------ */
    //     return $expectsJson
    //         ? response()->json([
    //               'message'       => 'Bukti transfer berhasil di-upload.',
    //               'redirect_url'  => route('payment.success', $order),
    //               'transactionId' => $tx->id,
    //           ])
    //         : redirect()
    //               ->route('payment.success', $order)
    //               ->with('success', 'Bukti transfer berhasil di-upload!');
    // }

public function uploadTransferProof(
    Request        $request,
    Order          $order,
    PaymentService $pay
) {
    /* ───────────── 0. VALIDASI FORM ───────────── */
    $validated = $request->validate([
        'payment_stage'  => 'required|in:dp,full',
        'transfer_proof' => 'required|image|max:2048',
    ]);

    $stage       = $validated['payment_stage'];   // 'dp' | 'full'
    $expectsJson = $request->expectsJson();

    /* ───────────── 1. BUSINESS RULES ───────────── */
    if ($stage === 'full') {
        //   ✔ boleh full langsung jika user memang pilih “full”
        //   ✔ boleh full sesudah DP diverifikasi (payment_status === partial)
        $fullAllowed = $order->selected_payment_type === 'full'
                    || $order->payment_status       === 'partial';

        if (! $fullAllowed) {
            return $this->jsonOrBack(
                'Anda harus membayar DP terlebih dahulu.',
                422,
                $expectsJson
            );
        }
    }

    if ($stage === 'dp' && $order->payment_status !== 'pending') {
        return $this->jsonOrBack(
            'DP sudah dibayarkan.',
            422,
            $expectsJson
        );
    }

    /* ───────────── 2. SIMPAN FILE ───────────── */
    $path = $request->file('transfer_proof')
                    ->store('transfer_proofs', 'private');

    /* ───────────── 3. DB TRANSACTION ───────────── */
    return DB::transaction(function () use ($stage, $order, $pay, $path, $expectsJson) {

        $grandTotal = $order->total_price + ($order->shipping->shipping_cost ?? 0);

        /* ---------------   DP   --------------- */
        if ($stage === 'dp') {

            // ↑ cari placeholder DP; kalau belum ada buat dg helper
            $tx = $order->transactions()
                        ->where('payment_stage', 'dp')
                        ->first()
                  ?? $pay->createStageTransaction($order, 'dp', $grandTotal);

            // simpan bukti
            $tx->transfer_proof_dp = $path;
            $tx->is_verified_dp    = false;
            $tx->status            = 'pending';
            $tx->save();

            // update order (40 % dibayar → sisa 60 %)
            $order->update([
                'remaining_balance' => $grandTotal - $tx->total_payment,
                'payment_status'    => 'partial',
            ]);
        }

        /* ---------------  FULL  --------------- */
        else /* stage === full */ {

            // cari placeholder FULL terlebih dulu
            $tx = $order->transactions()
                        ->where('payment_stage', 'full')
                        ->first()
                  ?? $pay->createStageTransaction($order, 'full', $grandTotal);

            $tx->transfer_proof_full = $path;
            $tx->is_verified_full    = false;
            $tx->status              = 'pending_full';
            $tx->save();

            // lunasi order
            $order->update([
                'remaining_balance' => 0,
                'payment_status'    => 'paid',
            ]);
        }

        /* ---------------  RESPONSE  --------------- */
        $payload = [
            'message'       => 'Bukti transfer berhasil di‑upload.',
            'redirect_url'  => route('payment.success', $order),
            'transactionId' => $tx->id,
        ];

        return $expectsJson
            ? response()->json($payload)
            : redirect()->route('payment.success', $order)
                         ->with('success', $payload['message']);
    });
}


/* util simpel untuk balikan json / redirect back */
private function jsonOrBack(string $msg, int $status, bool $json, array $extra = [])
{
    return $json
        ? response()->json(array_merge(['message' => $msg], $extra), $status)
        : back()->withErrors($msg);
}


/* ──────────────────────────────
 * Helper: balikan JSON atau redirect
 * ────────────────────────────── */
// private function jsonOrBack(
//     string  $msg,
//     int     $status,
//     bool    $expectsJson,
//     array   $extra = []
// ) {
//     return $expectsJson
//         ? response()->json(array_merge(['message'=>$msg], $extra), $status)
//         : back()->withErrors($msg);
// }


    /* ---------------------------------------------------------------------- */
    /* Helper  – balas JSON atau redirect back                                */
    /* ---------------------------------------------------------------------- */
    // private function jsonOrBack(
    //     string  $msg,
    //     int     $status,
    //     bool    $json,
    //     array   $extra = []
    // ) {
    //     return $json
    //         ? response()->json(array_merge(['message' => $msg], $extra), $status)
    //         : back()->withErrors($msg)->withInput();
    // }


// /**
//  * Helper: kirim JSON atau redirect back + errors.
//  */
// private function jsonOrBack(string $msg, int $code, bool $json)
// {
//     return $json
//         ? response()->json(['message' => $msg], $code)
//         : back()->withErrors($msg);
// }


    public function show(Order $order)
    {
        // pastikan satu transaksi
        $order->transaction()->firstOrCreate([], [
            'user_id'        => $order->user_id,
            'transaction_id' => Str::uuid(),
            'order_date'     => now(),
            'total_payment'  => $order->total_price,
            'payment_method' => 'bank_transfer',
            'payment_stage'  => $order->selected_payment_type ?: 'dp',
            'status'         => self::initialStatus($order),
            'is_verified_dp'     => false,
            'is_verified_full'   => false,
        ]);

        $transaction = $order->transaction;            // ← kirim ke view
        return view('payment.show', compact('order', 'transaction'));
    }

    public function choosePaymentStage(Request $request, Order $order)
    {
        $request->validate([
            'payment_stage' => 'required|in:dp,full',
        ]);

        $order->update([
            'selected_payment_type' => $request->payment_stage,
        ]);

        return redirect()->route('payment.show', $order)->with('success', 'Jenis pembayaran berhasil dipilih.');
    }

    public function showCompletionForm(Order $order)
    {
        abort_unless(auth()->id() === $order->user_id && $order->user->role==='corporate', 403);

        // corporate selalu full
        $this->makeTx($order, true);

        return view('payment.complete', compact('order'));
    }


    public function submitCompletion(Request $r, Order $order)
    {
        abort_unless(auth()->id() === $order->user_id && $order->user->role === 'corporate', 403);

        $r->validate([
            'transfer_proof_full' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $tx = $order->transaction; // sudah dipastikan ada di method show
        if ($tx->transfer_proof_full) {
            return back()->with('error','PO / bukti sudah di-upload.');
        }

        $path = $r->file('transfer_proof_full')->store('transfer_proofs','public');

        $tx->update([
            'transfer_proof_full' => $path,
            'status'              => 'pending_po',
        ]);

        return back()->with('success','PO / bukti berhasil dikirim. Menunggu verifikasi admin.');
    }


    public function showPayOffForm(Transaction $transaction)
    {
            $order   = $transaction->order; 
        abort_unless(
            auth()->id() === $transaction->user_id &&
            $transaction->payment_stage === 'dp' &&
            !$transaction->is_verified_full,
            403
        );

        return view('payment.payoff', compact('transaction','order'));
    }

    // public function submitPayOff(Request $r, Transaction $transaction)
    // {
    //     abort_unless(auth()->id() === $transaction->user_id, 403);

    //     $r->validate([
    //         'transfer_proof_full' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
    //     ]);

    //     // ----- data dasar -----
    //     $order = $transaction->order;
    //     $path  = $r->file('transfer_proof_full')
    //             ->store('transfer_proofs', 'public');
    //     $isFull = true;  // pelunasan selalu full
    //     $tx     = $this->makeTx($order, $isFull);
    //     /* --------------------------------------------------------
    //     pastikan baris transaksi sudah ada (atau buat lengkap)
    //     -------------------------------------------------------- */
    //     $tx = $order->transaction()->firstOrCreate(
    //     ['order_id' => $order->id],           // kunci pencarian
    //     [
    //         'user_id'        => $order->user_id,   // ← tambahkan
    //         'transaction_id' => Str::uuid(),
    //         'order_date'     => now(),
    //         'total_payment'  => $order->total_payment ?? $order->remaining_balance,
    //         'payment_method' => 'bank_transfer',
    //         'payment_stage'  => $isFull ? 'full' : 'dp',
    //         'status'         => $this->initialStatus($order),
    //         'is_verified_dp'    => false,
    //         'is_verified_full'  => false,
    //     ]
    // );

    // // cegah re-upload
    // if ($tx->transfer_proof_full) {
    //     return response()->json(
    //         ['error' => 'Bukti pelunasan sudah di-upload.'], 422);
    // }

    // /* simpan bukti baru & update status */
    // $tx->update([
    //     'transfer_proof_full' => $path,
    //     'is_verified_full'    => false,
    //     'status'              => 'pending_full',
    // ]);

    // /* update order */
    // $order->update([
    //     'remaining_balance' => 0,
    //     'payment_status'    => 'paid',
    // ]);


    // return response()->json([
    //     'message'      => 'Bukti pelunasan terkirim. Menunggu verifikasi admin.',
    //     'redirect_url' => route('order.show', $order),
    // ]);
    // }
    public function submitPayOff(Request $request, Transaction $transaction)
{
    /* 1. Autorisasi — hanya owner */
    abort_unless(auth()->id() === $transaction->user_id, 403);

    /* 2. Validasi file */
    $request->validate([
        'transfer_proof_full' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
    ]);

    /* 3. Simpan file lebih dulu */
                              // ← deklarasi SEKALI
    // $path = $request->file('transfer_proof_full')
    //                ->store('transfer_proofs','public');
    $path = $request->file('transfer_proof_full')
               ->store('transfer_proofs', 'private'); 

    /* 4. Transaksi DB atomik */
    DB::transaction(function () use ($transaction, $path) {

        $order = $transaction->order()->lockForUpdate()->firstOrFail();

        /* ---------------- Ambil / buat transaksi FULL ---------------- */
        $tx = $order->transactions()      // relasi hasMany di model Order
                    ->where('payment_stage','full')
                    ->first();

        if (! $tx) {
            $tx = Transaction::create([
                'user_id'        => $order->user_id,
                'order_id'       => $order->id,
                'transaction_id' => Str::uuid(),
                'order_date'     => now(), 
                'total_payment'  => $order->remaining_balance,  // sisa 60 %
                'payment_method' => 'bank_transfer',
                'payment_stage'  => 'full',
                'status'         => 'pending_full',
                'is_verified_dp'   => $transaction->is_verified_dp,
                'is_verified_full' => false,
            ]);
        } elseif ($tx->transfer_proof_full) {
            // sudah pernah upload → tolak
            throw ValidationException::withMessages([
                'transfer_proof_full' => 'Bukti pelunasan sudah di-upload.',
            ]);
        }

        /* ---------------- Simpan bukti & status ---------------- */
        $tx->update([
            'transfer_proof_full' => $path,
            'status'              => 'pending_full',
            'is_verified_full'    => false,
        ]);

        /* ---------------- Order: biarkan tetap partial ------------- */
        $order->update([
            // saldo tetap apa adanya sampai diverifikasi admin
            'payment_status' => 'partial',          // akan menjadi 'paid' saat verifikasi
        ]);

        Log::debug('[PAYOFF] Upload full', [
            'order_id' => $order->id,
            'tx_id'    => $tx->id,
        ]);
    });

    /* 5. Response */
   DB::transaction(function () use ($transaction, $path) {
    /* … proses … */
});

/* setelah commit */
$order = $transaction->order()->first();   // atau $transaction->order->refresh()

return redirect()->route('order.show', $order)
                 ->with('success', 'Bukti pelunasan berhasil di-upload — menunggu verifikasi admin.');
}

    public function processPayment(Request $request, Order $order)
    {
        // Ini bisa dikosongkan / optional nanti
    }

    public function success(Order $order)
    {
        return view('payment.success', compact('order'));
    }

  
    public function confirm(Request $request, $orderId)
    {
        $request->validate([
            'payment_type' => 'required|in:dp,full',
            'proof' => 'required|image|max:2048',
        ]);

        $order = Order::with(['shipping', 'transaction'])->findOrFail($orderId);

        $paymentType = $request->payment_type;

        // Cek apakah transaksi sudah ada
        $transaction = Transaction::firstOrCreate(
            ['order_id' => $order->id],
            [
                'user_id' => auth()->id(),
                'payment_type' => $order->selected_payment_type,
                'status' => 'pending',
            ]
        );

        $proofPath = $request->file('proof')->store('transfer_proofs', 'public');

        if ($paymentType === 'dp') {
            if ($transaction->transfer_proof_dp) {
                return back()->with('error', 'Bukti DP sudah diunggah.');
            }

            $transaction->update([
                'transfer_proof_dp' => $proofPath,
            ]);

            return redirect()->route('payment.success', $order->id)->with('success', 'Bukti DP berhasil diunggah. Menunggu verifikasi admin.');
        }

        if ($paymentType === 'full') {
            if (!$transaction->transfer_proof_dp) {
                return back()->with('error', 'Harap unggah bukti DP terlebih dahulu.');
            }

            if ($transaction->transfer_proof_full) {
                return back()->with('error', 'Bukti pelunasan sudah diunggah.');
            }

            $transaction->update([
                'transfer_proof_full' => $proofPath,
            ]);

            return redirect()->route('payment.success', $order->id)->with('success', 'Bukti pelunasan berhasil diunggah. Menunggu verifikasi admin.');
        }

        return back()->with('error', 'Jenis pembayaran tidak valid.');
    }

}
