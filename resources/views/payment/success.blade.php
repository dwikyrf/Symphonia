<x-layout>
    <x-slot:title>Pembayaran Berhasil</x-slot:title>

    <section class="bg-white py-8 antialiased md:py-16">
        <div class="mx-auto max-w-2xl px-4 2xl:px-0">
            <h2 class="text-xl font-semibold text-gray-900 sm:text-2xl mb-2">Thanks for your order!</h2>
            <p class="text-gray-500 mb-6 md:mb-8">
                Your order 
                <a href="{{ route('order.show', $order->id) }}" class="font-medium text-gray-900 hover:underline">
                    #{{ $order->order_number }}
                </a> 
                will be processed within 24 hours.
            </p>

            <div class="space-y-4 sm:space-y-2 rounded-lg border border-gray-100 bg-gray-50 p-6 mb-6 md:mb-8">
                <dl class="sm:flex items-center justify-between gap-4">
                    <dt class="font-normal mb-1 sm:mb-0 text-gray-500">Date</dt>
                    <dd class="font-medium text-gray-900 sm:text-end">
                        {{ $order->created_at->format('d F Y') }}
                    </dd>
                </dl>
                <dl class="sm:flex items-center justify-between gap-4">
                    <dt class="font-normal mb-1 sm:mb-0 text-gray-500">Payment Method</dt>
                    <dd class="font-medium text-gray-900 sm:text-end">
                        Bank Transfer
                    </dd>
                </dl>
                <dl class="sm:flex items-center justify-between gap-4">
                    <dt class="font-normal mb-1 sm:mb-0 text-gray-500">Name</dt>
                    <dd class="font-medium text-gray-900 sm:text-end">
                        {{ $order->user->name }}
                    </dd>
                </dl>
                <dl class="sm:flex items-center justify-between gap-4">
                    <dt class="font-normal mb-1 sm:mb-0 text-gray-500">Address</dt>
                    <dd class="font-medium text-gray-900 sm:text-end">
                        {{ $order->address ? $order->address->fullAddress() : '-' }}
                    </dd>
                </dl>
            </div>

            <div class="flex items-center space-x-4">
                <a href="{{ route('order.show', $order->id) }}"
                   class="text-white bg-primary-700 hover:bg-primary-800 font-medium rounded-lg text-sm px-5 py-2.5">
                    Track your order
                </a>
                <a href="{{ route('product.index') }}"
                   class="py-2.5 px-5 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 hover:text-primary-700">
                    Return to shopping
                </a>
            </div>
        </div>
    </section>
</x-layout>
