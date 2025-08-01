<?php

namespace App\Http\Controllers;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\CartDetail;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function addToCart(Request $request, Product $product)
    {
        // dd($request->all()); // Debugging: Cek apakah data terkirim dengan benar
        
        $userId = auth()->id();
        $quantities = $request->input('quantity', []);

        // Konversi semua nilai quantity ke integer (menghapus angka nol di depan)
        $filteredQuantities = array_map(fn($qty) => (int) ltrim($qty, '0'), $quantities);

        // Hanya simpan ukuran dengan quantity > 0
        $filteredQuantities = array_filter($filteredQuantities, fn($qty) => $qty > 0);

        // Jika tidak ada yang dipilih, kembali dengan pesan error
        if (empty($filteredQuantities)) {
            return redirect()->back()->with('error', 'Please select at least one quantity.');
        }

        DB::beginTransaction();
        try {
            // Cek apakah user sudah memiliki cart aktif (belum checkout)
            $cart = Cart::firstOrCreate(
                ['user_id' => $userId],
                ['total_price' => 0, 'total_quantity' => 0]
            );

            $totalQuantity = 0;
            $totalPrice = 0;

            foreach ($filteredQuantities as $size => $quantity) {
                // Cek apakah produk dengan ukuran yang sama sudah ada di cart_detail
                $cartDetail = CartDetail::where('cart_id', $cart->id)
                    ->where('product_id', $product->id)
                    ->where('size', $size)
                    ->first();

                if ($cartDetail) {
                    // Jika sudah ada, update quantity
                    $cartDetail->increment('quantity', $quantity);
                } else {
                    // Jika belum ada, buat baru
                    CartDetail::create([
                        'cart_id' => $cart->id,
                        'product_id' => $product->id,
                        'size' => $size,
                        'quantity' => $quantity,
                        'price' => $product->price,
                    ]);
                }

                $totalQuantity += $quantity;
                $totalPrice += $product->price * $quantity;
            }

            // Update total cart
            $cart->increment('total_quantity', $totalQuantity);
            $cart->increment('total_price', $totalPrice);

            DB::commit();
            return redirect()->route('cart.index')->with('success', 'Product added to cart.');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage()); // Debugging jika terjadi error
            return redirect()->back()->with('error', 'Failed to add product to cart.');
        }
    }

    public function index()
    {
        $userId = auth()->id();

        // Ambil cart user yang belum checkout
        $cart = Cart::where('user_id', $userId)->first();

        // Jika tidak ada cart, tampilkan cart kosong
        if (!$cart) {
            return view('cart.index', ['cartItems' => collect()]);
        }

        // Ambil semua item dari cart_detail berdasarkan cart_id
        $cartItems = CartDetail::where('cart_id', $cart->id)
                                ->with('product')
                                ->get();

        return view('cart.index', compact('cartItems'));
    }

    public function removeFromCart($id)
    {
    
        $cartDetail = CartDetail::findOrFail($id);
        $cart = $cartDetail->cart;
    
        // Hapus item dari cart detail
        $cartDetail->delete();
    
        // Perbarui total cart
        $cart->decrement('total_quantity', $cartDetail->quantity);
        $cart->decrement('total_price', $cartDetail->quantity * $cartDetail->price);
    
        return redirect()->route('cart.index')->with('success', 'Item removed from cart.');
    }
    
    public function updateQuantity(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartDetail = CartDetail::findOrFail($id);
        $cart = $cartDetail->cart;

        // Update jumlah total dalam cart
        $cart->total_quantity += ($request->input('quantity') - $cartDetail->quantity);
        $cart->total_price += ($request->input('quantity') - $cartDetail->quantity) * $cartDetail->price;

        $cart->save();

        // Update quantity pada cart detail
        $cartDetail->quantity = $request->input('quantity');
        $cartDetail->save();

        return redirect()->route('cart.index')->with('success', 'Quantity updated.');
    }

    // public function checkout()
    // {
    //     // dd("Checkout route hit!");
    //     $userId = auth()->id();
    
    //     // Ambil cart aktif user beserta cartDetails
    //     $cart = Cart::where('user_id', $userId)->with('cartDetails')->first();
    
    //     // Jika cart tidak ditemukan atau kosong, kembalikan error
    //     if (!$cart || $cart->cartDetails->isEmpty()) {
    //         return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
    //     }
    
    //     DB::beginTransaction();
    //     try {
    //         // Pindahkan data dari cart ke order
    //         $order = Order::create([
    //             'user_id' => $userId,
    //             'total_price' => $cart->total_price,
    //             'total_quantity' => $cart->total_quantity,
    //             'remaining_balance'=> $cart->total_price,
    //             'status' => 'pending',
    //             'price' => $cart->total_price,
    //         ]);
    
    //         // Pindahkan semua item dari cart_detail ke order_detail
    //         $orderDetails = [];
    //         foreach ($cart->cartDetails as $cartDetail) {
    //             $orderDetails[] = [
    //                 'order_id' => $order->id,
    //                 'product_id' => $cartDetail->product_id,
    //                 'size' => $cartDetail->size,
    //                 'quantity' => $cartDetail->quantity,
    //                 'created_at' => now(),
    //                 'updated_at' => now(),
    //             ];
    //         }
    
    //         // Insert batch untuk mengurangi query database
    //         OrderDetail::insert($orderDetails);
    
    //         // Hapus data cart setelah checkout
    //         $cart->cartDetails()->delete();
    //         $cart->delete();
    
    //         DB::commit();
    //         return redirect()->route('order.show', $order->id)->with('success', 'Order placed successfully!');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         \Log::error("Checkout failed: " . $e->getMessage());
    //         return redirect()->route('cart.index')->with('error', 'Checkout failed. Please try again.');
    //     }
    // }
    
    public function checkout()
{
    $userId = auth()->id();

    /* ───── Ambil cart beserta detail ───── */
    $cart = Cart::where('user_id', $userId)
                ->with('cartDetails')
                ->first();

    if (!$cart || $cart->cartDetails->isEmpty()) {
        return redirect()
            ->route('cart.index')
            ->with('error', 'Keranjang Anda kosong.');
    }

    /* ───── 🔒 VALIDASI: minimal 24 qty ───── */
    $totalQty = $cart->cartDetails->sum('quantity');

    // cek jumlah total
    if ($totalQty < 24) {
        return redirect()
            ->route('cart.index')
            ->with('error', 'Minimal pemesanan 24 buah. Total Anda saat ini: '.$totalQty);
    }

    // opsional: pastikan tidak ada kuantitas 0/negatif
    if ($cart->cartDetails->contains(fn($d) => $d->quantity < 1)) {
        return redirect()
            ->route('cart.index')
            ->with('error', 'Kuantitas setiap item harus minimal 1.');
    }

    /* ───── Mulai transaksi DB ───── */
    DB::beginTransaction();
    try {
        // ----- 1. Buat order ----------------------------------------
        $order = Order::create([
            'user_id'          => $userId,
            'total_price'      => $cart->total_price,
            'total_quantity'   => $totalQty,
            'remaining_balance'=> $cart->total_price,
            'status'           => 'pending',
            'price'            => $cart->total_price,
        ]);

        // ----- 2. Salin detail ke order_detail ----------------------
        $orderDetails = $cart->cartDetails->map(function ($cd) use ($order) {
            return [
                'order_id'   => $order->id,
                'product_id' => $cd->product_id,
                'size'       => $cd->size,
                'quantity'   => $cd->quantity,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        })->all();
        OrderDetail::insert($orderDetails);

        // ----- 3. Bersihkan cart ------------------------------------
        $cart->cartDetails()->delete();
        $cart->delete();

        DB::commit();
        return redirect()
            ->route('order.show', $order->id)
            ->with('success', 'Order berhasil dibuat!');
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Checkout failed: '.$e->getMessage());
        return redirect()
            ->route('cart.index')
            ->with('error', 'Checkout gagal. Silakan coba lagi.');
    }
}



    public function showOrder($id)
    {
        $order = Order::where('id', $id)
                      ->where('user_id', auth()->id())
                      ->with('details.product')
                      ->firstOrFail();

        return view('order.show', compact('order'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = OrderDetail::findOrFail($id);
        $cartItem->update([
            'quantity' => $request->input('quantity')
        ]);

        return redirect()->route('cart.index')->with('success', 'Cart updated successfully.');
    }

}
