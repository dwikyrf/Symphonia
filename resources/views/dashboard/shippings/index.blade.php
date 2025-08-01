<x-layout>
    <x-slot:title>Daftar Pengiriman</x-slot:title>

    <section class="bg-white py-8">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Semua Pengiriman</h2>

            <div class="overflow-x-auto bg-white rounded-lg shadow">
                <table class="min-w-full text-sm text-left text-gray-500">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="px-6 py-4">Order</th>
                            <th class="px-6 py-4">Kurir</th>
                            <th class="px-6 py-4">Layanan</th>
                            <th class="px-6 py-4">Ongkir</th>
                            <th class="px-6 py-4">Resi</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @foreach ($shippings as $shipping)
                            @php
                                $order = \App\Models\Order::where('shipping_id', $shipping->id)->first();
                            @endphp
                            <tr class="border-b">
                                <td class="px-6 py-4 font-medium text-gray-900">
                                    {{ $order?->order_number ?? '-' }}
                                </td>
                                <td class="px-6 py-4">{{ $shipping->courier_name }}</td>
                                <td class="px-6 py-4">{{ $shipping->service_code }}</td>
                                <td class="px-6 py-4">Rp {{ number_format($shipping->shipping_cost, 0, ',', '.') }}</td>
                                <td class="px-6 py-4">{{ $shipping->tracking_number ?? '-' }}</td>
                                <td class="px-6 py-4 capitalize">{{ $shipping->status }}</td>
                                <td class="px-6 py-4">
                                    @if ($order)
                                        <a href="{{ route('dashboard.shipping.edit', $order) }}"
                                           class="text-blue-600 hover:underline">Edit</a>
                                    @else
                                        <span class="text-gray-400 italic">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</x-layout>
