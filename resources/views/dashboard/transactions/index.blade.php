<x-layout>
<section class="max-w-6xl mx-auto p-6">
    <h2 class="text-2xl font-bold mb-6">Daftar Transaksi</h2>

    {{-- ==== Filter sederhana ==== --}}
    <form method="GET" class="mb-4 flex flex-wrap gap-3">
        <input type="text" name="q" value="{{ request('q') }}"
               placeholder="Cari order / customer…"
               class="border rounded px-3 py-1 w-64 text-sm">

        <select name="status" class="border rounded px-3 py-1 text-sm">
            <option value="">Semua status</option>
            @foreach(['pending','pending_full','paid_dp','paid','approved','failed'] as $s)
                <option value="{{ $s }}" @selected(request('status')===$s)>
                    {{ ucfirst(str_replace('_',' ',$s)) }}
                </option>
            @endforeach
        </select>

        <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1.5 rounded text-sm">
            Filter
        </button>
    </form>

    {{-- ==== Tabel ==== --}}
    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-left">
            <tr class="whitespace-nowrap">
                <th class="px-4 py-3">#</th>
                <th class="px-4 py-3">Order</th>
                <th class="px-4 py-3">Customer</th>
                <th class="px-4 py-3">Tanggal</th>
                <th class="px-4 py-3">Total</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3 text-center">Aksi</th>
            </tr>
            </thead>

            <tbody>
            @foreach($transactions as $i => $t)
                <tr class="border-b">
                    <td class="px-4 py-2">{{ $transactions->firstItem() + $i }}</td>

                    <td class="px-4 py-2">
                        <a href="{{ route('dashboard.order.show', $t->order_id) }}"
                           class="text-indigo-600 hover:underline">
                            #{{ $t->order?->order_number ?? '-' }}
                        </a>
                    </td>

                    <td class="px-4 py-2">{{ $t->user->name }}</td>

                    <td class="px-4 py-2">
                        {{ optional($t->order_date)->format('d M Y') ?? '-' }}
                    </td>

                    <td class="px-4 py-2">
                        Rp{{ number_format($t->total_payment, 0, ',', '.') }}
                    </td>

                    {{-- badge status --}}
                    <td class="px-4 py-2">
                        @php
                            $colors = [
                                'pending'      => 'bg-yellow-100 text-yellow-800',
                                'pending_full' => 'bg-orange-100 text-orange-800',
                                'paid_dp'      => 'bg-blue-100 text-blue-800',
                                'paid'         => 'bg-green-100 text-green-800',
                                'approved'     => 'bg-green-100 text-green-800',
                                'failed'       => 'bg-red-100 text-red-800',
                            ];
                        @endphp
                        <span class="px-2 py-1 rounded text-xs font-medium {{ $colors[$t->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ strtoupper(str_replace('_',' ',$t->status)) }}
                        </span>
                    </td>

                    {{-- ===== Aksi ===== --}}
                    <td class="px-4 py-2 text-center space-x-1">
                        <a href="{{ route('dashboard.transactions.show', $t) }}"
                           class="inline-block bg-gray-200 hover:bg-gray-300 px-3 py-1.5 rounded text-xs">
                            Detail
                        </a>

                        <a href="{{ route('dashboard.transactions.edit', $t) }}"
                           class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded text-xs">
                            Edit
                        </a>

                        {{-- Hapus --}}
                        <form action="{{ route('dashboard.transactions.destroy', $t) }}"
                              method="POST" class="inline"
                              onsubmit="return confirm('Hapus transaksi ini?')">
                            @csrf @method('DELETE')
                            <button class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded text-xs">
                                Hapus
                            </button>
                        </form>

                        
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="p-4">
            {{ $transactions->withQueryString()->links() }}
        </div>
    </div>
</section>
</x-layout>
