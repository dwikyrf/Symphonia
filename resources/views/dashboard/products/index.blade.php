<x-layout>
    {{-- Flash success --}}
    @if (session('success'))
        <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <section class="bg-gray-50 p-3 sm:p-5">
        <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
            {{-- Container utama --}}
            <div class="bg-white relative shadow-md sm:rounded-lg overflow-hidden">

                {{-- Header & Search --}}
                <div class="flex flex-col md:flex-row items-center justify-between space-y-3
                            md:space-y-0 md:space-x-4 p-4">
                    {{-- Form pencarian --}}
                    <div class="w-full md:w-1/2">
                        <form action="{{ route('dashboard.products.index') }}" method="GET"
                              class="flex items-center">
                            <label for="search" class="sr-only">Search</label>
                            <div class="relative w-full">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                    <svg aria-hidden="true" class="w-5 h-5 text-gray-500" fill="currentColor"
                                         viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                              d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817
                                                 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                              clip-rule="evenodd" />
                                    </svg>
                                </span>
                                <input type="text" name="search" id="search"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                              focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2"
                                       placeholder="Search product..."
                                       value="{{ request('search') }}">
                            </div>
                            <button type="submit"
                                    class="ml-2 px-4 py-2 text-sm font-medium text-white bg-primary-700
                                           hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 rounded-lg">
                                Search
                            </button>
                        </form>
                    </div>

                    {{-- Tombol Add + dropdown aksi / filter --}}
                    <div
                        class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch
                               md:items-center justify-end md:space-x-3 flex-shrink-0">

                        <a href="{{ route('dashboard.products.create') }}"
                           class="flex items-center justify-center text-white bg-primary-700 hover:bg-primary-800
                                  focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2">
                            <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                      d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2
                                         0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                            </svg>
                            Add Product
                        </a>

                        {{-- (Dropdown aksi & filter jika diperlukan, tidak diubah) --}}
                        {{-- … --}}
                    </div>
                </div>

                {{-- Modal Add Product (tidak berubah) --}}
                {{-- … --}}

                {{-- Tabel Produk --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-4 py-3">Title</th>
                                <th scope="col" class="px-4 py-3">Category</th>
                                <th scope="col" class="px-4 py-3">Body</th>
                                <th scope="col" class="px-4 py-3">Price</th>
                                <th scope="col" class="px-4 py-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr class="border-b">
                                    <td class="px-4 py-3">{{ $product->name }}</td>
                                    <td class="px-4 py-3">{{ $product->category->name }}</td>
                                    <td class="px-4 py-3">
                                        {{ \Illuminate\Support\Str::limit($product->description, 50, '…') }}
                                    </td>
                                    <td class="px-4 py-3">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </td>

                                    {{-- kolom Action: ditandai relative supaya dropdown absolute bisa melayang --}}
                                    <td class="px-4 py-3 flex items-center justify-end relative">
                                        {{-- Tombol toggle dropdown --}}
                                        <button id="dropdown-button-{{ $product->id }}"
                                                data-dropdown-toggle="dropdown-{{ $product->id }}"
                                                class="inline-flex items-center p-2 text-sm font-medium text-center
                                                       text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M6 10a2 2 0 11-4 0 2 2 0 014
                                                       0zM12 10a2 2 0 11-4 0 2 2 0 014
                                                       0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                            </svg>
                                        </button>

                                        {{-- Dropdown menu (absolute + z‑index tinggi) --}}
                                        <div id="dropdown-{{ $product->id }}"
                                             class="hidden absolute right-0 mt-2 w-44 z-20 bg-white rounded shadow">
                                            <ul class="py-1 text-sm text-gray-700">
                                                <li>
                                                    <a href="{{ route('dashboard.products.show', $product->slug) }}"
                                                       class="block py-2 px-4 hover:bg-gray-100">
                                                        Show
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('dashboard.products.edit', $product->slug) }}"
                                                       class="block py-2 px-4 hover:bg-gray-100">
                                                        Edit
                                                    </a>
                                                </li>
                                            </ul>
                                            <div class="py-1 border-t">
                                                <form action="{{ route('dashboard.products.destroy', $product->slug) }}"
                                                      method="POST"
                                                      onsubmit="return confirm('Are you sure you want to delete this product?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="block w-full text-left py-2 px-4 text-sm text-gray-700
                                                                   hover:bg-gray-100">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="p-4 flex justify-between items-center">
                    @if ($products->count() > 0)
                        <span class="text-sm text-gray-500">
                            Showing
                            <span class="font-semibold text-gray-900">{{ $products->firstItem() }}</span> to
                            <span class="font-semibold text-gray-900">{{ $products->lastItem() }}</span> of
                            <span class="font-semibold text-gray-900">{{ $products->total() }}</span> results
                        </span>
                    @endif
                    <div class="pagination">
                        {{ $products->links('vendor.pagination.tailwind') }}
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Pastikan skrip dropdown Flowbite aktif --}}
    <script src="https://unpkg.com/flowbite@latest/dist/flowbite.min.js"></script>
</x-layout>