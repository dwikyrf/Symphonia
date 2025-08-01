<x-layout>
    <x-slot:title>Order #{{ $order->order_number }}</x-slot:title>

    <section class="bg-white py-8 md:py-16">
        <div class="max-w-screen-xl px-4 mx-auto 2xl:px-0">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Ringkasan Order (Admin)</h2>

            {{-- ==================== INFO ORDER ==================== --}}
            <div class="bg-white shadow-md rounded-lg p-6 space-y-4">
                <div class="flex flex-col sm:flex-row sm:justify-between">
                    <div>
                        <p class="text-lg font-semibold text-gray-900">
                            Total Price: Rp {{ number_format($order->total_price,0,',','.') }}
                        </p>
                        <p class="text-lg font-semibold text-gray-900">
                            Total Quantity: {{ $order->total_quantity }} items
                        </p>
                    </div>
                    <div class="text-md text-gray-700">
                        Status:
                        <span class="ml-2 px-2 py-1 text-sm rounded-lg font-medium
                            @if($order->status === 'processing')    bg-yellow-500 text-white
                            @elseif($order->status === 'distribution') bg-purple-500 text-white
                            @elseif($order->status === 'completed')   bg-green-600 text-white
                            @elseif($order->status === 'cancelled')   bg-gray-500 text-white
                            @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- ==================== ALAMAT ==================== --}}
            <div class="mt-6 bg-white shadow-md rounded-lg p-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-2">Billing & Delivery Information</h4>
                @if($order->address)
                    <p class="text-base text-gray-700">
                        {{ $order->address->recipient_name }} – {{ $order->address->phone }}<br>
                        {{ $order->address->address }}, {{ $order->address->city }}<br>
                        {{ $order->address->province }} – {{ $order->address->postal_code }}
                    </p>
                @else
                    <p>No address provided.</p>
                @endif
            </div>

            {{-- ==================== RINCIAN PRODUK ==================== --}}
            <div class="mt-6 bg-white shadow-md rounded-lg p-6">
                <h4 class="text-lg font-bold text-gray-900 mb-4">Order Details</h4>
                <div class="relative overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-900">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="p-4">Product</th>
                                <th class="p-4">Ukuran & Qty</th>
                                <th class="p-4 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->details->groupBy('product_id') as $details)
                                @php $product = $details->first()->product; @endphp
                                <tr class="border-b border-gray-200">
                                    <td class="p-4 flex items-center gap-4">
                                        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}"
                                             class="h-16 w-16 object-cover rounded-lg">
                                        <span>{{ $product->name }}</span>
                                    </td>
                                    <td class="p-4">
                                        @foreach($details as $d)
                                            ({{ $d->size }}) – {{ $d->quantity }} pcs<br>
                                        @endforeach
                                    </td>
                                    <td class="p-4 text-right">
                                        Rp {{ number_format($details->sum(fn($d)=>$d->quantity*$product->price),0,',','.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ==================== DESAIN & LOGO ==================== --}}
            @if($order->design || $order->logo || $order->description)
            <div class="mt-6 bg-white shadow-md rounded-lg p-6">
                <h4 class="text-lg font-bold text-gray-900 mb-4">Desain & Catatan Pemesan</h4>
                <div class="space-y-4">
                    @if($order->design)
                        <div>
                            <p class="text-gray-700 font-medium mb-1">Design:</p>
                            <img src="{{ route('order.file', [$order->id,'design']) }}"
                                 alt="Design" class="h-40 rounded border">
                        </div>
                    @endif
                    @if($order->logo)
                        <div>
                            <p class="text-gray-700 font-medium mb-1">Logo:</p>
                            <img src="{{ route('order.file', [$order->id,'logo']) }}"
                                 alt="Logo" class="h-40 rounded border">
                        </div>
                    @endif
                    @if($order->description)
                        <div>
                            <p class="text-gray-700 font-medium mb-1">Description:</p>
                            <p class="text-gray-800">{{ $order->description }}</p>
                        </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- ==================== BUKTI TRANSFER ==================== --}}
            @if($transaction)
            <div class="mt-6 bg-white shadow-md rounded-lg p-6">
                <h4 class="text-lg font-bold text-gray-900 mb-4">Bukti Pembayaran</h4>
                <div class="space-y-4">
                    @if($transaction->transfer_proof_dp)
                        <div>
                            <p class="text-gray-700 font-medium mb-1">Bukti Transfer DP:</p>
                            <img src="{{ route('order.file', [$order->id,'transfer_proof_dp']) }}"
                                 alt="DP proof" class="h-40 rounded border">
                            <p>Status: {{ $transaction->is_verified_dp ? '✔ Terverifikasi' : '❌ Belum' }}</p>
                        </div>
                    @endif
                    @if($transaction->transfer_proof_full)
                        <div>
                            <p class="text-gray-700 font-medium mb-1">Bukti Transfer Pelunasan:</p>
                            <img src="{{ route('order.file', [$order->id,'transfer_proof_full']) }}"
                                 alt="Full proof" class="h-40 rounded border">
                            <p>Status: {{ $transaction->is_verified_full ? '✔ Terverifikasi' : '❌ Belum' }}</p>
                        </div>
                    @endif
                </div>
            </div>
            @endif
        <div></div>
            {{-- ==================== RINGKASAN & STATUS ==================== --}}
            <div class="mt-6 bg-white shadow-md rounded-lg p-6 space-y-6">
                <h4 class="text-lg font-semibold text-gray-900">Ringkasan Biaya</h4>
                <div class="space-y-2 text-base">
                    <div class="flex justify-between text-gray-700">
                        <span>Subtotal</span>
                        <span>Rp {{ number_format($order->price,0,',','.') }}</span>
                    </div>
                    <div class="flex justify-between text-gray-700">
                        <span>Shipping</span>
                        <span>Rp {{ number_format(optional($order->shipping)->shipping_cost ?? 0,0,',','.') }}</span>
                    </div>
                    <div class="flex justify-between border-t pt-2 text-lg font-bold text-gray-900">
                        <span>Total</span>
                        <span>
                            Rp {{ number_format($order->total_price + (optional($order->shipping)->cost ?? 0),0,',','.') }}
                        </span>
                    </div>
                </div>

                {{-- Update Status --}}
                <div class="flex flex-col sm:flex-row sm:justify-between gap-4">
                    <a href="{{ route('dashboard.order.index') }}"
                       class="w-full sm:w-auto text-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-900 hover:bg-gray-100">
                        Kembali ke Daftar Order
                    </a>

                    <form method="POST" action="{{ route('dashboard.order.update-status', $order->id) }}"
                          class="w-full sm:w-auto">
                        @csrf
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Update Status</label>
                        <div class="flex gap-2 items-center">
                            <select name="status" id="status"
                                    class="rounded-lg border-gray-300 text-sm px-3 py-2 focus:ring focus:ring-primary-500">
                                <option value="pending"    {{ $order->status==='pending'    ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->status==='processing' ? 'selected' : '' }}>Processing</option>
                                <option value="completed"  {{ $order->status==='completed'  ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled"  {{ $order->status==='cancelled'  ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            <button type="submit"
                                    class="bg-primary-600 hover:bg-primary-700 text-white text-sm px-4 py-2 rounded-lg">
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script>
document.addEventListener('click', function (e) {
  const img = e.target.closest('img');          // pastikan elemen IMG
  if (!img) return;          // tanpa filter kelas


  /* hentikan navigasi apabila img ada di dalam <a> */
  const link = img.closest('a');
  if (link) e.preventDefault();

  /* === buat overlay gelap === */
  const overlay = document.createElement('div');
  overlay.style.cssText = `
    position:fixed; inset:0; z-index:9999;
    background:rgba(0,0,0,.85);
    display:flex; align-items:center; justify-content:center;
    opacity:0; transition:opacity .3s ease;
  `;

  /* === gambar besar === */
  const big = document.createElement('img');
  big.src = img.src;
  big.style.cssText = `
    max-width:90%; max-height:90%;
    border-radius:8px; box-shadow:0 15px 35px rgba(0,0,0,.5);
    transform:scale(.9); transition:transform .3s ease;
  `;

  overlay.appendChild(big);
  document.body.appendChild(overlay);
  document.body.style.overflow = 'hidden';

  /* animasi fade‑in */
  requestAnimationFrame(() => {
    overlay.style.opacity = '1';
    big.style.transform = 'scale(1)';
  });

  /* tutup saat overlay diklik */
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

    </section>
</x-layout>
