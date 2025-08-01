<x-layout>
    <x-slot:title>Pembayaran Order #{{ $order->order_number }}</x-slot:title>

    {{-- meta csrf sudah ada di layout utama --}}
    <section class="bg-white py-8 md:py-16">
        <div class="mx-auto max-w-screen-md px-4">
<x-slot:title>Pelunasan • Order #{{ $transaction->order->order_number }}</x-slot:title>

        {{-- ---------- Hero ---------- --}}
        <section class="relative isolate overflow-hidden bg-gradient-to-br from-primary-600 via-primary-500 to-primary-400 py-16">
            <div class="absolute inset-0 bg-[url('/img/shape.svg')] bg-center bg-cover opacity-10"></div>

            <div class="relative z-10 mx-auto max-w-screen-md px-6 text-center text-white">
                <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight mb-2">
                    Pembayaran
                </h1>
                <p class="text-sm md:text-base opacity-90">
                    Order&nbsp;<strong>#{{ $transaction->order->order_number }}</strong>
                </p>
            </div>
        </section>
            {{-- ------------------------------------------------------------------- --}}
            {{--  FORM PEMBAYARAN                                                     --}}
            {{-- ------------------------------------------------------------------- --}}
            <form id="paymentForm"
                  class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm"
                  enctype="multipart/form-data">
                @csrf

                <input type="hidden" id="order_id" value="{{ $order->id }}">

                {{-- <selected_payment_type> sudah diganti ↓ --}}
                <input type="hidden"
                       id="payment_stage"
                       name="payment_stage"
                       value="{{ $order->selected_payment_type }}"> {{-- "dp" | "full" --}}

                {{-- ── Info rekening ─────────────────────────────────────────────── --}}
                <div class="mb-6">
                    <label class="block text-sm font-medium mb-2">No. Rekening</label>

                    <div class="flex items-center space-x-4 mb-2">
                        <img src="{{ asset('img/mandiri.svg') }}" class="w-14" alt="Mandiri">
                        <span class="text-sm">
                            Mandiri: <strong>130001074xxxx</strong><br>
                            A/N Fakhrizal Davidson
                        </span>
                    </div>
                    <div class="flex items-center space-x-4">
                        <img src="{{ asset('img/bca.svg') }}" class="w-14" alt="BCA">
                        <span class="text-sm">
                            BCA: <strong>157140xxxx</strong><br>
                            A/N Fakhrizal Davidson
                        </span>
                    </div>
                </div>

                @php
                    $transaction = $order->transaction;
                    $showUploadForm = !(
                        ($transaction?->payment_stage === 'dp'   && $transaction?->is_verified_dp) ||
                        ($transaction?->payment_stage === 'full' && $transaction?->is_verified_full)
                    );
                @endphp

                {{-- ── Bukti transfer lama (jika ada) ───────────────────────────── --}}
                @if ($transaction && $transaction->transfer_proof_dp)
                    <div class="mb-6">
                        <p class="text-sm font-medium mb-2">Bukti Transfer Sebelumnya:</p>
                        <img src="{{ asset('storage/'.$transaction->transfer_proof_dp) }}"
                             class="rounded-lg w-full max-h-64 object-contain mb-2">
                        <p class="text-sm {{ $transaction->is_verified_dp ? 'text-green-600' : 'text-yellow-600' }}">
                            {{ $transaction->is_verified_dp
                                ? 'Sudah diverifikasi oleh admin.'
                                : 'Belum diverifikasi. Anda dapat menggantinya.' }}
                        </p>
                    </div>
                @endif

                {{-- ── Form upload baru ─────────────────────────────────────────── --}}
                @if ($showUploadForm)
                    <div class="mb-6">
                        <label class="block text-sm font-medium">
                            Upload Bukti Transfer (jpg/png)
                        </label>
                        <input type="file"
                               id="transfer_proof"
                               name="transfer_proof"
                               accept="image/*"
                               class="mt-2 w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm"
                               required>
                    </div>

                    <div id="preview" class="mb-6 hidden">
                        <p class="text-sm font-medium mb-2">Preview:</p>
                        <img id="previewImage"
                             class="rounded-lg w-full max-h-64 object-contain">
                    </div>
                @endif

                {{-- ── Ringkasan biaya ──────────────────────────────────────────── --}}
                <div class="mb-6 border-t pt-4">
                    <div class="flex justify-between text-sm">
                        <span>Subtotal</span>
                        <span>Rp {{ number_format($order->price, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span>Ongkir</span>
                        <span>Rp {{ number_format($order->shipping->shipping_cost ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-lg font-bold mt-2">
                        <span>Total Bayar</span>
                        <span id="total_payment"></span>
                    </div>
                </div>

                {{-- ── Tombol ───────────────────────────────────────────────────── --}}
                @if ($showUploadForm)
                    <div class="flex gap-4">
                        <a href="{{ route('order.show', $order->id) }}"
                           class="w-1/2 rounded-lg bg-gray-400 px-5 py-2.5 text-center text-sm text-white hover:bg-gray-500">
                            Kembali
                        </a>

                        <button type="submit" id="pay-button" disabled
                                class="w-1/2 rounded-lg bg-primary-700 px-5 py-2.5 text-sm text-white hover:bg-primary-800">
                            Konfirmasi Pembayaran
                        </button>
                    </div>
                @endif
            </form>
        </div>
    </section>

    {{-- ----------------------------------------------------------------------- --}}
    {{--  SCRIPT                                                                --}}
    {{-- ----------------------------------------------------------------------- --}}
    <script>
/* nilai mentah dari Blade → pastikan integer */
const totalPrice   = Number({{ $order->price }});                       // subtotal
const shippingCost = Number({{ $order->shipping->shipping_cost ?? 0 }}); // ongkir
const payType      = @json($order->selected_payment_type);              // "dp" | "full"
const orderId      = {{ $order->id }};

document.addEventListener('DOMContentLoaded', () => {

    /* formatter Rp */
    const rupiah = new Intl.NumberFormat('id-ID', {
        style: 'currency', currency: 'IDR', minimumFractionDigits: 0
    });

    /* --- Hitung nominal: DP = 25 % dari (subtotal + ongkir) --- */
    const grandTotal = totalPrice + shippingCost;
    const nominal = payType === 'dp'
        ? Math.floor(grandTotal * 0.40)      // dibulatkan ke BAWAH
        : grandTotal;

    /* tampilkan */
    const nominalEl = document.getElementById('total_payment');
    if (nominalEl) nominalEl.textContent = rupiah.format(nominal);

    /* --- Preview & enable tombol --- */
    const fileInput = document.getElementById('transfer_proof');
    const payBtn    = document.getElementById('pay-button');
    if (payBtn) payBtn.disabled = true;

    fileInput?.addEventListener('change', e => {
        const f = e.target.files[0];
        if (!f) return;

        const rd = new FileReader();
        rd.onload = ev => {
            document.getElementById('preview')?.classList.remove('hidden');
            const img = document.getElementById('previewImage');
            if (img) img.src = ev.target.result;
        };
        rd.readAsDataURL(f);
        if (payBtn) payBtn.disabled = false;
    });

    /* --- Submit form via fetch --- */
    document.getElementById('paymentForm')?.addEventListener('submit', async e => {
        e.preventDefault();
        if (!fileInput?.files.length) {
            return alert('Silakan pilih file bukti transfer.');
        }

        const fd = new FormData(e.target);
        fd.set('payment_stage', payType);            // dp | full

        const url = "{{ route('payment.uploadProof', ':id') }}".replace(':id', orderId);

        try {
            const res  = await fetch(url, {
                method : 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept'      : 'application/json'
                },
                body   : fd
            });

            const data = await res.json().catch(() => ({}));

            if (!res.ok) {
                const msg = data.message
                        || Object.values(data.errors ?? {}).flat().join('\n')
                        || res.statusText;
                throw new Error(msg);
            }

            alert(data.message ?? 'Bukti transfer berhasil di-upload.');
            window.location.href = data.redirect_url ?? window.location.href;

        } catch (err) {
            console.error(err);
            alert('Upload gagal: ' + err.message);
        }
    });
});
</script>

</x-layout>
