<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Address;
use App\Models\Shipping;
use App\Models\OrderDetail;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Services\KomerceShippingService;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())->orderBy('created_at', 'desc')->paginate(10);
        return view('order.index', compact('orders'));
    }
public function show(Order $order)
{
    /* ────────────────── Proteksi akses ────────────────── */
    if ($order->user_id !== auth()->id()) {
        return redirect()
            ->route('order.index')
            ->with('error', 'Anda tidak dapat mengakses pesanan tersebut.');
    }

    /* ────────────────── Load relasi ────────────────────── */
    // hindari N+1 ketika di Blade
    $order->load(['transaction', 'details.product', 'shipping', 'address']);

    /* ────────────────── Pastikan transaksi ada ─────────── */
    // $order->transaction()->firstOrCreate(
    //     ['order_id' => $order->id],                 // kriteria pencarian
    //     [                                           // data default bila belum ada
    //         'user_id'          => $order->user_id,
    //         'transaction_id'   => (string) Str::uuid(),
    //         'order_date'       => now(),
    //         'total_payment'    => $order->total_price,
    //         'payment_method'   => 'bank_transfer',
    //         'payment_stage'    => $order->selected_payment_type ?: 'dp',
    //         'status'           => PaymentController::initialStatus($order),
    //         'is_verified_dp'   => false,
    //         'is_verified_full' => false,
    //     ]
    // );
// if ($order->selected_payment_type && $order->transactions()->doesntExist()) {
//         $order->transactions()->create([
//             'user_id'        => $order->user_id,
//             'transaction_id' => (string) Str::uuid(),
//             'order_date'     => now(),
//             'payment_stage'  => $order->selected_payment_type,   // 'dp' OR 'full'
//             'total_payment'  => 0,                               // akan diisi nanti
//             'status'         => 'draft',
//             'is_verified_dp'   => false,
//             'is_verified_full' => false,
//         ]);
//     }
    /* ────────────────── Kirim ke view ──────────────────── */
    $transaction = $order->transaction; // dipakai di Blade

    return view('order.show', compact('order', 'transaction'));
}
    // public function show(Order $order)
    // {
    //     // pastikan relasi di-load (agar tidak N+1 ketika di view)
    //     $order->load(['transaction','details.product','shipping','address']);

    //     // ***buat transaksi kosong kalau belum ada (seperti PaymentController::show)***
    //     $order->transaction()->firstOrCreate(
    //     ['order_id' => $order->id], // <-- 1. Kriteria Pencarian: Cari berdasarkan order_id
    //     [                           // <-- 2. Data untuk dibuat JIKA tidak ditemukan
    //         'user_id'          => $order->user_id,
    //         'transaction_id'   => Str::uuid(),
    //         'order_date'       => now(),
    //         'total_payment'    => $order->total_price,
    //         'payment_method'   => 'bank_transfer',
    //         'payment_stage'    => $order->selected_payment_type ?: 'dp', // contoh
    //         'status'           => PaymentController::initialStatus($order),
    //         'is_verified_dp'   => false,
    //         'is_verified_full' => false,
    //     ]
    // );

    //     $transaction = $order->transaction;   // <-- untuk blade

    //     return view('order.show', compact('order','transaction'));
    // }

     public function createFromCart()
    {
        $user = Auth::user();
        $cart = Cart::with('details.product')->where('user_id', $user->id)->first();

        if (!$cart || $cart->total_price <= 0) {
            return redirect()->back()->with('error', 'Keranjang belanja kosong.');
        }

        $order = Order::create([
            'user_id' => $user->id,
            'role' => strtolower($user->role),
            'total_price' => $cart->total_price,
            'dp_paid' => 0,
            'remaining_balance' => $cart->total_price,
            'total_quantity' => $cart->total_quantity,
            'payment_status' => 'pending',
            'status' => 'pending',
        ]);

        foreach ($cart->details as $detail) {
            OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => $detail->product_id,
                'size' => $detail->size,
                'quantity' => $detail->quantity,
                'price' => $detail->product->price,
            ]);
        }

        $cart->delete();

        return redirect()->route('order.show', $order->id)->with('success', 'Order berhasil dibuat, silakan pilih metode pembayaran.');
    }

    public function track(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        $shippingAddress = $order->address ?? $order->user->addresses->where('is_default', true)->first();

        $orderDetails = $order->details()->with('product')->get();

        $trackingStatus = $order->tracking()->get()->map(function ($tracking) {
            return [
                'date' => $tracking->created_at->format('d M Y, H:i'),
                'status' => $tracking->status
            ];
        });

        return view('order.track', compact('order', 'shippingAddress', 'orderDetails', 'trackingStatus'));
    }
    public function confirmAll(Request $request, Order $order)
    {
        $request->validate([
            'address_id'           => 'required|exists:addresses,id',
            'selected_payment_type'=> 'required|in:dp,full',
            'courier_name'         => 'required|string',
            'service_code'         => 'required|string',
            'etd'                  => 'nullable|string',
            'shipping_cost'        => 'required|integer',
            'destination_id'       => 'required|integer',
            'origin_id'            => 'required|integer',
        ]);

        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $shipping = Shipping::firstOrCreate([
            'courier_name' => $request->courier_name,
            'service_code' => $request->service_code,
            'shipping_cost' => $request->shipping_cost,
            'estimated_days' => $request->etd,
        ], ['status' => 'pending']);

        $order->address_id = $request->address_id;
        $order->shipping_id = $shipping->id;
        $order->selected_payment_type = $request->selected_payment_type;

        $price = $order->details->sum(fn($d) => $d->quantity * $d->product->price);
        $order->total_price = $price + $shipping->shipping_cost;

        $order->save();

        return redirect()->route('payment.show', $order->id)->with('success', 'Informasi pengiriman dan pembayaran berhasil disimpan.');
    }

    public function updateAddress(Request $request, Order $order)
    {
        // $this->authorize('update', $order); // opsional, jika pakai policy

        $request->validate([
            'address_id' => 'required|exists:addresses,id',
        ]);

        $order->update([
            'address_id' => $request->address_id,
        ]);

        return response()->json(['message' => 'Alamat pengiriman diperbarui.']);
    }

    public function repeatOrder(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        $user = auth()->user();

        // Ambil atau buat cart
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);

        foreach ($order->details as $detail) {
            $existing = $cart->cartDetails()
                ->where('product_id', $detail->product_id)
                ->where('size', $detail->size)
                ->first();

            $price = $detail->price ?? $detail->product->price;

            if ($existing) {
                $existing->update([
                    'quantity' => $existing->quantity + $detail->quantity,
                    'price' => $price,
                ]);
            } else {
                $cart->cartDetails()->create([
                    'product_id' => $detail->product_id,
                    'quantity' => $detail->quantity,
                    'size' => $detail->size,
                    'price' => $price,
                ]);
            }
        }

        // Hitung ulang total_price dan total_quantity untuk cart
        $totalPrice = $cart->cartDetails->sum(fn($d) => $d->price * $d->quantity);
        $totalQuantity = $cart->cartDetails->sum('quantity');

        $cart->update([
            'total_price' => $totalPrice,
            'total_quantity' => $totalQuantity,
        ]);

        return redirect()->route('cart.index')->with('success', 'Produk dari pesanan telah dimasukkan ke keranjang.');
    }

    public function list()
    {
        $list = Order::where('user_id', auth()->id())
                     ->orderBy('created_at', 'desc')
                     ->paginate(10);

        return view('order.list', compact('list'));
    }
    // public function updatePaymentType(Request $request, Order $order)
    // {
    //     /* ───────────────────────── VALIDASI ───────────────────────── */
    //     $rules = [
    //         'selected_payment_type' => ['required','in:dp,full'],
    //     ];

    //     // Jika order belum punya alamat + shipping,
    //     // maka field shipping_details (JSON) WAJIB ada
    //     if (is_null($order->address_id) || is_null($order->shipping_id)) {
    //         $rules['shipping_details'] = ['required','json'];
    //     }

    //     $data = $request->validate($rules);

    //     /* ────────────────────── PROSES SHIPPING (opsional) ───────────────────── */
    //     if (isset($data['shipping_details'])) {
    //         $ship = json_decode($data['shipping_details'], true);

    //         // pastikan JSON memuat key minimum yang dibutuhkan
    //         if (!$ship || !Arr::has($ship, [
    //             'address_id','courier_name','service_code','shipping_cost'
    //         ])) {
    //             return back()->with('error','Data pengiriman tidak valid.');
    //         }

    //         /** 1. alamat */
    //         $order->address_id = $ship['address_id'];

    //         /** 2. hitung ulang subtotal barang */
    //         $price = $order->details
    //                     ->sum(fn ($d) => $d->quantity * $d->product->price);

    //         /** 3. cari / buat shipping */
    //         $shipping = Shipping::firstOrCreate(
    //             [
    //                 'courier_name'   => $ship['courier_name'],
    //                 'service_code'   => $ship['service_code'],
    //                 'shipping_cost'  => $ship['shipping_cost'],
    //                 'estimated_days' => $ship['etd'] ?? null,
    //             ],
    //             ['status' => 'pending']
    //         );

    //         /** 4. total & remaining balance */
    //         $total = $price + $shipping->shipping_cost;

    //         // default: user belum bayar apa-apa
    //         $remaining = $total;

    //         // kalau sudah sempat bayar DP (edge-case ketika user ulangi flow)
    //         if ($order->payment_status === 'partial') {
    //             $remaining = (int) round($total * 0.60);   // 60 %
    //         }

    //         /** 5. simpan ke order (tanpa commit dulu) */
    //         $order->fill([
    //             'shipping_id'       => $shipping->id,
    //             'price'             => $price,
    //             'total_price'       => $total,
    //             'remaining_balance' => $remaining,
    //         ]);
    //     }

    //     /* ─────────────────── PROSES PAYMENT TYPE ─────────────────── */
    //     $order->selected_payment_type = $data['selected_payment_type'];
    //     $order->save();                               // satu commit

    //     return redirect()
    //         ->route('payment.show', $order)
    //         ->with('success','Jenis pembayaran & pengiriman berhasil disimpan.');
    // }
    public function updatePaymentType(Request $request, Order $order)
{
    /* -------- 1. Validasi input -------- */
    $rules = ['selected_payment_type' => ['required', 'in:dp,full']];
    if (is_null($order->address_id) || is_null($order->shipping_id)) {
        $rules['shipping_details'] = ['required', 'json'];
    }
    $data = $request->validate($rules);

    /* -------- 2. Transaksi database (SATU SAJA) -------- */
    DB::transaction(function () use ($data, $order) {

        /* (A) -------------- proses shipping (opsional) -------------- */
        if (isset($data['shipping_details'])) {
            $ship = json_decode($data['shipping_details'], true);

            // pastikan JSON valid
            if (!Arr::has($ship, [
                'address_id', 'courier_name', 'service_code', 'shipping_cost'
            ])) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'shipping_details' => 'Data pengiriman tidak valid.',
                ]);
            }

            // 1. alamat
            $order->address_id = $ship['address_id'];

            // 2. subtotal barang
            $price = $order->details
                           ->sum(fn ($d) => $d->quantity * $d->product->price);

            // 3. shipping
            $shipping = Shipping::firstOrCreate(
                [
                    'courier_name'   => $ship['courier_name'],
                    'service_code'   => $ship['service_code'],
                    'shipping_cost'  => $ship['shipping_cost'],
                    'estimated_days' => $ship['etd'] ?? null,
                ],
                ['status' => 'pending']
            );

            // 4. total & sisa
            $total = $price + $shipping->shipping_cost;
            $remaining = $order->payment_status === 'partial'
                ? intdiv($total * 60, 100) : $total;

            // 5. isi kolom order
            $order->fill([
                'shipping_id'       => $shipping->id,
                'price'             => $price,
                'total_price'       => $total,
                'remaining_balance' => $remaining,
            ]);
        }

        /* (B) -------------- simpan tipe pembayaran -------------- */
        $order->selected_payment_type = $data['selected_payment_type'];
        $order->save();                                     // ← commit perubahan Order

        /* (C) -------------- sinkron tabel transactions -------------- */
        $grandTotal = $order->price + ($order->shipping->shipping_cost ?? 0);

        // helper status awal
        $initStatus = fn ($stage) => $stage === 'dp' ? 'pending' : 'pending_full';

        if ($order->selected_payment_type === 'dp') {
            // buat / update transaksi DP (40 %)
            $order->transactions()->updateOrCreate(
                [
                    'order_id'      => $order->id,
                    'payment_stage' => 'dp',
                ],
                [
                    'user_id'        => $order->user_id,
                    'transaction_id' => (string) Str::uuid(),
                    'order_date'     => now(),
                    'payment_method' => 'bank_transfer',
                    'total_payment'  => intdiv($grandTotal * 40, 100),
                    'status'         => $initStatus('dp'),
                ]
            );

            // hapus placeholder FULL
            $order->transactions()
                  ->where('payment_stage', 'full')
                  ->delete();
        }

        if ($order->selected_payment_type === 'full') {
            // hapus placeholder DP
            $order->transactions()
                  ->where('payment_stage', 'dp')
                  ->delete();

            // buat / update transaksi FULL (100 %)
            $order->transactions()->updateOrCreate(
                [
                    'order_id'      => $order->id,
                    'payment_stage' => 'full',
                ],
                [
                    'user_id'        => $order->user_id,
                    'transaction_id' => (string) Str::uuid(),
                    'order_date'     => now(),
                    'payment_method' => 'bank_transfer',
                    'total_payment'  => $grandTotal,
                    'status'         => $initStatus('full'),
                ]
            );
        }
    });

    /* -------- 3. Redirect -------- */
    return redirect()
        ->route('payment.show', $order)
        ->with('success', 'Jenis pembayaran & pengiriman berhasil disimpan.');
}
        public function uploadDetails(Request $request, Order $order)
    {
        $validated = $request->validate([
            'design'      => 'required|image|max:2048',
            'logo'        => 'required|image|max:2048',
            'description' => 'required|string',
        ]);

        // ── simpan ke disk PRIVATE ──
        $designPath = $request->file('design')
                            ->store('order_designs', 'private');
        $logoPath   = $request->file('logo')
                            ->store('order_logos', 'private');

        $order->update([
            'design'      => $designPath,   // simpan RELATIF path
            'logo'        => $logoPath,
            'description' => $validated['description'],
        ]);

        return back()->with('success','Desain & logo berhasil di‑upload.');
    }

    protected function processShippingDetails(Request $request, Order $order)
    {
        // Validasi data pengiriman dari Request yang baru dibuat
        $request->validate([
            'order_id'       => 'required|exists:orders,id',
            'address_id'     => 'required|exists:addresses,id',
            'origin_id'      => 'required|integer',
            'destination_id' => 'required|integer',
            'courier_name'   => 'required|string',
            'service_code'   => 'required|string',
            'etd'            => 'nullable|string',
            'shipping_cost'  => 'required|integer|min:0', // Pastikan min:0 jika memungkinkan biaya 0
        ]);

        $order->address_id = $request->address_id;

        // Hitung ulang harga produk dari orderDetails
        $price = $order->details->sum(fn($d) => $d->quantity * $d->product->price);

        // Cari atau buat shipping baru
        $shipping = Shipping::firstOrCreate([
            'courier_name'   => $request->courier_name,
            'service_code'   => $request->service_code,
            'shipping_cost'  => $request->shipping_cost,
            'estimated_days' => $request->etd,
        ], [
            'status' => 'pending',
        ]);

        // Update order
        $order->price = $price; // Jika ada kolom 'price' di tabel orders
        $order->shipping_id = $shipping->id;
        $order->total_price = $price + $shipping->shipping_cost;
        $order->save();
    }
     public function cancel(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        if (in_array($order->status, ['completed', 'cancelled'])) {
            return redirect()->route('order.show', $order->id)->with('error', 'Order tidak dapat dibatalkan.');
        }

        $order->update([
            'status' => 'cancelled',
            'payment_status' => 'pending',
        ]);

        return redirect()->route('order.show', $order->id)->with('success', 'Order berhasil dibatalkan.');
    }
        public function uploadCorporateProof(Request $request, Order $order)
    {
        $request->validate([
            'corporate_proof' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $path = $request->file('corporate_proof')->store('corporate_proofs', 'public');

        $order->corporate_proof = $path;
        $order->save();

        return redirect()->route('order.show', $order->id)->with('success', 'Bukti pemesanan berhasil diunggah.');
    }
     public function calculateShipping(Request $request, KomerceShippingService $komerce)
    {
        $order = Order::with('details')->findOrFail($request->order_id);

        $params = [
            'origin_id' => $request->origin_id,
            'destination_id' => $request->destination_id,
            'weight' => $order->details->sum(fn($d) => $d->quantity * 800),
            'item_value' => $order->details->sum(fn($d) => $d->quantity * $d->product->price),
        ];

         return response()->json($komerce->calculateShipping($params));
        
    }

    
    public function searchDestination(Request $request, KomerceShippingService $komerce)
    {
        return response()->json(
            $komerce->searchDestination($request->input('keyword'))
        );
    }


    public function updateShippingDetails(Request $request)
    {
        $request->validate([
            'order_id'       => 'required|exists:orders,id',
            'address_id'     => 'required|exists:addresses,id',
            'origin_id'      => 'required|integer',
            'destination_id' => 'required|integer',
            'courier_name'   => 'required|string',
            'service_code'   => 'required|string',
            'etd'            => 'nullable|string',
            'shipping_cost'  => 'required|integer',
        ]);

        /* ─── 1. Ambil order & hitung ulang subtotal ─── */
        $order  = Order::with('details.product')->findOrFail($request->order_id);
        $price  = $order->details->sum(fn ($d) => $d->quantity * $d->product->price);

        /* ─── 2. Cari / buat record shipping sesuai pilihan user ─── */
        $shipping = \App\Models\Shipping::firstOrCreate(
            [
                'courier_name'  => $request->courier_name,
                'service_code'  => $request->service_code,
                'shipping_cost' => $request->shipping_cost,
                'estimated_days'=> $request->etd,
            ],
            [ 'status' => 'pending' ]
        );

        /* ─── 3. Hitung total & remaining balance ─── */
        $total             = $price + $shipping->shipping_cost;
        $remaining_balance = $total;                          // default
        if ($order->payment_status === 'partial') {
            // jika user sudah bayar DP → sisakan 60 %
            $remaining_balance = $total * 0.60;
        }

        /* ─── 4. Update order ─── */
        $order->update([
            'address_id'        => $request->address_id,
            'shipping_id'       => $shipping->id,
            'price'             => $price,
            'total_price'       => $total,
            'remaining_balance' => $remaining_balance,
        ]);

        /* ─── 5. Response ─── */
        return response()->json([
            'message'           => 'Alamat & pengiriman berhasil disimpan.',
            'shipping_id'       => $shipping->id,
            'shipping_label'    => "{$shipping->courier_name} - {$shipping->service_code}",
            'total_price'       => $total,
            'remaining_balance' => $remaining_balance,
        ]);
    }

    // public function uploadDetails(Request $request, Order $order)
    // {
    //     $request->validate([
    //         'design' => 'nullable|image|max:2048',
    //         'logo' => 'nullable|image|max:2048',
    //         'description' => 'nullable|string|max:5000',
    //     ]);

    //     if ($request->hasFile('design')) {
    //         $designPath = $request->file('design')->store('designs', 'public');
    //         $order->design = $designPath;
    //     }

    //     if ($request->hasFile('logo')) {
    //         $logoPath = $request->file('logo')->store('logos', 'public');
    //         $order->logo = $logoPath;
    //     }

    //     $order->description = $request->input('description');
    //     $order->save();

    //     return back()->with('success', 'Detail desain berhasil disimpan.');
    // }
// public function updatePaymentType(Request $request, Order $order)
// {
//     /* 1. VALIDASI (tetap) */
//     $rules = ['selected_payment_type' => ['required','in:dp,full']];
//     if (is_null($order->address_id) || is_null($order->shipping_id)) {
//         $rules['shipping_details'] = ['required','json'];
//     }
//     $data = $request->validate($rules);

//     DB::transaction(function () use ($data, $order) {
//         /* (A) SHIPPING – sama persis dengan kode Anda … */

//         /* (B) SET payment_type */
//         $order->selected_payment_type = $data['selected_payment_type'];
//         $order->save();

//         /* (C) SINKRONKAN TRANSAKSI SESUAI PILIHAN */

//         $grandTotal = $order->price + ($order->shipping->shipping_cost ?? 0);

//         if ($order->selected_payment_type === 'dp') {
//             $dpAmount = intdiv($grandTotal * 40, 100);

//             // buat / update baris DP
//             $order->transactions()->updateOrCreate(
//                 ['payment_stage' => 'dp'],
//                 ['total_payment' => $dpAmount, 'status' => 'draft']
//             );

//             // hapus placeholder full (jika ada salah bikin sebelumnya)
//             $order->transactions()->where('payment_stage','full')->delete();
//         }

//         if ($order->selected_payment_type === 'full') {
//             // hapus placeholder DP (jika sempat tercipta)
//             $order->transactions()->where('payment_stage','dp')->delete();

//             // buat / update baris FULL = grand total
//             $order->transactions()->updateOrCreate(
//                 ['payment_stage' => 'full'],
//                 ['total_payment' => $grandTotal, 'status' => 'draft']
//             );
//         }
//     });

//     return redirect()
//         ->route('payment.show', $order)
//         ->with('success', 'Jenis pembayaran & pengiriman berhasil disimpan.');
// }

 public function showFile(Order $order, string $type)
    {
        $user = auth()->user();

        // ── Autorisasi ──
        abort_unless(
            $user && ($user->id === $order->user_id || $user->role === 'admin'),
            403
        );

        // ── Path file ──
        $path = $type === 'logo' ? $order->logo : $order->design;
        abort_unless($path, 404);

        // ── Kirim respon (inline) ──
        return Storage::disk('private')->response($path);
        // → ganti response() → download() jika ingin selalu-unduh
    }


}
