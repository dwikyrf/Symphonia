<x-layout>
    <x-slot:title>Edit Pengiriman</x-slot:title>

    <section class="bg-white py-8">
        <div class="max-w-xl mx-auto px-4">
            <h2 class="text-xl font-bold text-gray-900 mb-6">
                Edit Pengiriman untuk Order #{{ $order->order_number }}
            </h2>
            <a href="{{ route('dashboard.shipping.index') }}"
                   class="inline-flex items-center px-3 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 text-sm">
                    ← Kembali
                </a>
            {{-- tampilkan error validasi --}}
            @if ($errors->any())
                <div class="mb-4 rounded bg-red-50 p-4 text-red-700">
                    <ul class="ml-4 list-disc">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('dashboard.shipping.update', $order) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

    {{-- kolom yang HANYA ditampilkan (read-only) --}}
    {{-- … sama seperti contoh sebelumnya … --}}
                <div>
                    <label class="block font-medium text-gray-700">Nama Kurir</label>
                    <input type="text" value="{{ $shipping->courier_name }}" readonly class="w-full mt-1 bg-gray-100 border-gray-300 rounded">
                </div>

                <div>
                    <label class="block font-medium text-gray-700">Kode Layanan</label>
                    <input type="text" value="{{ $shipping->service_code }}" readonly class="w-full mt-1 bg-gray-100 border-gray-300 rounded">
                </div>

                <div>
                    <label class="block font-medium text-gray-700">Biaya Pengiriman</label>
                    <input type="text" value="Rp {{ number_format($shipping->shipping_cost, 0, ',', '.') }}" readonly class="w-full mt-1 bg-gray-100 border-gray-300 rounded">
                </div>

                <div>
                    <label class="block font-medium text-gray-700">Estimasi Hari</label>
                    <input type="text" value="{{ $shipping->estimated_days }}" readonly class="w-full mt-1 bg-gray-100 border-gray-300 rounded">
                </div>
    {{-- ① Nomor Resi --}}
                <div>
                    <label class="block font-medium">Nomor Resi</label>
                    <input  type="text" name="tracking_number"
                            value="{{ old('tracking_number', $shipping->tracking_number) }}"
                            class="w-full rounded border-gray-300" />
                </div>

                {{-- ② Status --}}
                <div>
                    <label class="block font-medium">Status</label>
                    <select name="status" class="w-full rounded border-gray-300" required>
                        <option value="pending"  @selected($shipping->status==='pending') >Pending</option>
                        <option value="dikirim"  @selected($shipping->status==='dikirim') >Dikirim</option>
                        <option value="diterima" @selected($shipping->status==='diterima')>Diterima</option>
                    </select>
                </div>

                {{-- ③ Tanggal dikirim --}}
                <div>
                    <label class="block font-medium">Tanggal Dikirim</label>
                    <input  type="datetime-local" name="shipped_at"
                            value="{{ old('shipped_at', optional($shipping->shipped_at)->format('Y-m-d\TH:i')) }}"
                            class="w-full rounded border-gray-300" />
                </div>

                {{-- ④ Tanggal diterima --}}
                <div>
                    <label class="block font-medium">Tanggal Diterima</label>
                    <input  type="datetime-local" name="delivered_at"
                            value="{{ old('delivered_at', optional($shipping->delivered_at)->format('Y-m-d\TH:i')) }}"
                            class="w-full rounded border-gray-300" />
                </div>

                <button class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
                    Simpan Perubahan
                </button>
            </form>

        </div>
    </section>
</x-layout>
