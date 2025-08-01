<x-layout>
  <x-slot:title>{{ $product->name }}</x-slot:title>

  <section class="py-8 bg-white md:py-16 antialiased">
    <div class="max-w-screen-xl px-4 mx-auto 2xl:px-0">
      {{-- ……… BAGIAN ATAS TIDAK DIUBAH ……… --}}

      <div class="lg:grid lg:grid-cols-2 lg:gap-8 xl:gap-16">

        {{-- ================= Gambar Produk ================= --}}
        <div class="shrink-0 max-w-md lg:max-w-lg mx-auto">
          <img
            class="w-full object-cover rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 ease-in-out"
            src="{{ $product->image ? asset('' . $product->image) : asset('img/default.png') }}"
            alt="{{ $product->name }}"
          />
        </div>

        {{-- ================= Detail & Form ================= --}}
        <div class="mt-6 sm:mt-8 lg:mt-0">
          <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl tracking-tight">
            {{ $product->name }}
          </h1>

          {{-- Harga & Rating --}}
          <div class="mt-4 sm:flex sm:items-center sm:gap-4">
            <p class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
              Rp {{ number_format($product->price, 0, ',', '.') }}
            </p>

            <div class="flex items-center gap-3 mt-2 sm:mt-0">
              @php $average = round($product->averageRating()); @endphp
              <div class="flex items-center gap-1">
                @for ($i = 1; $i <= 5; $i++)
                  <svg
                    class="w-5 h-5 {{ $i <= $average ? 'text-yellow-400' : 'text-gray-300' }}"
                    fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927C9.39 2.005 10.61 2.005 10.951 2.927l1.316 3.821a1 1 0 00.95.69h4.045c.969 0
                             1.371 1.24.588 1.81l-3.27 2.375a1 1 0 00-.364 1.118l1.316 3.822c.34.922-.755 1.688-1.538
                             1.118L10 13.347l-3.27 2.375c-.782.57-1.877-.196-1.538-1.118l1.316-3.822a1 1 0 00-.364-1.118L2.874
                             9.248c-.783-.57-.38-1.81.588-1.81h4.045a1 1 0 00.95-.69l1.316-3.82z"/>
                  </svg>
                @endfor
              </div>
              <p class="text-sm font-medium text-gray-600">
                ({{ number_format($product->averageRating(), 1) ?? '0.0' }})
              </p>
              <a href="#reviews"
                 class="text-sm font-medium text-primary-600 hover:text-primary-700 hover:underline">
                Lihat Ulasan ({{ $product->reviews()->count() }})
              </a>
            </div>
          </div>

          {{-- ==== Form Pilih Ukuran & Jumlah ==== --}}
          <div class="mt-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-1">
              Pilih Ukuran &amp; Jumlah
            </h2>

            <form action="{{ route('cart.add', $product->slug) }}" method="POST" class="mt-4">
              @csrf
              <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                @foreach(['XS','S','M','L','XL','XXL'] as $size)
                  <div
                    class="border border-gray-300 p-3 rounded-lg hover:border-primary-500 hover:bg-primary-50
                           transition-colors duration-200">
                    <label for="quantity-{{ $size }}" class="flex items-center justify-between w-full cursor-pointer">
                      <span class="text-sm font-medium text-gray-700">{{ $size }}</span>
                      <input
                        type="number"
                        name="quantity[{{ $size }}]"
                        id="quantity-{{ $size }}"
                        min="0"
                        value="{{ old('quantity.'.$size, 0) }}"
                        class="w-20 p-2 border border-gray-300 rounded-lg text-center quantity-input
                               focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                        aria-label="Jumlah untuk ukuran {{ $size }}">
                    </label>
                  </div>
                @endforeach
              </div>

              {{-- Total & Warning --}}
              <div class="mt-6 text-lg font-semibold text-gray-900 flex justify-between items-center">
                <span>Total Jumlah:</span>
                <span id="total-quantity" class="text-xl text-primary-700">0</span>
              </div>
              <p id="qty-warning" class="mt-2 text-sm text-red-600 hidden">
                Minimal pemesanan 24 buah
              </p>

              {{-- Tombol Submit --}}
              <button
                id="add-to-cart"
                type="submit"
                disabled
                class="mt-6 w-full sm:w-auto flex items-center justify-center gap-2
                       text-white bg-primary-700 hover:bg-primary-800
                       disabled:bg-gray-400 disabled:cursor-not-allowed
                       focus:ring-4 focus:outline-none focus:ring-primary-300
                       font-medium rounded-lg text-base px-6 py-3 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor"
                     viewBox="0 0 20 20">
                  <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74
                           11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0
                           0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0
                           013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                </svg>
                Tambah ke Keranjang
              </button>
            </form>
          </div>

          <hr class="my-8 border-gray-200">

          {{-- Deskripsi Produk --}}
          <div>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">Deskripsi Produk</h3>
            <div class="prose prose-sm sm:prose-base max-w-none text-gray-700">
              {!! $product->description !!}
            </div>
          </div>
        </div>
      </div>

      {{-- =====================   ULASAN   ===================== --}}
      <div id="reviews" class="mt-12 md:mt-16">
        @include('partials.reviews', ['product' => $product])
      </div>

      {{-- ……… BAGIAN FORM ULASAN TETAP (tak diubah) ……… --}}
      {{--  scroll down  --}}
      @php
        $user = auth()->user();
        $orderForReview = $user
            ? $user->orders()
                   ->where('status','completed')
                   ->whereHas('details', fn($q)=>$q->where('product_id', $product->id))
                   ->latest()->first()
            : null;
        $existingReview = $orderForReview
            ? $user->reviews()
                   ->where('order_id', $orderForReview->id)
                   ->where('product_id', $product->id)
                   ->first()
            : null;
      @endphp

      {{-- ========= Form Review (tetap) ========= --}}
      @if($orderForReview)
        {{-- ……… (isi sama persis dengan sebelumnya) ……… --}}
      @else
        <p class="mt-8 text-sm text-gray-500 text-center">
          Hanya pembeli yang telah menyelesaikan pesanan yang memuat produk ini yang dapat memberi ulasan.
        </p>
      @endif
    </div>
  </section>

  {{-- =====================  SCRIPT  ===================== --}}
  <script>
  document.addEventListener('DOMContentLoaded', () => {
    const qtyInputs     = document.querySelectorAll('.quantity-input');
    const totalQtyEl    = document.getElementById('total-quantity');
    const addBtn        = document.getElementById('add-to-cart');
    const warnEl        = document.getElementById('qty-warning');
    const MIN_QTY       = 24;

    /* hitung & validasi */
    const updateTotals = () => {
      let total = 0;
      qtyInputs.forEach(i => total += parseInt(i.value) || 0);
      totalQtyEl.textContent = total;

      const meetsMin = total >= MIN_QTY;
      addBtn.disabled         = !meetsMin;
      warnEl.classList.toggle('hidden', meetsMin);
    };

    /* sanitasi input */
    qtyInputs.forEach(input => {
      const sanitize = v => Math.max(0, parseInt(v) || 0);

      input.addEventListener('input', () => {
        input.value = sanitize(input.value);
        updateTotals();
      });

      input.value = sanitize(input.value); // init
    });

    updateTotals(); // awal

    /* backup: cegah submit via DevTools */
    addBtn.closest('form').addEventListener('submit', e => {
      if (parseInt(totalQtyEl.textContent) < MIN_QTY) {
        e.preventDefault();
        alert('Minimal pemesanan 24 buah');
      }
    });

    /* Lightbox gambar review (tetap) */
    document.querySelectorAll('.review-image').forEach(img => {
      // ……… (kode lightbox Anda sebelumnya) ………
    });
  });
  </script>
</x-layout>
