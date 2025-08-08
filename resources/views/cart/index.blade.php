<x-layout>
    <x-slot:title>Cart</x-slot:title>

    <section class="bg-white py-8 antialiased md:py-16">
        <div class="mx-auto max-w-screen-xl px-4 2xl:px-0 lg:flex lg:items-start lg:gap-8">
            
            <!-- Shopping Cart Section -->
            <div class="w-full lg:w-2/3">
                <h2 class="text-xl font-semibold text-gray-900 sm:text-2xl">Shopping Cart</h2>

                @if($cartItems->isEmpty())
                    <p class="mt-6 text-gray-500 text-center">Your cart is empty.</p>
                @else
                    <div class="mt-6 space-y-6">
                        @foreach ($cartItems->groupBy('product_id') as $productId => $items)
                            <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-md transition-transform hover:scale-105">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <img class="h-16 w-16 rounded-md object-cover" src="{{ asset('' . $items->first()->product->image) }}" alt="{{ $items->first()->product->name }}">
                                        <div>
                                            <a href="/product/{{ $items->first()->product->slug }}" class="text-base font-medium text-gray-900 hover:underline">
                                                {{ $items->first()->product->name }}
                                            </a>
                                            <p class="text-gray-500 text-sm">
                                                Total Quantity: <span class="font-bold text-gray-900">{{ number_format($items->sum('quantity')) }}</span>
                                            </p>
                                            <p class="text-gray-900 font-bold text-lg">
                                                Rp {{ number_format($items->sum(fn($item) => $item->product->price * $item->quantity), 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Detail Order -->
                                <div class="mt-4">
                                    <table class="w-auto border-collapse border border-gray-300 text-sm">
                                        <thead>
                                            <tr class="bg-gray-100">
                                                <th class="border border-gray-300 px-2 py-1 text-gray-900">Size</th>
                                                <th class="border border-gray-300 px-2 py-1 text-gray-900">Quantity</th>
                                                <th class="border border-gray-300 px-2 py-1 text-gray-900">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($items as $cartItem)
                                            <tr
                                                x-data="{ original: {{ $cartItem->quantity }}, current: {{ $cartItem->quantity }} }"
                                                x-init="$watch('current', val => $refs.btn.disabled = (val == original))"
                                            >
                                                <td class="border px-2 py-1">{{ $cartItem->size }}</td>

                                                <!-- Qty -->
                                                <td class="border px-2 py-1 text-center">
                                                    <form action="{{ route('cart.update', $cartItem->id) }}" method="POST" class="flex items-center">
                                                        @csrf
                                                        <input type="number"
                                                            name="quantity"
                                                            x-model.number="current"
                                                            min="1"
                                                            class="w-20 text-center border rounded-lg text-sm mr-2" />
                                                        <button
                                                            x-ref="btn"
                                                            :disabled="true"                 
                                                            type="submit"
                                                            class="bg-blue-600 text-white px-2 py-1 rounded-lg text-xs
                                                                disabled:opacity-50 disabled:cursor-not-allowed
                                                                hover:bg-blue-700">
                                                            Update
                                                        </button>
                                                    </form>
                                                </td>

                                                <!-- Remove -->
                                                <td class="border px-2 py-1 text-center">
                                                    <form action="{{ route('cart.remove', $cartItem->id) }}" method="POST" class="inline-block">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="bg-red-600 text-white px-2 py-1 rounded-lg hover:bg-red-700 text-xs">
                                                            Remove
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            @php
                $totalQty = $cartItems->sum('quantity');
                $minQty   = 24;          // <- ubah kalau kebijakan berubah
            @endphp
            <!-- Order Summary Section -->
            <div class="w-full lg:w-1/3 lg:sticky lg:top-20">
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h2 class="text-xl font-semibold text-gray-900">Order Summary</h2>
                    
                    <div class="mt-4 space-y-2">
                        <p class="text-lg font-medium text-gray-900">
                            Total Items: {{ number_format($cartItems->sum('quantity')) }}
                        </p>
                        <p class="text-lg font-medium text-gray-900">
                            Total Price: Rp {{ number_format($cartItems->sum(fn($item) => $item->product->price * $item->quantity), 0, ',', '.') }}
                        </p>
                    </div>

                    @if(!$cartItems->isEmpty())
                        <form method="GET" action="{{ route('cart.checkout') }}">
                            @csrf
                            <button type="submit" class="w-full bg-primary-700 text-white py-2 px-4 rounded-lg hover:bg-primary-800">
                                Proceed to Checkout
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </section>
</x-layout>
