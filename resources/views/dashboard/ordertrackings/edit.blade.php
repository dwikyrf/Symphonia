<x-layout>
    <x-slot:title>Edit Tracking</x-slot:title>

    <div class="py-8 max-w-xl mx-auto">
        <h2 class="text-xl font-semibold mb-6 text-gray-900">Edit Status Tracking</h2>

        <form method="POST" action="{{ route('order-trackings.update', $orderTracking) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-900 mb-1">Status</label>
                <input type="text" name="status" value="{{ $orderTracking->status }}" required
                    class="w-full border-gray-300 rounded" />
            </div>

            <button type="submit" class="w-full bg-primary-700 text-white py-2 rounded hover:bg-primary-800">
                Update Tracking
            </button>
        </form>
    </div>
</x-layout>
