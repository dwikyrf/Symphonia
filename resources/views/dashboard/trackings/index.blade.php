<x-layout>
    <x-slot:title>Manage Tracking Status</x-slot:title>

    <section class="py-8">
        <div class="max-w-screen-xl mx-auto px-4">
            <h2 class="text-2xl font-bold mb-6 text-gray-900">List of Tracking Status</h2>

            <div class="mb-4 text-right">
                <a href="{{ route('trackings.create') }}" class="inline-block bg-primary-700 text-white px-4 py-2 rounded hover:bg-primary-800">
                    + Add New Status
                </a>
            </div>

            <div class="overflow-x-auto bg-white rounded shadow">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-gray-700">Name</th>
                            <th class="px-4 py-3 text-gray-700">Slug</th>
                            <th class="px-4 py-3 text-gray-700">Color</th>
                            <th class="px-4 py-3 text-gray-700">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($trackings as $tracking)
                        <tr class="border-b">
                            <td class="px-4 py-3">{{ $tracking->name }}</td>
                            <td class="px-4 py-3">{{ $tracking->slug }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded {{ $tracking->color }}">{{ $tracking->color }}</span>
                            </td>
                            <td class="px-4 py-3 flex space-x-2">
                                <a href="{{ route('trackings.edit', $tracking) }}" class="text-blue-600 hover:underline">Edit</a>
                                <form action="{{ route('trackings.destroy', $tracking) }}" method="POST" onsubmit="return confirm('Are you sure to delete?')">
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
                    {{ $trackings->links() }}
                </div>
            </div>
        </div>
    </section>
</x-layout>
