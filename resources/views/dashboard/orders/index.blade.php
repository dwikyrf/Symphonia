<x-layout>
    <x-slot:title>Dashboard Orders</x-slot:title>

    <section class="bg-white py-8 md:py-16">
        <div class="mx-auto max-w-screen-xl px-4">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Order List</h2>

            <!-- 🔥 Form Search + Filter + Export START -->
            <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
                <form action="{{ route('dashboard.order.index') }}" method="GET" class="flex gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Order..."
                        class="border rounded-lg px-3 py-2 text-sm" />

                    <select name="status" class="border rounded-lg px-3 py-2 text-sm">
                        <option value="">-- All Status --</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>

                    <button type="submit" class="bg-primary-700 text-white px-4 py-2 rounded hover:bg-primary-800 text-sm">
                        Filter
                    </button>
                </form>

                <div class="mt-4 md:mt-0">
                    <a href="{{ route('dashboard.order.exports') }}"
                       class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm">
                        Export Excel
                    </a>
                </div>
            </div>
            <!-- 🔥 Form Search + Filter + Export END -->

            <!-- 🔥 Bulk Delete Form START -->
            <form action="{{ route('dashboard.order.bulk-delete') }}" method="POST" id="bulkDeleteForm">
                @csrf

                <div class="bg-white shadow-md rounded-lg overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs uppercase bg-gray-50">
                            <tr>
                                <th class="px-6 py-3">
                                    <input type="checkbox" id="selectAll">
                                </th>
                                <th class="px-6 py-3">Order Number</th>
                                <th class="px-6 py-3">Date</th>
                                <th class="px-6 py-3">Total</th>
                                <th class="px-6 py-3">Payment Status</th>
                                <th class="px-6 py-3">Order Status</th>
                                <th class="px-6 py-3 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orders as $order)
                            <tr class="border-b">
                                <td class="px-6 py-4">
                                    <input type="checkbox" name="order_ids[]" value="{{ $order->id }}">
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $order->order_number }}</td>
                                <td class="px-6 py-4">{{ $order->created_at->format('d F Y') }}</td>
                                <td class="px-6 py-4">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 capitalize">{{ $order->payment_status }}</td>
                                <td class="px-6 py-4 capitalize">{{ $order->status }}</td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('dashboard.order.show', $order->id) }}"
                                       class="text-blue-600 hover:underline font-medium">
                                        View
                                    </a> |
                                    <a href="{{ route('dashboard.invoice.preview', $order->id) }}"
                                       class="text-green-600 hover:underline font-medium" target="_blank">
                                        Preview Invoice
                                    </a> |
                                    <a href="{{ route('dashboard.invoice.download', $order->id) }}"
                                       class="text-red-600 hover:underline font-medium">
                                        Download Invoice
                                    </a> |
                                    <a href="{{ route('dashboard.invoice.send', $order->id) }}"
                                       class="text-purple-600 hover:underline font-medium">
                                        Send Invoice Email
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-6 text-gray-500">
                                    No orders found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <button type="submit" class="mt-4 bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                    Delete Selected
                </button>
            </form>
            <!-- 🔥 Bulk Delete Form END -->

            <!-- 🔥 Script Select All -->
            <script>
                document.getElementById('selectAll').onclick = function() {
                    let checkboxes = document.getElementsByName('order_ids[]');
                    for (let checkbox of checkboxes) {
                        checkbox.checked = this.checked;
                    }
                }
            </script>

            <!-- 🔥 Pagination -->
            <div class="mt-6">
                {{ $orders->links() }}
            </div>

        </div>
    </section>
   
    
</x-layout>
