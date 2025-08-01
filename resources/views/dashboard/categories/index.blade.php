<x-layout>
    @if (session('success'))
    <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg">
        {{ session('success') }}
    </div>
    @endif

    <section class="bg-gray-50 p-3 sm:p-5">
        <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
            <div class="bg-white relative shadow-md sm:rounded-lg overflow-hidden">
                <div class="flex justify-between items-center p-4">
                    <h2 class="text-lg font-bold text-gray-900">Category Management</h2>
                    <a href="{{ route('dashboard.categories.create') }}"
                        class="flex items-center justify-center text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 
                            font-medium rounded-lg text-sm px-4 py-2 focus:outline-none">
                        <svg class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                        </svg>
                        Add Category
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th class="px-4 py-3">Name</th>
                                <th class="px-4 py-3">Slug</th>
                                <th class="px-4 py-3">Color</th>
                                <th class="px-4 py-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($paginatedCategories as $category)
                            <tr class="border-b">
                                <td class="px-4 py-3">{{ $category->name }}</td>
                                <td class="px-4 py-3">{{ $category->slug }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-block px-2 py-1 text-xs font-medium rounded" style="background-color: {{ $category->color }}">
                                        {{ $category->color }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 flex items-center justify-end">
                                    <a href="{{ route('dashboard.categories.edit', $category->id) }}"
                                        class="text-blue-600 hover:underline text-sm mr-4">Edit</a>
                                    <form action="{{ route('dashboard.categories.destroy', $category->id) }}" method="POST"
                                          onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline text-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-4 flex justify-between items-center">
                    @if ($paginatedCategories->count() > 0)
                        <span class="text-sm text-gray-500">
                            Showing {{ $paginatedCategories->firstItem() }} to {{ $paginatedCategories->lastItem() }} of {{ $paginatedCategories->total() }}
                        </span>
                    @endif
                    {{ $paginatedCategories->links('vendor.pagination.tailwind') }}
                </div>
            </div>
        </div>
    </section>
</x-layout>

