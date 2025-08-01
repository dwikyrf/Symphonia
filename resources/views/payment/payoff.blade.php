{{-- resources/views/payment/payoff.blade.php --}}
<x-layout>
    <x-slot:title>Pelunasan • Order #{{ $transaction->order->order_number }}</x-slot:title>

    {{-- ---------- Hero ---------- --}}
    <section class="relative isolate overflow-hidden bg-gradient-to-br from-primary-600 via-primary-500 to-primary-400 py-16">
        <div class="absolute inset-0 bg-[url('/img/shape.svg')] bg-center bg-cover opacity-10"></div>

        <div class="relative z-10 mx-auto max-w-screen-md px-6 text-center text-white">
            <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight mb-2">
                Pelunasan Pembayaran
            </h1>
            <p class="text-sm md:text-base opacity-90">
                Order&nbsp;<strong>#{{ $transaction->order->order_number }}</strong>
            </p>
        </div>
    </section>

    {{-- ---------- Card utama ---------- --}}
    <section class="py-10 md:py-14 bg-gray-50">
        <div class="mx-auto max-w-screen-md px-6 md:px-0">
            <div class="rounded-2xl bg-white shadow-xl ring-1 ring-gray-100 overflow-hidden">

                {{-- Ribbon sisa tagihan --}}
                <div class="bg-gray-900/90 px-6 py-4 text-center text-white">
                    <span class="text-sm md:text-base">Sisa tagihan</span>
                    <span class="block text-xl md:text-2xl font-bold tracking-wide">
                        Rp {{ number_format($transaction->order->remaining_balance, 0, ',', '.') }}
                    </span>
                </div>

                {{-- Konten form --}}
                <div class="p-6 md:p-8">
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
                    <p class="mb-6 text-gray-700">
                        Silakan unggah bukti transfer untuk melunasi pembayaran Anda.
                        Kami akan memverifikasinya maksimal 1 × 24 jam.
                    </p>

                    <form class="space-y-6"
                          action="{{ route('payment.payoff.submit', $transaction->id) }}"
                          method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Input bukti --}}
                        <div>
                            <label class="mb-2 inline-block font-medium text-gray-800">
                                Bukti Transfer <span class="text-red-600">*</span>
                            </label>
                            <input
                                name="transfer_proof_full"
                                type="file"
                                accept="application/pdf,image/*"
                                required
                                class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm file:mr-4 file:rounded file:border-0 file:bg-primary-600 file:px-4 file:py-2.5 file:text-sm file:font-medium file:text-white hover:file:bg-primary-700 focus:outline-none @error('transfer_proof_full') border-red-500 @enderror"
                            >
                            @error('transfer_proof_full')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tombol --}}
                        <div class="flex flex-col sm:flex-row gap-4 pt-2">
                            <a href="{{ route('order.show', $transaction->order) }}"
                               class="inline-flex justify-center rounded-lg bg-gray-200 px-6 py-3 text-sm font-medium text-gray-800 hover:bg-gray-300">
                                Kembali ke Detail Order
                            </a>

                            <button type="submit"
                                    class="inline-flex justify-center rounded-lg bg-primary-600 px-6 py-3 text-sm font-medium text-white shadow-md hover:bg-primary-700 focus:outline-none">
                                Kirim Bukti Pelunasan
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </section>
</x-layout>
