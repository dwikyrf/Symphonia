<x-layout>
    <x-slot:title>Upload PO / Bukti • Order #{{ $order->order_number }}</x-slot:title>

    {{-- Hero --}}
    <section class="relative isolate overflow-hidden bg-gradient-to-br from-primary-600 via-primary-500 to-primary-400 py-16">
        <div class="absolute inset-0 bg-[url('/img/shape.svg')] bg-center bg-cover opacity-10"></div>
        <div class="relative z-10 mx-auto max-w-screen-md px-6 text-center text-white">
            <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight mb-2">Upload PO / Bukti Pembayaran</h1>
            <p class="text-sm md:text-base opacity-90">
                Order <strong>#{{ $order->order_number }}</strong>
            </p>
        </div>
    </section>

    <section class="bg-white py-10">
        <div class="mx-auto max-w-screen-md px-6">
            {{-- Card --}}
            <form action="{{ route('payment.complete.submit',$order) }}"
                  method="POST" enctype="multipart/form-data"
                  id="corpCompletionForm"
                  class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm space-y-6">
                @csrf

                {{-- Ringkasan --}}
                <div class="text-sm">
                    <div class="flex justify-between">
                        <span>Subtotal</span>
                        <span>Rp {{ number_format($order->price,0,',','.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Ongkir</span>
                        <span>Rp {{ number_format($order->shipping->shipping_cost ?? 0,0,',','.') }}</span>
                    </div>
                    <div class="flex justify-between text-lg font-bold mt-2">
                        <span>Total Bayar</span>
                        <span>
                          Rp {{ number_format(($order->price + ($order->shipping->shipping_cost ?? 0)),0,',','.') }}
                        </span>
                    </div>
                </div>

                {{-- Input file --}}
                <div>
                    <label class="block mb-2 font-medium">File PO / Bukti (PDF/JPG/PNG) *</label>
                    <input type="file" name="transfer_proof_full" id="transfer_proof_full" required
                           accept="application/pdf,image/*"
                           class="w-full border rounded-md p-2 @error('transfer_proof_full') border-red-500 @enderror">
                    @error('transfer_proof_full')
                      <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Preview (jika gambar) --}}
                <div id="preview" class="hidden">
                    <p class="text-sm font-medium mb-2">Preview:</p>
                    <img id="previewImage" class="rounded-lg w-full max-h-64 object-contain">
                    <p id="previewPdf" class="hidden text-sm">
                        File PDF dipilih. <span class="underline cursor-pointer text-primary-700" id="openPdf">Buka PDF</span>
                    </p>
                </div>

                {{-- Tombol --}}
                <div class="flex gap-4">
                    <a href="{{ route('order.show', $order) }}"
                       class="w-1/2 rounded-lg bg-gray-400 px-5 py-2.5 text-center text-sm text-white hover:bg-gray-500">
                       Kembali
                    </a>
                    <button type="submit" id="sendBtn" disabled
                            class="w-1/2 rounded-lg bg-primary-700 px-5 py-2.5 text-sm text-white hover:bg-primary-800 disabled:opacity-50 disabled:cursor-not-allowed">
                        Kirim
                    </button>
                </div>

                {{-- Flash --}}
                @if (session('success'))
                  <p class="text-green-600">{{ session('success') }}</p>
                @endif
                @if (session('error'))
                  <p class="text-red-600">{{ session('error') }}</p>
                @endif
            </form>
        </div>
    </section>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const input = document.getElementById('transfer_proof_full');
        const send  = document.getElementById('sendBtn');
        const prev  = document.getElementById('preview');
        const img   = document.getElementById('previewImage');
        const pdfP  = document.getElementById('previewPdf');
        const open  = document.getElementById('openPdf');

        input?.addEventListener('change', e => {
            const f = e.target.files[0];
            if (!f) return;

            const isPdf = f.type === 'application/pdf';
            prev.classList.remove('hidden');
            img.classList.toggle('hidden', isPdf);
            pdfP.classList.toggle('hidden', !isPdf);

            if (isPdf) {
                // optional: open PDF in new tab
                const url = URL.createObjectURL(f);
                open.onclick = () => window.open(url, '_blank');
            } else {
                const rd = new FileReader();
                rd.onload = ev => img.src = ev.target.result;
                rd.readAsDataURL(f);
            }

            send.disabled = false;
        });
    });
    </script>
</x-layout>
