<x-layout>
    <x-slot:title>{{ $title ?? 'Tulis Ulasan' }}</x-slot:title>

    <section class="bg-white py-8 antialiased md:py-16">
        <div class="max-w-screen-md mx-auto px-4">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-8 text-center">Tulis Ulasan Anda</h2>

            <form method="POST" action="{{ route('review.store', $product->id) }}" enctype="multipart/form-data" class="space-y-6 p-6 sm:p-8 bg-gray-50 dark:bg-gray-800 rounded-xl shadow-lg">
                @csrf

                <input type="hidden" name="order_id" value="{{ $order->id }}">

                <div>
                    <label for="rating" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Rating Anda <span class="text-red-500">*</span></label>
                    {{-- Alpine.js component untuk rating bintang --}}
                    <div x-data="{ 
                            currentRating: {{ old('rating', 0) }}, 
                            hoverRating: 0,
                            setRating(star) {
                                this.currentRating = star;
                                // Memperbarui nilai input tersembunyi secara manual jika diperlukan oleh beberapa validasi JS,
                                // atau jika 'rating' di backend diharapkan dari input ini.
                                // HTML5 'required' pada input tersembunyi akan bekerja berdasarkan nilai 'currentRating'.
                                document.getElementById('rating_value_hidden').value = star;
                            },
                            getStarClass(star) {
                                if (this.hoverRating >= star) {
                                    return 'text-yellow-400';
                                } else if (this.currentRating >= star) {
                                    return 'text-yellow-400';
                                }
                                return 'text-gray-300 dark:text-gray-600';
                            }
                        }" 
                        class="flex items-center space-x-1"
                        @mouseleave="hoverRating = 0" {{-- Reset hoverRating saat mouse keluar dari container bintang --}}
                    >
                        {{-- Input tersembunyi untuk menyimpan nilai rating yang akan dikirim --}}
                        <input type="hidden" name="rating" id="rating_value_hidden" x-model="currentRating" required>

                        <template x-for="star in [1, 2, 3, 4, 5]" :key="star">
                            <svg @mouseover="hoverRating = star"
                                 @click="setRating(star)"
                                 class="w-7 h-7 sm:w-8 sm:h-8 cursor-pointer transition-colors duration-150"
                                 :class="getStarClass(star)"
                                 fill="currentColor" 
                                 viewBox="0 0 20 20">
                                <path d="M9.049 2.927C9.39 2.005 10.61 2.005 10.951 2.927l1.316 3.821a1 1 0 00.95.69h4.045c.969 0 1.371 1.24.588 1.81l-3.27 2.375a1 1 0 00-.364 1.118l1.316 3.822c.34.922-.755 1.688-1.538 1.118L10 13.347l-3.27 2.375c-.782.57-1.877-.196-1.538-1.118l1.316-3.822a1 1 0 00-.364-1.118L2.874 9.248c-.783-.57-.38-1.81.588-1.81h4.045a1 1 0 00.95-.69l1.316-3.82z" />
                            </svg>
                        </template>
                        <span x-show="currentRating > 0" x-text="currentRating + ' Bintang'" class="ml-2 text-sm text-gray-600 dark:text-gray-400"></span>
                    </div>
                    @error('rating') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="title" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Judul Ulasan <span class="text-red-500">*</span></label>
                    <input type="text" name="title" id="title" required 
                           value="{{ old('title') }}"
                           class="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:ring-primary-500 focus:border-primary-500 dark:focus:ring-primary-400 dark:focus:border-primary-400" 
                           placeholder="Contoh: Produk berkualitas tinggi!" />
                    @error('title') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="content" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Ulasan Anda <span class="text-red-500">*</span></label>
                    <textarea name="content" id="content" rows="5" required 
                              class="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:ring-primary-500 focus:border-primary-500 dark:focus:ring-primary-400 dark:focus:border-primary-400"
                              placeholder="Ceritakan pengalaman Anda menggunakan produk ini...">{{ old('content') }}</textarea>
                    @error('content') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="images" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-200">Upload Gambar (Opsional, maks. 3 gambar)</label>
                    <input type="file" name="images[]" id="images" multiple accept="image/png, image/jpeg, image/jpg" 
                           class="block w-full text-sm text-gray-900 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer bg-white dark:bg-gray-700 focus:outline-none shadow-sm
                                  file:mr-4 file:py-2.5 file:px-5 
                                  file:rounded-l-md file:border-0
                                  file:text-sm file:font-semibold
                                  file:bg-gray-100 dark:file:bg-gray-600 file:text-primary-700 dark:file:text-primary-300
                                  hover:file:bg-gray-200 dark:hover:file:bg-gray-500" />
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Tipe file yang diizinkan: PNG, JPG, JPEG. Ukuran maks per gambar: 2MB.</p>
                    @error('images') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    @error('images.*') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Indikator Pembelian Terverifikasi --}}
                <div class="flex items-center pt-2">
                    <svg class="w-5 h-5 text-green-500 dark:text-green-400 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <p class="text-sm font-medium text-green-600 dark:text-green-400">Pembelian Terverifikasi</p>
                </div>
                
                <div class="pt-2">
                    <button type="submit" class="w-full sm:w-auto text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800 font-medium rounded-lg text-base px-6 py-3 text-center transition ease-in-out duration-150">
                        Kirim Ulasan
                    </button>
                </div>
            </form>
        </div>
    </section>
</x-layout>