<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>
<form method="GET"
              action="{{ route('product.index') }}"
              class="mb-8 flex w-full max-w-xl overflow-hidden rounded-lg shadow-sm">

            <label for="search" class="sr-only">Cari produk</label>
            <input  id="search"
                    name="search"
                    type="text"
                    value="{{ request('search') }}"
                    placeholder="Cari produk…"
                    class="w-full border-0 bg-gray-100 px-4 py-3 text-sm focus:bg-white focus:outline-none"
            />

            <button type="submit"
                    class="bg-primary-600 px-6 text-sm font-semibold text-white transition hover:bg-primary-700">
                Cari
            </button>

            @if(request()->filled('search'))
                <a href="{{ route('product.index') }}"
                   class="flex items-center bg-gray-200 px-4 text-xs text-gray-600 transition hover:bg-gray-300">
                    Reset
                </a>
            @endif
        </form>

    <section class="py-8 bg-white">
        <div class="grid gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4">
            @foreach ($products as $product)
                <div class="flex flex-col rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                    <div class="h-56 w-full mb-4">
                        <a href="{{ route('product.show', $product->slug) }}">
                            @if($product->image)
                                <img class="mx-auto h-full object-cover rounded" src="{{ asset($product->image) }}" alt="{{ $product->name }}" />
                            @else
                                <img class="mx-auto h-full" src="https://flowbite.s3.amazonaws.com/blocks/e-commerce/imac-front.svg" />
                                <img class="mx-auto hidden h-full" src="https://flowbite.s3.amazonaws.com/blocks/e-commerce/imac-front-dark.svg" />
                            @endif
                        </a>
                    </div>

                    <div class="flex-grow pt-4">
                        <a href="{{ route('product.show', $product->slug) }}" class="text-lg font-semibold leading-tight text-gray-900 hover:underline">
                            {{ $product->name }}
                        </a>

                        <!-- Rating -->
                        <div class="mt-2 flex items-center gap-1">
                            @php $average = round($product->averageRating()); @endphp
                            @for ($i = 1; $i <= 5; $i++)
                                <svg class="h-4 w-4 {{ $i <= $average ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927C9.39 2.005 10.61 2.005 10.951 2.927l1.316 3.821a1 1 0 00.95.69h4.045c.969 0 1.371 1.24.588 1.81l-3.27 2.375a1 1 0 00-.364 1.118l1.316 3.822c.34.922-.755 1.688-1.538 1.118L10 13.347l-3.27 2.375c-.782.57-1.877-.196-1.538-1.118l1.316-3.822a1 1 0 00-.364-1.118L2.874 9.248c-.783-.57-.38-1.81.588-1.81h4.045a1 1 0 00.95-.69l1.316-3.82z"/>
                                </svg>
                            @endfor
                            <span class="ml-1 text-sm text-gray-500">
                                ({{ number_format($product->averageRating(), 1) ?? '0.0' }})
                            </span>
                            <span class="text-xs text-gray-400">• {{ $product->reviews->count() }} ulasan</span>
                        </div>
                    </div>

                    <div class="mt-4 flex items-center justify-between gap-4">
                        <p class="text-2xl font-extrabold leading-tight text-gray-900">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </p>
                        <a href="{{ route('product.show', $product->slug) }}" class="bg-primary-700 text-white px-4 py-2 rounded-lg hover:bg-primary-800">Detail</a>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{ $products->links() }}
</x-layout>
