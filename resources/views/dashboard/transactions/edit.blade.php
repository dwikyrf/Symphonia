<x-layout>
    <section class="bg-white">
        <div class="mx-auto max-w-2xl py-8 px-4 lg:py-16">

            <h2 class="mb-6 text-2xl font-bold text-gray-900">
                Edit Transaction
                <span class="ml-2 text-sm font-normal text-gray-500">
                    (#{{ $transaction->transaction_id }})
                </span>
            </h2>

            {{-- Validation --}}
            @if ($errors->any())
                <div class="mb-6 rounded bg-red-50 p-4 text-sm text-red-700">
                    <ul class="list-disc ml-5 space-y-1">
                        @foreach ($errors->all() as $err) <li>{{ $err }}</li> @endforeach
                    </ul>
                </div>
            @endif

            <form  method="POST"
                   action="{{ route('dashboard.transactions.update', $transaction) }}"
                   class="space-y-6 rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                @csrf
                @method('PUT')

                {{-- Order Date → default hari ini --}}
                <div>
                    <label for="order_date"
                           class="mb-1 block text-sm font-medium text-gray-900">
                        Order Date
                    </label>
                    <input  type="date"
                            id="order_date"
                            name="order_date"
                            value="{{ old('order_date', now()->toDateString()) }}"
                            class="block w-full rounded-lg border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900
                                   focus:border-primary-600 focus:ring-primary-600"
                            required>
                </div>

                {{-- Total Payment (read-only) --}}
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-900">
                        Total Payment (Rp)
                    </label>

                    {{-- tampilan hanya teks --}}
                    <div class="rounded-lg bg-gray-50 p-2.5 text-sm text-gray-700 border border-gray-200">
                        Rp {{ number_format($transaction->total_payment, 0, ',', '.') }}
                    </div>

                    {{-- hidden agar value tetap terkirim --}}
                    <input type="hidden" name="total_payment" value="{{ $transaction->total_payment }}">
                </div>

                {{-- Status enum --}}
                <div>
                    <label for="status"
                           class="mb-1 block text-sm font-medium text-gray-900">
                        Transaction Status
                    </label>
                    <select id="status" name="status"
                            class="block w-full rounded-lg border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900
                                   focus:border-primary-600 focus:ring-primary-600" required>
                        @php
                            $options = [
                                'pending'       => 'Pending DP',
                                'pending_full'  => 'Pending Pelunasan',
                                'pending_po'    => 'Pending PO',
                                'paid_dp'       => 'DP Paid',
                                'paid'          => 'Paid Lunas',
                                'approved'      => 'PO Approved',
                                'failed'        => 'Failed / Reject',
                            ];
                        @endphp
                        @foreach ($options as $val => $label)
                            <option value="{{ $val }}"
                                    @selected(old('status', $transaction->status) === $val)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Action btn --}}
                <div class="flex justify-between">
                    <a  href="{{ route('dashboard.transactions.index') }}"
                        class="inline-flex items-center rounded-lg bg-gray-200 px-5 py-2.5 text-sm font-medium
                               text-gray-900 hover:bg-gray-300">
                        ← Back
                    </a>

                    <button type="submit"
                            class="inline-flex items-center rounded-lg bg-primary-700 px-5 py-2.5 text-sm font-medium
                                   text-white hover:bg-primary-800 focus:ring-4 focus:ring-primary-200">
                        Update Transaction
                    </button>
                </div>
            </form>
        </div>
    </section>
</x-layout>
