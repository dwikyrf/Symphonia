<x-layout>
    <x-slot:title>Order #{{ $order->order_number }}</x-slot:title>

    <section class="bg-gray-50 py-8 md:py-16">
        <div class="mx-auto max-w-screen-lg px-4">
            <h2 class="text-3xl font-extrabold text-gray-900 mb-8 text-center">Detail Pesanan Anda</h2>

            {{-- Order Summary --}}
            <div class="bg-white shadow-lg rounded-xl p-8 mb-8 border border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-xl font-bold text-gray-800 mb-2">
                            Total Harga: Rp {{ number_format($order->total_price, 0, ',', '.') }}
                        </p>
                        <p class="text-lg text-gray-700 mb-2">
                            Total Barang: {{ $order->total_quantity }} items
                        </p>
                        <p class="text-lg text-gray-700">
                            Status Order:
                            <span
                                class="px-3 py-1 rounded-full text-sm font-semibold
                                    @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                    @elseif($order->status === 'completed') bg-green-100 text-green-800
                                    @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                    @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <p class="text-lg text-gray-700 mb-2">
                            Status Pembayaran:
                            <strong class="
                                @if($order->payment_status === 'pending') text-red-600
                                @elseif($order->payment_status === 'partial') text-orange-600
                                @elseif($order->payment_status === 'paid') text-green-600
                                @endif">
                                {{ ucfirst($order->payment_status) }}
                            </strong>
                        </p>
                        @if ($order->selected_payment_type)
                            <p class="text-md text-gray-600">
                                Jenis Pembayaran Dipilih:
                                <span class="font-semibold">{{ ucfirst($order->selected_payment_type) }}</span>
                            </p>
                        @endif
                        
                    </div>
                </div>
            </div>

            {{-- Shipping Information (Consolidated) --}}
            @if ($order->shipping && $order->address)
                <div class="bg-white shadow-lg rounded-xl p-8 mb-8 border border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Informasi Pengiriman</h3>
                    <p class="text-gray-700"><strong>Alamat:</strong> {{ $order->address->address }}, {{ $order->address->city }}, {{ $order->address->postal_code }}</p>
                    <p class="text-gray-700"><strong>Penerima:</strong> {{ $order->address->recipient_name }} ({{ $order->address->phone_number }})</p>
                    <p class="text-gray-700"><strong>Layanan:</strong> {{ $order->shipping->courier_name }} - {{ $order->shipping->service_code }}</p>
                    <p class="text-gray-700"><strong>Biaya Ongkir:</strong> Rp {{ number_format($order->shipping->shipping_cost, 0, ',', '.') }}</p>
                    <p class="text-gray-700"><strong>No. Resi:</strong> {{ $order->shipping->tracking_number ?? '-' }}</p>
                    <p class="text-gray-700"><strong>Estimasi Tiba:</strong> {{ $order->shipping->estimated_days ?? '-' }}</p>
                    @php
                        $badgeClass = [
                            'pending'   => 'bg-yellow-100 text-yellow-800',
                            'shipped'   => 'bg-blue-100 text-blue-800',
                            'delivered' => 'bg-green-100 text-green-800',
                        ][$order->shipping->status] ?? 'bg-gray-100 text-gray-800';

                        $label = [
                            'pending'   => 'Belum Dikirim',
                            'shipped'   => 'Sedang Dikirim',
                            'delivered' => 'Telah Diterima',
                        ][$order->shipping->status] ?? 'Status Tidak Diketahui';
                    @endphp
                    <span class="inline-block mt-2 px-3 py-1 text-sm font-medium rounded-full {{ $badgeClass }}">
                        {{ $label }}
                    </span>
                </div>
            @endif

            {{-- Product List --}}
            <div class="bg-white shadow-lg rounded-xl p-8 mb-8 border border-gray-200">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Daftar Produk</h3>
                <div class="space-y-6">
                    @foreach ($order->details->groupBy('product_id') as $productId => $details)
                        @php $product = $details->first()->product; @endphp
                        <div class="flex items-center gap-6 border border-gray-200 rounded-lg p-4 bg-gray-50 hover:shadow-sm transition-shadow">
                            <img src="{{ asset($product->image) }}" class="w-20 h-20 object-cover rounded-md shadow-sm" alt="{{ $product->name }}">
                            <div class="flex-1">
                                <h4 class="font-bold text-lg text-gray-900 mb-1">{{ $product->name }}</h4>
                                <div class="text-sm text-gray-600 space-y-1">
                                    @foreach ($details as $d)
                                        <p>{{ $d->size }}: {{ $d->quantity }} pcs</p>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Shipping & Payment selection (when pending) --}}
            @if($order->status === 'pending' && $order->payment_status === 'pending')
                <div class="bg-white shadow-lg rounded-xl p-8 mb-8 border border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Pengiriman & Pembayaran</h3>

                    {{-- -- Step 1: address + shipping --}}
                    @if($order->address_id === null || $order->shipping_id === null)
                        <div class="mb-6 border-b pb-6 border-gray-200">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4">Pilih Alamat & Layanan Pengiriman</h4>

                            {{-- If user has no addresses --}}
                            @if(auth()->user()->addresses->isEmpty())
                                <div class="mt-2 text-sm text-red-500 p-3 bg-red-50 rounded-md">
                                    Anda belum memiliki alamat.
                                    <a href="{{ route('addresses.create') }}" class="text-blue-600 underline hover:text-blue-800">Tambahkan Alamat</a>
                                </div>
                            @else
                                {{-- Address dropdown --}}
                                <div class="mb-4">
                                    <label for="address-select" class="block text-sm font-medium text-gray-700 mb-1">Pilih Alamat Pengiriman</label>
                                    <select id="address-select" class="w-full border border-gray-300 rounded-md p-2 focus:ring-primary-500 focus:border-primary-500">
                                        <option value="">-- Pilih Alamat --</option>
                                        @foreach (auth()->user()->addresses as $addr)
                                            <option
                                                value="{{ $addr->id }}"
                                                data-postal="{{ $addr->postal_code }}"
                                                data-recipient="{{ $addr->recipient_name }}"
                                                data-city="{{ $addr->city }}"
                                                {{ $order->address_id === $addr->id ? 'selected' : '' }}>
                                                {{ $addr->recipient_name }} - {{ $addr->address }} ({{ $addr->postal_code }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Shipping options --}}
                                <div id="shipping-options-container" class="{{ $order->address_id ? '' : 'hidden' }}">
                                    <div id="loading-shipping" class="text-sm text-gray-500 mt-2 flex items-center hidden">
                                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Memuat data ongkir...
                                    </div>
                                    <label for="shipping-option" class="block text-sm font-medium text-gray-700 mt-4 mb-1">Pilih Layanan Pengiriman</label>
                                    <select id="shipping-option" class="w-full border border-gray-300 rounded-md p-2 focus:ring-primary-500 focus:border-primary-500">
                                        <option value="">Pilih Layanan</option>
                                    </select>
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- -- Step 2: payment type --}}
                    @if(!$order->selected_payment_type)
                        <div class="mt-6">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4">Pilih Jenis Pembayaran</h4>
                            <form id="payment-type-form" method="POST" action="{{ route('order.updatePaymentType', $order->id) }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="shipping_details" id="shipping-details-input">

                                @if(auth()->user()->role === 'user')
                                    <select name="selected_payment_type" required class="w-full border border-gray-300 rounded-md p-2 focus:ring-primary-500 focus:border-primary-500">
                                        <option value="">-- Pilih --</option>
                                        <option value="dp">DP (40%)</option>
                                        <option value="full">Lunas (100%)</option>
                                    </select>
                                @elseif(auth()->user()->role === 'corporate')
                                    <p class="text-gray-700">
                                        Untuk akun korporat, pembayaran akan dilunasi secara penuh (100%).
                                    </p>
                                    <input type="hidden" name="selected_payment_type" value="full">
                                @endif

                                <button type="submit"
                                    class="mt-4 w-full bg-primary-600 text-white px-5 py-2.5 rounded-md hover:bg-primary-700 transition-colors font-semibold">
                                    Konfirmasi Jenis Pembayaran
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="mt-6">
                            <a href="{{ route('payment.show', $order->id) }}"
                            class="inline-block w-full text-center bg-primary-600 hover:bg-primary-700
                                    text-white font-semibold px-5 py-2.5 rounded-md transition-colors">
                                Lanjut ke Pembayaran
                            </a>
                        </div>
                    @endif
                </div>
            @endif
            <div>          {{-- jarak 1.5rem (24 px) dari elemen di atas --}}
                @if($order->payment_status === 'partial')
                    <a  href="{{ route('payment.payoff.form', $order->transaction->id) }}"
                        class="inline-block bg-primary-600 hover:bg-primary-700 text-white
                            font-semibold px-5 py-2.5 rounded-md
                            mt-4   {{-- jarak di dalam div --}}
                            mb-6"> {{-- jarak ke elemen di bawah --}}
                        Upload Bukti Pelunasan
                    </a>
                @endif
            </div>         
            {{-- =====================  Desain & Logo  ===================== --}}
        <div>
            @if (
                $order->selected_payment_type &&
                $transaction &&
                ($transaction->is_verified_dp || $transaction->is_verified_full)
            )
                @php
                    $hasDetails = $order->design && $order->logo && $order->description;
                @endphp

                <div class="bg-white shadow-lg rounded-xl p-8 mb-8 border border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">
                        Unggah Detail Desain & Logo
                    </h3>

                    @if(!$hasDetails)
                        {{-- =========== FORM (belum pernah isi) =========== --}}
                        <p class="text-gray-700 mb-4">
                            Mohon unggah desain, logo, dan deskripsi untuk pesanan ini.
                            Setelah tersimpan, Anda tidak dapat mengubahnya sendiri.
                        </p>

                        <form method="POST"
                            action="{{ route('order.uploadDetails', $order->id) }}"
                            enctype="multipart/form-data">
                            @csrf

                            {{-- Desain --}}
                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-1">Desain (gambar)</label>
                                <input type="file" name="design" accept="img/*"
                                    class="block w-full text-sm file:rounded-md file:bg-gray-50 …" required>
                            </div>

                            {{-- Logo --}}
                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-1">Logo (gambar)</label>
                                <input type="file" name="logo" accept="img/*"
                                    class="block w-full text-sm file:rounded-md file:bg-gray-50 …" required>
                            </div>

                            {{-- Deskripsi --}}
                            <div class="mb-6">
                                <label class="block text-sm font-medium mb-1">Deskripsi</label>
                                <textarea name="description" rows="4" required
                                        class="w-full border rounded-md p-2"
                                        placeholder="Tambahkan deskripsi detail…"></textarea>
                            </div>

                            <button type="submit"
                                    class="w-full bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-md px-5 py-2.5">
                                Simpan Detail Desain
                            </button>
                        </form>
                    @else
                        {{-- =========== READ-ONLY VIEW (sudah diisi) =========== --}}
                        <p class="text-gray-700 mb-4">
                            Detail desain & logo sudah tersimpan. Jika Anda perlu mengubahnya,
                            silakan hubungi CS kami.
                        </p>

                        <div class="grid gap-6 md:grid-cols-2">
                            {{-- Preview Desain --}}
                            <div>
                                <h4 class="text-sm font-semibold mb-2">Desain</h4>
                                <a href="{{ route('order.file', [$order->id,'design']) }}" target="_blank">
                                    <img src="{{ route('order.file', [$order->id,'design']) }}"
                                        class="w-full h-48 object-contain border rounded-md shadow-sm">
                                </a>
                            </div>

                            {{-- Preview Logo --}}
                            <div>
                                <h4 class="text-sm font-semibold mb-2">Logo</h4>
                                <a href="{{ route('order.file', [$order->id,'logo']) }}" target="_blank">
                                    <img src="{{ route('order.file', [$order->id,'logo']) }}"
                                        class="w-full h-48 object-contain border rounded-md shadow-sm">
                                </a>
                            </div>
                        </div>
                        {{-- Deskripsi --}}
                        <div class="mt-6">
                            <h4 class="text-sm font-semibold mb-2">Deskripsi</h4>
                            <div class="border rounded-md p-4 bg-gray-50 text-gray-800 whitespace-pre-line">
                                {{ $order->description }}
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>

           {{-- ================= Review Section ================= --}}
@foreach ($order->details->groupBy('product_id') as $productId => $details)
@php
    $product = $details->first()->product;
    $existingReview = $product->reviews()
        ->where('user_id', auth()->id())
        ->where('order_id', $order->id)
        ->first();
@endphp

@if($order->status === 'completed' && ! $existingReview)
<section class="bg-white py-8 md:py-16 antialiased">
  <div class="max-w-screen-md mx-auto px-4">
    <h2 class="text-3xl font-bold text-center mb-8 text-gray-900">
      Tulis Ulasan {{ $product->name }}
    </h2>

    <form method="POST" action="{{ route('order.reviews.store', $order->id) }}"
          enctype="multipart/form-data"
          class="space-y-6 p-6 sm:p-8 bg-gray-50 rounded-xl shadow-lg">
      @csrf
      <input type="hidden" name="order_id"   value="{{ $order->id }}">
      <input type="hidden" name="product_id" value="{{ $product->id }}">

      {{-- Rating --}}
      @php $rid = 'rating_'.$product->id; @endphp
      <div x-data="{
              rating: {{ old('rating', 1) }},
              hover : 0,
              set(n){ this.rating = n; $refs.input.value = n }
          }" class="flex items-center space-x-1 select-none">

        <template x-for="star in [1,2,3,4,5]" :key="star">
          <svg @mouseenter="hover = star" @mouseleave="hover = 0"
               @click="set(star)"
               :class="(hover >= star || rating >= star)
                        ? 'text-yellow-400' : 'text-gray-300'"
               class="w-6 h-6 cursor-pointer transition-colors duration-200"
               xmlns="http://www.w3.org/2000/svg" fill="currentColor"
               viewBox="0 0 20 20">
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.316 3.821a1 1 0
                     00.95.69h4.045c.969 0 1.371 1.24.588 1.81l-3.27 2.375a1 1 0
                     00-.364 1.118l1.316 3.822c.34.922-.755 1.688-1.538
                     1.118L10 13.347l-3.27 2.375c-.782.57-1.877-.196-1.538-1.118l1.316-3.822a1
                     1 0 00-.364-1.118L2.874 9.248c-.783-.57-.38-1.81.588-1.81h4.045a1
                     1 0 00.95-.69l1.316-3.82z"/>
          </svg>
        </template>

        <input type="hidden" x-ref="input" id="{{ $rid }}" name="rating" :value="rating">
        <span class="ml-2 text-sm" x-text="rating + ' Bintang'"></span>
      </div>

      {{-- Judul --}}
      <div>
        <label class="block mb-2 text-sm font-medium">Judul <span class="text-red-500">*</span></label>
        <input type="text" name="title" value="{{ old('title') }}" required
               class="w-full border rounded-lg p-3">
      </div>

      {{-- Konten --}}
      <div>
        <label class="block mb-2 text-sm font-medium">Ulasan <span class="text-red-500">*</span></label>
        <textarea name="content" rows="5" required
                  class="w-full border rounded-lg p-3">{{ old('content') }}</textarea>
      </div>

      {{-- Gambar --}}
      <div>
        <label class="block mb-2 text-sm font-medium">Gambar (opsional, maks 3)</label>
        <input type="file" name="images[]" multiple accept="image/png,image/jpeg"
               onchange="if(this.files.length>3){alert('Maksimal 3 gambar'); this.value='';}"
               class="block w-full text-sm border rounded-lg p-2">
      </div>

      <button type="submit"
              class="w-full sm:w-auto bg-primary-700 hover:bg-primary-800
                     text-white rounded-lg px-6 py-3">
        Kirim Ulasan
      </button>
    </form>
  </div>
</section>

@elseif($order->status === 'completed' && $existingReview)
  <section class="py-4 text-center text-gray-600">
    <p>Anda sudah memberi ulasan untuk <strong>{{ $product->name }}</strong>. Terima kasih!</p>
  </section>
@endif
@endforeach

    {{-- === Light‑box delegasi untuk .review-image === --}}
   <script>
document.addEventListener('click', function (e) {
  const target = e.target;

  // hanya untuk gambar review
  if (!target.classList.contains('review-image')) return;

  // === overlay ===
  const overlay = document.createElement('div');
  overlay.style.cssText = `
    position:fixed; inset:0; z-index:9999;
    background:rgba(0,0,0,.85);
    display:flex; align-items:center; justify-content:center;
    opacity:0; transition:opacity .3s ease;
  `;

  // === gambar penuh ===
  const big = document.createElement('img');
  big.src = target.src;
  big.style.cssText = `
    max-width:90%; max-height:90%;
    border-radius:8px; box-shadow:0 15px 35px rgba(0,0,0,.5);
    transform:scale(.9); transition:transform .3s ease;
  `;

  overlay.appendChild(big);
  document.body.appendChild(overlay);
  document.body.style.overflow = 'hidden';

  // animasi masuk
  requestAnimationFrame(() => {
    overlay.style.opacity = '1';
    big.style.transform = 'scale(1)';
  });

  // klik mana saja → tutup
  overlay.addEventListener('click', () => {
    overlay.style.opacity = '0';
    big.style.transform = 'scale(.9)';
    setTimeout(() => {
      overlay.remove();
      document.body.style.overflow = '';
    }, 300);
  });
});
</script>



            {{-- Cancel Order --}}
            @if($order->status === 'pending')
                <div class="mt-8">
                    <form method="POST" action="{{ route('order.cancel', $order->id) }}"
                          onsubmit="return confirm('Apakah Anda yakin ingin membatalkan order ini? Tindakan ini tidak dapat dibatalkan.');">
                        @csrf
                        <button type="submit"
                                class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-4 rounded-md transition-colors shadow-md">
                            Batalkan Order
                        </button>
                    </form>
                </div>
            @endif

            <div class="mt-8 text-center">
                <a href="{{ route('order.index') }}"
                   class="inline-block w-full md:w-auto text-center rounded-md bg-gray-300 px-6 py-3 text-gray-900 font-semibold hover:bg-gray-400 transition-colors shadow-sm">
                    ← Kembali ke Daftar Pesanan
                </a>
            </div>
        </div>
    </section>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const addressSelect           = document.getElementById('address-select');
        const shippingOptionsContainer= document.getElementById('shipping-options-container');
        const shippingSelect          = document.getElementById('shipping-option');
        const loadingShipping         = document.getElementById('loading-shipping');
        const shippingDetailsInput    = document.getElementById('shipping-details-input');

        function loadShippingOptions() {
            const postalCode = addressSelect.options[addressSelect.selectedIndex]?.getAttribute('data-postal');
            const addressId  = addressSelect.value;
            const orderId    = {{ $order->id }};
            const originId   = 5140; // ganti sesuai kebutuhan

            if (!postalCode || !addressId) {
                shippingOptionsContainer.classList.add('hidden');
                shippingDetailsInput.value = '';
                return;
            }

            shippingOptionsContainer.classList.remove('hidden');
            loadingShipping.classList.remove('hidden');
            shippingSelect.innerHTML = '<option value="">Memuat layanan...</option>';
            shippingSelect.disabled  = true;
            shippingDetailsInput.value = '';

            // 1) destinationSearch
            fetch('{{ route('order.destinationSearch') }}', {
                method : 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept'      : 'application/json'
                },
                body: JSON.stringify({ keyword: postalCode })
            })
            .then(res => {
                if (!res.ok) {
                    return res.json().then(err => { throw new Error(err.message || res.status); });
                }
                return res.json();
            })
            .then(dest => {
                if (!dest?.length || !dest[0]?.id) {
                    throw new Error('Destinasi tidak ditemukan untuk kode pos ini.');
                }
                const destinationIdForShipping = dest[0].id;

                // 2) calculateShipping
                return fetch('{{ route('order.calculateShipping') }}', {
                    method : 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept'      : 'application/json'
                    },
                    body: JSON.stringify({
                        order_id     : orderId,
                        origin_id    : originId,
                        destination_id: destinationIdForShipping
                    })
                })
                .then(res => {
                    if (!res.ok) {
                        return res.json().then(err => { throw new Error(err.message || res.status); });
                    }
                    return res.json();
                })
                .then(data => ({ data, destinationIdForShipping }));
            })
            .then(({ data, destinationIdForShipping }) => {
                shippingSelect.innerHTML = '<option value="">Pilih Layanan</option>';
                shippingSelect.disabled  = false;

                const services = [
                    ...(data.data?.calculate_reguler || []),
                    ...(data.data?.calculate_cargo   || []),
                    ...(data.data?.calculate_instant || [])
                ];

                if (!services.length) {
                    shippingSelect.innerHTML = '<option value="">Tidak ada layanan tersedia</option>';
                    shippingSelect.disabled  = true;
                } else {
                    services.forEach(service => {
                        const shippingCostInRupiah = Math.floor(service.shipping_cost / 1000);
                        const jsonData = JSON.stringify({
                            courier_name  : service.shipping_name,
                            service_code  : service.service_name,
                            etd           : service.etd,
                            shipping_cost : shippingCostInRupiah,
                            origin_id     : originId,
                            destination_id: destinationIdForShipping,
                            address_id    : addressId,
                            order_id      : orderId
                        });
                        const label = `${service.shipping_name} - ${service.service_name} - Rp${shippingCostInRupiah.toLocaleString('id-ID')} (${service.etd})`;
                        shippingSelect.innerHTML += `<option value='${jsonData}'>${label}</option>`;
                    });
                }
                loadingShipping.classList.add('hidden');
            })
            .catch(err => {
                console.error('Error fetching shipping options:', err);
                loadingShipping.classList.add('hidden');
                shippingSelect.innerHTML = '<option value="">Gagal memuat layanan</option>';
                shippingSelect.disabled  = true;
                shippingDetailsInput.value = '';
                alert('Gagal memuat ongkir. Pastikan alamat valid dan coba lagi. Detail: ' + err.message);
            });
        }

        if (addressSelect) {
            addressSelect.addEventListener('change', loadShippingOptions);
            @if($order->address_id === null || $order->shipping_id === null)
                if (addressSelect.value) loadShippingOptions();
            @endif
        }

        if (shippingSelect) {
            shippingSelect.addEventListener('change', function () {
                shippingDetailsInput.value = this.value || '';
            });
        }

        const paymentTypeForm = document.getElementById('payment-type-form');
        if (paymentTypeForm) {
            paymentTypeForm.addEventListener('submit', function (e) {
                const needValidation =
                    {{ $order->address_id === null ? 'true' : 'false' }} ||
                    {{ $order->shipping_id === null ? 'true' : 'false' }};

                if (needValidation) {
                    if (!addressSelect?.value) {
                        alert('Mohon pilih alamat pengiriman terlebih dahulu.');
                        e.preventDefault();
                        return;
                    }
                    if (!shippingSelect?.value || shippingDetailsInput.value === '') {
                        alert('Mohon pilih layanan pengiriman terlebih dahulu.');
                        e.preventDefault();
                        return;
                    }
                }
            });
        }
    });
    </script>
</x-layout>
