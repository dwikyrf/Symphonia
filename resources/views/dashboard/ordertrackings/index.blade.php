<x-layout>
    <x-slot:title>Manage Order Trackings</x-slot:title>

    <section class="py-8">
        <div class="max-w-screen-xl mx-auto px-4">
            <h2 class="text-2xl font-bold mb-6 text-gray-900">Order Trackings</h2>

            <div class="mb-6 text-right">
                <a href="{{ route('ordertrackings.create') }}" class="inline-block bg-primary-700 text-white px-4 py-2 rounded hover:bg-primary-800">
                    + Add Tracking
                </a>
            </div>

            <div class="overflow-x-auto bg-white rounded shadow">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-gray-700">Order Number</th>
                            <th class="px-4 py-3 text-gray-700">Status</th>
                            <th class="px-4 py-3 text-gray-700">Created At</th>
                            <th class="px-4 py-3 text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ordertrackings as $tracking)
                            <tr class="border-b">
                                <td class="px-4 py-3">{{ $tracking->order->order_number ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $tracking->status }}</td>
                                <td class="px-4 py-3">{{ $tracking->created_at->format('d F Y H:i') }}</td>
                                <td class="px-4 py-3 flex space-x-2">
                                    <form action="{{ route('ordertrackings.destroy', $tracking) }}" method="POST" onsubmit="return confirm('Delete this tracking?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="p-4">
                    {{ $ordertrackings->links() }}
                </div>
            </div>
        </div>
    </section>
</x-layout>
