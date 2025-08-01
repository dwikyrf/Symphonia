<x-layout>
    {{-- ----------  TITLE SLOT  ---------- --}}
    <x-slot name="title">Kategori: {{ $category->name }}</x-slot>

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        {{-- ----------  HEADER  ---------- --}}
        <h2 class="mb-8 text-2xl font-bold text-gray-900">
            Produk dalam “{{ $category->name }}”
        </h2>

        {{-- ----------  EMPTY STATE ---------- --}}
        @if ($products->isEmpty())
            <p class="py-24 text-center text-gray-500">
                Belum ada produk di kategori ini.
            </p>
        @endif

        {{-- ----------  PRODUCT GRID ---------- --}}
        <section class="grid gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
            @foreach ($products as $product)
                <article
                    class="group flex flex-col overflow-hidden rounded-2xl border border-gray-200 bg-white shadow transition hover:shadow-lg">

                    {{-- IMAGE --}}
                    <a href="{{ route('product.show', $product->slug) }}"
                       class="relative block aspect-[4/3] w-full overflow-hidden">
                        @if ($product->image)
                            <img src="{{ asset($product->image) }}"
                                 alt="{{ $product->name }}"
                                 class="h-full w-full object-cover transition duration-300 group-hover:scale-105" />
                        @else
                            <img src="https://flowbite.s3.amazonaws.com/blocks/e-commerce/imac-front.svg"
                                 alt="Placeholder"
                                 class="h-full w-full object-contain p-6 opacity-60" />
                        @endif
                    </a>

                    {{-- CONTENT --}}
                    <div class="flex flex-1 flex-col p-5">
                        <h3 class="line-clamp-2 text-base font-semibold text-gray-900">
                            <a href="{{ route('product.show', $product->slug) }}"
                               class="transition hover:text-primary-700">
                                {{ $product->name }}
                            </a>
                        </h3>

                        {{-- RATING --}}
                        <div class="mt-2 flex items-center gap-1 text-xs">
                            @php $avg = round($product->averageRating()); @endphp
                            @for ($i = 1; $i <= 5; $i++)
                                <svg viewBox="0 0 20 20"
                                     class="h-4 w-4 {{ $i <= $avg ? 'text-yellow-400' : 'text-gray-300' }}"
                                     fill="currentColor">
                                    <path d="M9.049 2.927c.341-.922 1.561-.922 1.902 0l1.316 3.821a1 1 0 00.95.69h4.045c.97 0 1.372 1.24.588 1.81l-3.27 2.375a1 1 0 00-.364 1.118l1.316 3.822c.34.922-.756 1.688-1.539 1.118L10 13.347l-3.27 2.375c-.783.57-1.878-.196-1.539-1.118l1.316-3.822a1 1 0 00-.363-1.118L2.874 9.248c-.784-.57-.381-1.81.588-1.81h4.045a1 1 0 00.95-.69l1.316-3.82z"/>
                                </svg>
                            @endfor
                            <span class="ml-1 text-gray-500">{{ number_format($product->averageRating(), 1) }}</span>
                            <span class="text-gray-400">• {{ $product->reviews->count() }} ulasan</span>
                        </div>

                        {{-- PRICE & CTA --}}
                        <div class="mt-auto flex items-center justify-between pt-4">
                            <span class="text-lg font-extrabold text-gray-900">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </span>
                            <a href="{{ route('product.show', $product->slug) }}"
                               class="rounded-lg bg-primary-600 px-4 py-2 text-xs font-medium text-white transition hover:bg-primary-700">
                                Detail
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
        </section>

        {{-- ----------  PAGINATION ---------- --}}
        <div class="mt-10">
            {{ $products->links() }}
        </div>
    </div>
</x-layout>