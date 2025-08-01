<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Cart;
use App\Models\Shipping;
use App\Services\KomerceShippingService;

class CheckoutController extends Controller
{
    public function process(Request $request, KomerceShippingService $komerce)
    {
        $request->validate([
            'address_id' => 'required|exists:addresses,id',
        ]);

        $user = Auth::user();

        $cart = Cart::with('details')->where('user_id', $user->id)->first();
        if (!$cart || $cart->details->isEmpty()) {
            return response()->json(['message' => 'Cart kosong atau tidak ditemukan.'], 400);
        }

        $address = $user->addresses()->where('id', $request->address_id)->first();
        if (!$address) {
            return response()->json(['message' => 'Alamat tidak valid.'], 400);
        }

        $destinationId = $address->city_code;

        $shippingData = $komerce->calculateShipping([
            'origin_id' => $request->origin_id,
            'destination_id' => $destinationId,
            'weight' => $cart->total_quantity * 800,
            'item_value' => $cart->total_price * 1000
        ]);

        $service = collect([
            ...$shippingData['data']['calculate_reguler'] ?? [],
            ...$shippingData['data']['calculate_cargo'] ?? [],
            ...$shippingData['data']['calculate_instant'] ?? []
        ])->first();

        if (!$service) {
            return response()->json(['message' => 'Tidak ada layanan pengiriman tersedia.'], 400);
        }

        DB::beginTransaction();
        try {
            $order = Order::create([
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'user_id' => $user->id,
                'role' => strtolower($user->role),
                'price' => $cart->total_price,
                'total_quantity' => $cart->total_quantity,
                'dp_paid' => 0,
                'remaining_balance' => 0,
                'payment_status' => 'pending',
                'status' => 'pending',
                'address_id' => $address->id,
                'total_price' => 0
            ]);

            $shipping = Shipping::create([
                'courier_name' => $service['shipping_name'] ?? 'Unknown',
                'service_code' => $service['service_name'] ?? 'REG',
                'shipping_cost' => (int) ($service['shipping_cost'] / 1000),
                'estimated_days' => $service['etd'] ?? '-',
                'status' => 'pending',
            ]);

            $order->shipping_id = $shipping->id;
            $order->save();

            $totalProduct = 0;
            foreach ($cart->details as $detail) {
                $subtotal = $detail->product->price * $detail->quantity;
                $totalProduct += $subtotal;

                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $detail->product_id,
                    'size' => $detail->size,
                    'quantity' => $detail->quantity,
                    'price' => $detail->product->price,
                    'design' => $order->design,
                    'logo' => $order->logo,
                    'description' => $order->description
                ]);
            }

            $order->update([
                'price' => $totalProduct,
                'total_price' => $totalProduct + $shipping->shipping_cost,
                'remaining_balance' => $totalProduct + $shipping->shipping_cost
            ]);

            $cart->details()->delete();
            $cart->delete();

            DB::commit();

            return response()->json([
                'message' => 'Checkout berhasil!',
                'order_number' => $order->order_number,
                'order_id' => $order->id,
                'redirect_to' => route('order.show', $order->id)
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Checkout gagal.', 'error' => $e->getMessage()], 500);
        }
    }


    public function showCheckoutForm(KomerceShippingService $komerce)
    {
        $provinces = $komerce->getProvinces();
        return view('checkout', compact('provinces'));
    }

    public function getCities(Request $request, KomerceShippingService $komerce)
    {
        $cities = $komerce->getCities($request->province_id);
        return response()->json($cities);
    }

    public function calculateShipping(Request $request, KomerceShippingService $komerce)
    {
        $data = $komerce->calculateShipping([
            'origin_id' => 501,
            'destination_id' => $request->destination,
            'weight' => $request->weight,
            'item_value' => $request->item_value
        ]);

        return response()->json($data);
    }
}
