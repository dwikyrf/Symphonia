<x-layout>
    <x-slot:title>Invoice #{{ $order->order_number }}</x-slot:title>

    <section class="bg-white py-8 md:py-16">
        <div class="max-w-4xl mx-auto px-4">
            <div class="bg-white shadow-md rounded-lg p-8">
                <h2 class="text-2xl font-bold mb-6">Invoice</h2>

                <div class="mb-4">
                    <p><strong>Order Number:</strong> {{ $order->order_number }}</p>
                    <p><strong>Order Date:</strong> {{ $order->created_at->format('d F Y') }}</p>
                    <p><strong>Customer:</strong> {{ $order->user->name }}</p>
                    <p><strong>Address:</strong> {{ $order->address?->fullAddress() ?? '-' }}</p>
                </div>

                <div class="overflow-x-auto mt-6">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs uppercase bg-gray-50">
                            <tr>
                                <th class="px-4 py-2">Product</th>
                                <th class="px-4 py-2">Quantity</th>
                                <th class="px-4 py-2">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->details as $detail)
                                <tr>
                                    <td class="px-4 py-2">{{ $detail->product->name }}</td>
                                    <td class="px-4 py-2">{{ $detail->quantity }}</td>
                                    <td class="px-4 py-2"> Rp {{ number_format($detail->quantity * $detail->product->price, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-6 flex justify-end">
                    <p class="text-lg font-bold text-gray-900">
                        Total: Rp {{ number_format($order->total_price, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>
    </section>
</x-layout>
