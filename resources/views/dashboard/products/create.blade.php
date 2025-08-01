
<x-layout>
    <section class="bg-white">
        <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16">
            <h2 class="mb-4 text-xl font-bold text-gray-900">Add a New Product</h2>

            <form action="{{ route('dashboard.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">

                    <!-- Product Name -->
                    <div class="sm:col-span-2">
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Product Name</label>
                        <input type="text" name="name" id="name" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 
                                   focus:border-primary-600 block w-full p-2.5 
                                  "
                            placeholder="Type product name" value="{{ old('name') }}">
                    </div>

                    <!-- Price -->
                    <div class="w-full">
                        <label for="price" class="block mb-2 text-sm font-medium text-gray-900">Price (Rp)</label>
                        <input type="number" name="price" id="price" min="0" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 
                                   focus:border-primary-600 block w-full p-2.5 
                                  "
                            placeholder="2999" value="{{ old('price') }}">
                    </div>

                    <!-- Category -->
                    <div class="w-full">
                        <label for="category_id" class="block mb-2 text-sm font-medium text-gray-900">Category</label>
                        <select name="category_id" id="category_id" required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 
                                   focus:border-primary-500 block w-full p-2.5 
                                  ">
                            <option value="">Select Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Product Image -->
                    <div class="sm:col-span-2">
                        <label for="image" class="block mb-2 text-sm font-medium text-gray-900">Product Image</label>
                        <input type="file" name="image" id="image" accept="image/*"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 
                                   focus:border-primary-600 block w-full p-2.5 
                                  ">
                    </div>

                    <!-- Description -->
                    <div class="sm:col-span-2">
                        <label for="description" class="block mb-2 text-sm font-medium text-gray-900">Description</label>
                        <textarea name="description" id="description" rows="4" required
                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 
                                   focus:ring-primary-500 focus:border-primary-500 
                                  "
                            placeholder="Product description">{{ old('description') }}</textarea>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-between mt-6">
                    <!-- Back Button -->
                    <a href="{{ route('dashboard.products.index') }}"
                        class="px-5 py-2.5 text-sm font-medium text-gray-900 bg-gray-200 rounded-lg 
                               hover:bg-gray-300">
                        &laquo; Back
                    </a>

                    <!-- Add Product Button -->
                    <button type="submit"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-primary-700 rounded-lg focus:ring-4 
                               focus:ring-primary-200 hover:bg-primary-800">
                        ✅ Add Product
                    </button>
                </div>

            </form>
        </div>
    </section>
</x-layout>


