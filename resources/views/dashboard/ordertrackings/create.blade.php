<x-layout>
    <x-slot:title>Add Order Tracking</x-slot:title>

    <section class="py-8">
        <div class="max-w-lg mx-auto px-4">
            <h2 class="text-2xl font-bold mb-6 text-gray-900">Create Tracking for Order</h2>

            <form action="{{ route('ordertrackings.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label class="block mb-2 font-medium">Select Order</label>
                    <select name="order_id" class="w-full border rounded px-3 py-2" required>
                        @foreach($orders as $order)
                            <option value="{{ $order->id }}">{{ $order->order_number }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block mb-2 font-medium">Tracking Status</label>
                    <input type="text" name="status" class="w-full border rounded px-3 py-2" placeholder="e.g. Dikirim ke gudang" required>
                </div>

                <button type="submit" class="bg-primary-700 text-white px-4 py-2 rounded hover:bg-primary-800">
                    Save
                </button>
            </form>
        </div>
    </section>
</x-layout>
