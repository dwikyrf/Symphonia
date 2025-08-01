<x-layout>
    <section class="bg-white py-8 antialiased md:py-16">
        <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
          <div class="mx-auto max-w-5xl">
            <div class="gap-4 sm:flex sm:items-center sm:justify-between">
              <h2 class="text-xl font-semibold text-gray-900 sm:text-2xl">My Orders</h2>
            </div>
      
            <div class="mt-6 flow-root sm:mt-8">
              <div class="divide-y divide-gray-200">
                @foreach ($orders as $order)
                <div class="flex flex-wrap items-center gap-y-4 py-6">
                  <dl class="w-1/2 sm:w-1/4 lg:w-auto lg:flex-1">
                    <dt class="text-base font-medium text-gray-500">Order ID:</dt>
                    <dd class="mt-1.5 text-base font-semibold text-gray-900">
                      <a href="{{ route('order.show', $order->id) }}" class="hover:underline">#{{ $order->order_number }}</a>
                    </dd>
                  </dl>
      
                  <dl class="w-1/2 sm:w-1/4 lg:w-auto lg:flex-1">
                    <dt class="text-base font-medium text-gray-500">Date:</dt>
                    <dd class="mt-1.5 text-base font-semibold text-gray-900">
                      {{ $order->created_at->format('d M Y') }}
                    </dd>
                  </dl>
      
                  <dl class="w-1/2 sm:w-1/4 lg:w-auto lg:flex-1">
                    <dt class="text-base font-medium text-gray-500">Total Price:</dt>
                    <dd class="mt-1.5 text-base font-semibold text-gray-900">
                      Rp {{ number_format($order->total_price, 0, ',', '.') }}
                    </dd>
                  </dl>
      
                  <dl class="w-1/2 sm:w-1/4 lg:w-auto lg:flex-1">
                    <dt class="text-base font-medium text-gray-500">Status:</dt>
                    <dd class="me-2 mt-1.5 inline-flex items-center rounded 
                      @if ($order->status == 'confirmed') bg-green-100 text-green-800
                      @elseif ($order->status == 'pending') bg-yellow-100 text-yellow-800
                      @elseif ($order->status == 'cancelled') bg-red-100 text-red-800
                      @else bg-gray-100 text-gray-800
                      @endif
                      px-2.5 py-0.5 text-xs font-medium">
                      {{ ucfirst($order->status) }}
                    </dd>
                  </dl>
      
                  <div class="w-full grid sm:grid-cols-2 lg:flex lg:w-64 lg:items-center lg:justify-end gap-4">
                    <!-- Repeat Order Button -->
                    <form action="{{ route('order.repeat', $order->id) }}" method="POST">
                      @csrf
                      <button type="submit" class="w-full rounded-lg bg-primary-700 px-3 py-2 text-sm font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 lg:w-auto">
                        Repeat Order
                      </button>
                    </form>
      
                    <!-- View Order Details -->
                    <a href="{{ route('order.show', $order->id) }}" class="w-full inline-flex justify-center rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:outline-none focus:ring-4 focus:ring-gray-100 lg:w-auto">
                      View Details
                    </a>
                  </div>
                </div>
                @endforeach
              </div>
            </div>
      
            <!-- Pagination -->
            @if ($orders->hasPages())
            <div class="mt-6 flex justify-center">
              {{ $orders->links() }}
            </div>
            @endif
          </div>
        </div>
      </section>
  </x-layout>
  