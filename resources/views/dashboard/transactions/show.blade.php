@php use Illuminate\Support\Str; @endphp
<x-layout>
<section class="bg-white">
    <div class="mx-auto max-w-3xl py-8 px-4 lg:py-16">

        {{-- Header --}}
        <h2 class="mb-6 text-2xl font-bold text-gray-900">
            Transaction Details
            <span class="ml-2 text-sm font-normal text-gray-500">
                #{{ $transaction->transaction_id }}
            </span>
        </h2>

        <div class="rounded-lg bg-gray-100 p-6 shadow space-y-6">

            {{-- Metadata grid --}}
            <div class="grid gap-4 sm:grid-cols-2">
                {{-- Customer --}}
                <x-meta-item label="Customer">
                    {{ $transaction->user->name }}
                </x-meta-item>

                {{-- Order number --}}
                <x-meta-item label="Order">
                    <a href="{{ route('dashboard.order.show', $transaction->order_id) }}"
                       class="text-indigo-600 hover:underline">
                        #{{ $transaction->order?->order_number ?? '-' }}
                    </a>
                </x-meta-item>

                {{-- Order date --}}
                <x-meta-item label="Order Date">
                    {{ $transaction->order_date->format('d M Y') }}
                </x-meta-item>

                {{-- Total payment --}}
                <x-meta-item label="Total Payment">
                    Rp{{ number_format($transaction->total_payment, 0, ',', '.') }}
                </x-meta-item>

                {{-- Status --}}
                <x-meta-item label="Status">
                    <x-badge :status="$transaction->status"/>
                </x-meta-item>
            </div>
{{-- Transfer proofs --}}
<div class="border-t pt-6 space-y-4">
    <h3 class="font-semibold text-gray-800 mb-2">Transfer Proofs</h3>

    @foreach ([
        'dp'   => ['label' => 'DP',   'file' => $transaction->transfer_proof_dp,
                   'verified' => $transaction->is_verified_dp],
        'full' => ['label' => 'Full', 'file' => $transaction->transfer_proof_full,
                   'verified' => $transaction->is_verified_full],
    ] as $stage => $item)
        @if ($item['file'])
            <div class="flex items-center justify-between bg-white p-3 rounded border">
                <div>
                    <p class="text-sm text-gray-700">
                        {{ $item['label'] }} transfer proof
                        @if ($item['verified'])
                            <span class="ml-2 text-green-600 text-xs">✔ verified</span>
                        @else
                            <span class="ml-2 text-yellow-600 text-xs">pending</span>
                        @endif
                    </p>

                    {{-- gunakan route untuk private file --}}
                    <a href="{{ route('dashboard.transfer-proof.show', [$transaction->id, $stage]) }}"
                       target="_blank"
                       class="text-indigo-600 hover:underline text-sm">
                        View / Download
                    </a>
                </div>

                {{-- tombol verifikasi bila perlu --}}
                @unless ($item['verified'])
                    <form method="POST"
                          action="{{ route('dashboard.transactions.verify', $transaction) }}">
                        @csrf
                        <input type="hidden" name="stage" value="{{ $stage }}">
                        <x-button type="submit" size="sm" variant="success">
                            Verify {{ $item['label'] }}
                        </x-button>
                    </form>
                @endunless
            </div>
        @endif
    @endforeach
</div>

            {{-- Actions --}}
            <div class="flex justify-between pt-4 border-t">
                <x-link-button :href="route('dashboard.transactions.index')" variant="secondary">
                    ← Back
                </x-link-button>

                <div class="space-x-2">
                    <x-link-button :href="route('dashboard.transactions.edit', $transaction)">
                        Edit
                    </x-link-button>
                    <form x-data method="POST"
                          action="{{ route('dashboard.transactions.destroy', $transaction) }}"
                          onsubmit="return confirm('Hapus transaksi ini?')" class="inline">
                        @csrf @method('DELETE')
                        <x-button variant="error">Delete</x-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
</x-layout>