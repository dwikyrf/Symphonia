
<x-layout>
    <section class="bg-white">
        <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16">
            <h2 class="mb-4 text-xl font-bold text-gray-900">Edit Product</h2>

            <form action="{{ route('dashboard.products.update', $product->slug) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                    <!-- Product Name -->
                    <div class="sm:col-span-2">
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Title</label>
                        <input type="text" name="name" id="name"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 
                                   focus:border-primary-600 block w-full p-2.5 
                                  "
                            placeholder="Enter product title" required value="{{ old('name', $product->name) }}">
                    </div>

                    <!-- Category -->
                    <div class="w-full">
                        <label for="category_id" class="block mb-2 text-sm font-medium text-gray-900">Category</label>
                        <select name="category_id" id="category_id"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 
                                   focus:border-primary-500 block w-full p-2.5 
                                  ">
                            <option value="">Select Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" 
                                    {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Product Slug (Generated Automatically) -->
                    <input type="hidden" name="slug" id="slug" value="{{ old('slug', $product->slug) }}">

                    <!-- Product Image -->
                    <div class="sm:col-span-2">
                        <label for="image" class="block mb-2 text-sm font-medium text-gray-900">Product Image</label>
                        
                        @if($product->image)
                            <img src="{{ asset($product->image) }}" alt="Product Image" class="mb-3 w-48 rounded-lg border border-gray-300">
                        @else
                            <p class="text-gray-500">No image available</p>
                        @endif

                        <input type="file" name="image" id="image" 
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 
                                      focus:border-primary-600 block w-full p-2.5 
                                     ">
                    </div>

                    <!-- Product Body -->
                    <div class="sm:col-span-2">
                        <label for="description" class="block mb-2 text-sm font-medium text-gray-900">Content</label>
                        <textarea name="description" id="description" rows="4"
                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 
                                   focus:ring-primary-500 focus:border-primary-500 
                                  "
                            placeholder="Edit your product content">{{ old('description', $product->description) }}</textarea>
                    </div>

                    <!-- Product Price -->
                    <div class="sm:col-span-2">
                        <label for="price" class="block mb-2 text-sm font-medium text-gray-900">Price</label>
                        <input type="number" name="price" id="price" min="0"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 
                            focus:border-primary-600 block w-full p-2.5 
                           "
                        placeholder="Enter product price" required value="{{ old('price', $product->price) }}">

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

                    <div class="flex space-x-3">
                        <!-- Update Button -->
                        <button type="submit"
                            class="px-5 py-2.5 text-sm font-medium text-white bg-primary-700 rounded-lg focus:ring-4 
                                   focus:ring-primary-200 hover:bg-primary-800">
                            ✅ Update Product
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</x-layout>

