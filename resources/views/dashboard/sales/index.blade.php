<x-layout>
    <x-slot:title>Sales Report</x-slot:title>

    <section class="bg-white py-8">
        <div class="mx-auto max-w-screen-xl px-4">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Sales Report</h2>

            <!-- Filter -->
            <form method="GET" action="{{ route('dashboard.sales.index') }}" class="flex flex-col sm:flex-row gap-4 mb-6">
                <<input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" class="border rounded-lg p-2 text-sm">
                <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" class="border rounded-lg p-2 text-sm">
                <select name="status" class="border rounded-lg p-2 text-sm">
                    <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Semua</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>

                <button type="submit" class="bg-primary-700 text-white px-4 py-2 rounded hover:bg-primary-800">Filter</button>

                <a href="{{ route('dashboard.sales.export-pdf', request()->all()) }}" 
                   class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm">
                    Export PDF
                </a>

                <a href="{{ route('dashboard.sales.export-excel', request()->all()) }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm">
                    Export Excel
                </a>
            </form>
            <script>
                const startDateInput = document.getElementById('start_date');
                const endDateInput = document.getElementById('end_date');

                startDateInput.addEventListener('change', function () {
                    endDateInput.min = this.value;

                    // Jika end_date sekarang < start_date, kosongkan
                    if (endDateInput.value && endDateInput.value < this.value) {
                        endDateInput.value = this.value;
                    }
                });
            </script>
            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs bg-gray-50">
                        <tr>
                            <th class="px-4 py-2">Order Number</th>
                            <th class="px-4 py-2">Date</th>
                            <th class="px-4 py-2">Total Price</th>
                            <th class="px-4 py-2">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ $order->order_number }}</td>
                            <td class="px-4 py-2">{{ $order->created_at->format('d M Y') }}</td>
                            <td class="px-4 py-2">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                            <td class="px-4 py-2 capitalize">{{ $order->status }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $orders->links() }}
            </div>
        </div>
    </section>
</x-layout>
