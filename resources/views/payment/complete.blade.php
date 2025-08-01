<x-layout>
    <x-slot:title>Upload PO / Bukti Pembayaran #{{ $order->order_number }}</x-slot:title>

    <section class="bg-gray-50 py-8 md:py-16">
        <div class="max-w-screen-md mx-auto bg-white p-6 md:p-8 rounded-xl shadow-lg">

            <h2 class="text-2xl font-bold text-center text-gray-900 mb-6">
                Upload Purchase Order / Bukti Pembayaran
            </h2>

            <p class="text-gray-700 mb-4">
                Total tagihan: <strong>Rp {{ number_format($order->total_price,0,',','.') }}</strong>.
                Unggah PDF / JPG / PNG PO (atau bukti transfer) Anda di bawah ini.
            </p>

            <form action="{{ route('payment.complete.submit',$order->id) }}"
                  method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div>
                    <label class="block mb-2 font-medium">File PO / Bukti*</label>
                    <input type="file" name="transfer_proof_full" required
                           accept="application/pdf,image/*"
                           class="w-full border rounded-md p-2 @error('transfer_proof_full') border-red-500 @enderror">
                    @error('transfer_proof_full')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                        class="w-full md:w-auto bg-primary-700 hover:bg-primary-800 text-white px-6 py-3 rounded-md">
                    Kirim
                </button>
            </form>

        </div>
    </section>
</x-layout>