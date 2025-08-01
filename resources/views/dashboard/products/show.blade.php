
<x-layout>
    <section class="py-8 bg-white md:py-16 antialiased">
        <div class="max-w-screen-xl px-4 mx-auto 2xl:px-0">
            <div class="lg:grid lg:grid-cols-2 lg:gap-8 xl:gap-16">
                
                <!-- Bagian Gambar Produk -->
                <div class="shrink-0 max-w-md lg:max-w-lg mx-auto">
                    @if($product->image)
                        <img class="w-full object-cover rounded-lg border border-gray-300" 
                             src="{{ asset('img/' . $product->image) }}" 
                             alt="{{ $product->name }}">
                    @else
                        <img class="w-full object-cover rounded-lg border border-gray-300" 
                             src="https://via.placeholder.com/300" 
                             alt="No Image Available">
                    @endif
                </div>

                <!-- Bagian Informasi Produk -->
                <div class="mt-6 sm:mt-8 lg:mt-0">
                    <!-- Nama Produk -->
                    <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl">
                        {{ $product->name }}
                    </h1>

                    <!-- Harga Produk -->
                    <div class="mt-4">
                        <p class="text-2xl font-extrabold text-gray-900 sm:text-3xl">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </p>
                    </div>

                    <!-- Garis Pemisah -->
                    <hr class="my-6 md:my-8 border-gray-200" />

                    <!-- Deskripsi Produk -->
                    <p class="mb-6 text-gray-500">
                        {{ $product->description }}
                    </p>

                    <!-- Tombol Aksi -->
                    <div class="flex items-center space-x-3 mt-6">
                        <!-- Tombol Kembali -->
                        <a href="{{ route('dashboard.products.index') }}" 
                           class="px-4 py-2 text-sm font-medium text-gray-900 bg-gray-200 rounded-lg hover:bg-gray-300">
                            &laquo; Kembali
                        </a>

                        <!-- Tombol Edit -->
                        <a href="{{ route('dashboard.products.edit', $product->slug) }}" 
                           class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                            ✏️ Edit Produk
                        </a>

                        <!-- Tombol Hapus -->
                        <form action="{{ route('dashboard.products.destroy', $product->slug) }}" method="POST" 
                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">
                                🗑 Hapus Produk
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>
</x-layout>

