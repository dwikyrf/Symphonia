<x-layout>
    <div class="max-w-2xl mx-auto py-8">
        <h2 class="text-2xl font-bold mb-6 text-gray-900">Edit Category</h2>

        <form method="POST" action="{{ route('dashboard.categories.update', $category->id) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 
                        block w-full p-2.5"
                    required>
                @error('name')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="color" class="block mb-2 text-sm font-medium text-gray-900">Color</label>
                <input type="text" name="color" id="color" value="{{ old('color', $category->color) }}"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 
                        block w-full p-2.5"
                    required>
                @error('color')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                class="bg-primary-700 hover:bg-primary-800 text-white font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none">
                Update Category
            </button>
        </form>
    </div>
</x-layout>
